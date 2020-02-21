<?php
namespace BOTK;

use BOTK\Exception\FactsValidatorWarning;
use BOTK\Exception\InvalidRawDataWarning;
use BOTK\Exception\TooManyErrorsException;
use BOTK\Exception\TooManyInsanesException;

/**
 * Create structured data from an array of raw data (i.e. ie just a sequence of scalars) managing errors.
 * The class provides a RDF triple counter and tresholds for data processinge errors
 * Options:
 * 	'datamapper' a callable that accepts a raw data array and create structured data as an array. Must be provided.
 *  'rawdataSanitizer a callable that validate raw data before datamapper. It returns an array of raw data of false if rawdata is invalid.
 *  'dataCleaner' a callable that filters the structured data returned by datamapper before to instanciate data model, 
 * 					by default it removes all empty properties.
 *  'factsErrorDetector' a callable that validate computed facts. It accepts a ModelInterface and returns an error description  or false. 
 * 					By defaults accepted raw data that produces empty facts are considered errors.
 */
class FactsFactory implements FactsFactoryInterface {
	
	protected $profile;
	protected $modelClass;
	protected $counter = array(
		'triple'		=> 0,			// rdf triples in facts
		'error'			=> 0,			// facts contains error
		'insane'		=> 0,			// raw data unaccepted
		'entity'		=> 0,			// raw data processed
	);
	
	
	public function __construct( array $profile =array() )
	{
		$defaults = array(
			'model'					  => 'LocalBusiness',
			'modelOptions'			  => array(),
			'entityThreshold'		  => 100, // min numbers of entity that trigger error resilence computation.
			'resilienceToErrors' 	  => 0.3, // if more than 30% of error throws a TooManyErrorException
			'resilienceToInsanes'	  => 0.9, // if more than 90% of unacceptable data throws a TooManyErrorException
			'source' 			  	  => null,	
			'datamapper'			  => function($rawdata){return $rawdata;},
			'dataCleaner' 		  	  => get_class().'::REMOVE_EMPTY',
			'factsErrorDetector' 	  => get_class().'::NOT_EMPTY_FACTS',
			'rawdataSanitizer' 		  => function($rawdata){return is_array($rawdata)?$rawdata:false;},
		);
		$this->profile = array_merge($defaults,$profile);
		$this->modelClass = class_exists($this->profile['model'])
			?$this->profile['model']
			:('\BOTK\Model\\'.$this->profile['model']);
		
		if( !class_exists($this->modelClass) || !is_subclass_of($this->modelClass, '\BOTK\ModelInterface')){
			throw new \InvalidArgumentException("The provided model ({$this->profile['model']} is unknown");	
		}
		if( !is_callable($this->profile['datamapper'])) {
			throw new \InvalidArgumentException("Invalid datamapper callback");	
		}
		if( !is_callable($this->profile['dataCleaner'])) {
			throw new \InvalidArgumentException("Invalid dataCleaner callback");	
		}
		if( !is_callable($this->profile['rawdataSanitizer'])) {
			throw new \InvalidArgumentException("Invalid rawdataSanitizer callback");	
		}
		if( !is_callable($this->profile['factsErrorDetector'])) {
			throw new \InvalidArgumentException("Invalid factsErrorDetector callback");	
		}
	}
	
	
	/**
	 * two level filter array, a default for dataCleaner callback
	 */
	public static function REMOVE_EMPTY( array $data)
	{
		$a = array();
	    foreach ($data as $key => $value) {
	       $a[$key] = is_array($value)?array_filter($value):$value;
	    }
	    return array_filter($a);
	}
	
	/**
	 * a default for dataValidator callback  
	 */
	public static function NOT_EMPTY_FACTS( \BOTK\ModelInterface $data)
	{
		return $data->getTripleCount()?false:'No facts found.';
	}
	
	
	/**
	 * create facts from rawdata. Please nothe that null facts does not means always an error (i.e. no facts is a fact).
	 * if you do not want empty facts use dataValidator
	 */
	public function factualize($rawData)
	{
		$rawdataSanitizer = $this->profile['rawdataSanitizer'];
		$validRawData = $rawdataSanitizer($rawData);
		$this->counter['entity']++;
		
		if (!empty($validRawData)){
			$datamapper = $this->profile['datamapper'];
			$dataCleaner = $this->profile['dataCleaner'];
			$factsErrorDetector = $this->profile['factsErrorDetector'];
			$data =$dataCleaner($datamapper($validRawData));
			$facts = call_user_func($this->modelClass.'::fromArray',$data,$this->profile['modelOptions']);
			$this->counter['triple'] += $facts->getTripleCount();
			if($error=$factsErrorDetector($facts)){
				$this->counter['error']++;
				throw new FactsValidatorWarning($error,$facts);
			}
		} else {
			$this->counter['insane']++;
			throw new InvalidRawDataWarning("Invalid rawdata",$rawData);
		}
		
		// ensure that not too many errors
		$errorRate = ($this->counter['error']/$this->counter['entity']);
		if(( $this->counter['entity'] > $this->profile['entityThreshold']) 
				&& ( $errorRate > $this->profile['resilienceToErrors'])){
			$x = $this->profile['resilienceToErrors']*100;
			throw new TooManyErrorsException("Error rate in data processing exceeded the $x% threshold");			
		}

		// ensure that not too many insaness raw data
		$insaneRate = ($this->counter['insane']/$this->counter['entity']);
		if(( $this->counter['entity'] > $this->profile['entityThreshold']) 
				&& ($insaneRate > $this->profile['resilienceToInsanes'])){
			$x = $this->profile['resilienceToInsanes']*100;
			throw new TooManyInsanesException("Unacceptable data rate exceeded the $x% threshold");			
		}

		return $facts;
	}
	
	
	public function generateLinkedDataHeader()
	{
		return call_user_func($this->modelClass.'::getTurtleHeader'); 
	}
	
	
	public function generateLinkedDataFooter()
	{
		$now = date('c');
		$rdf = "
#@prefix dct: <http://purl.org/dc/terms/> .
#@prefix void: <http://rdfs.org/ns/void#> .
#@prefix prov: <http://www.w3.org/ns/prov#> .
";

		// add  provenance info
		$rdf .= "#<> prov:generatedAtTime \"$now\"^^xsd:dateTime;";
		if(!empty($this->profile['source'])){
			$rdf.= "dct:source <{$this->profile['source']}>;";	
		}
		
		
		// add dataset info and a human readable comment as last line
		$rdf.= "foaf:primaryTopic <#dataset>.\n";
		$rdf.= "#<#dataset> a void:Dataset; void:datadump <>;void:triples {$this->counter['triple']} ;void:entities {$this->counter['entity']}.\n";
		$rdf.= "#Generated {$this->counter['triple']} good triples from {$this->counter['entity']} entities ({$this->counter['insane']} ignored), {$this->counter['error']} errors\n";
		
		return $rdf;
	}


	public function addToCounter($counter,$val=1)
	{
		if(!array_key_exists($counter,$this->counter)){
			throw new \InvalidArgumentException("Invalid counter name");
		}
		$this->counter[$counter]+= intval($val);
	}
	
	
	public function getCounters()
	{
		return $this->counter;
	}

}