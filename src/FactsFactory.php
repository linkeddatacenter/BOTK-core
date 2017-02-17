<?php
namespace BOTK;

class FactsFactory implements FactsFactoryInterface {
	
	protected $profile;
	protected $className;
	protected $tripleCount =0;
	protected $errors = array();
	protected $errorCount=0;
	protected $unacceptableCount=0;
	protected $entityCount=0;
	
	
	public function __construct( array $profile )
	{
		$defaults = array(
			'model'					  => 'LocalBusiness',
			'options'				  => array(),
			'source' 			  	  => null,
			'resilience' 			  => 0.3,
			'datamapper'			  => function($rawdata){return array();},
			'rawDataValidationFilter' => function($rawdata){return is_array($rawdata);},	
		);
		$this->profile = array_merge($defaults,$profile);
		$this->className = class_exists($this->profile['model'])
			?$this->profile['model']
			:('\BOTK\Model\\'.$this->profile['model']);
		
		assert(class_exists($this->className));
	}
	
	
	/**
	 * two level filter array
	 */
	public function removeEmpty( array $data )
	{
		$a = array();
	    foreach ($data as $key => $value) {
	       $a[$key] = is_array($value)?array_filter($value):$value;
	    }
	    return array_filter($a);
	}
	
	
	protected function createFacts($data=array())
	{
		$facts = new $this->className($data,$this->profile['options']);
		assert($facts instanceof ModelInterface);
		return $facts;	
	}
	
	
	public function factualize( array $rawData )
	{
		$datamapper = $this->profile['datamapper'];
		$data =$this->removeEmpty($datamapper($rawData));
		
		$facts = $this->createFacts($data);
		
		if($facts){
			$this->entityCount++;
			$this->tripleCount+=$facts->getTripleCount();
		}
		return $facts;
	}
	
	
	public function generateLinkedDataHeader()
	{
		assert(is_subclass_of($this->className,'\BOTK\ModelInterface'));
		$class = $this->className;
		return $class::getTurtleHeader(); 
	}
	
	
	public function generateLinkedDataFooter()
	{
		$now = date('c');
		$rdf = "\n<> ";
		$this->tripleCount += 6;

		// add  provenance info
		$verb=$this->tooManyErrors()?'invalidated':'generated';
		$rdf .= "prov:{$verb}AtTime \"$now\"^^xsd:dateTime;";
		if(!empty($this->profile['source'])){
			$rdf.= "dct:source <{$this->profile['source']}>;";	
			$this->tripleCount++;
		}
		
		// add dataset info and a human readable comment as last line
		$rdf.= "foaf:primaryTopic <#dataset>.\n";
		$rdf.= "<#dataset> a void:Dataset; void:datadump <>;void:triples {$this->tripleCount} ;void:entities {$this->entityCount}.\n";
		$rdf.= "# File **$verb** with {$this->tripleCount} good triples from {$this->entityCount} entities ({$this->unacceptableCount} ignored), {$this->errorCount} errors\n";
		
		return $rdf;
	}
	
	
	public function  addToTripleCounter( $triplesCount)
	{
		$this->tripleCount += intval($triplesCount);
		return $this;
	}	
	
	
	public function getTripleCount()
	{
		return $this->tripleCount;
	}
	
		
	public function addError($error)
	{
		$this->errors[]= (string) $error;
		$this->errorCount++;
		return $this;
	}


	public function tooManyErrors()
	{
		if( $this->entityCount < 100){ return false; }  // if less than 100 entity do not check
		
		return ($this->errorCount/$this->entityCount) > $this->profile['resilience'];
	}


	public function acceptable( $rawdata)
	{
		$rawValisdator = $this->profile['rawDataValidationFilter'];
		
		if(!($valid = $rawValisdator($rawdata))){
			$this->unacceptableCount++;
		}
		return $valid;
	}

}