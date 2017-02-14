<?php
namespace BOTK\Model;

abstract class AbstractModel 
{
	
	/**
	 * 
	 * MUST be redefined by concrete class with the model schema 
	 * Each array element is composed by a propery name and and property options.
	 * Property option is an array with following (optional) fields:
	 * 		'default' 	a value to be used for the propery
	 * 		'filter' 	a php filter 
	 * 		'options' 	php filter options
	 * 		'flags'		php filter flags
	 * 
	 * Example:array (
	 *	'legalName'			=> array(
	 *							'filter'    => FILTER_CALLBACK,	
	 *	                        'options' 	=> '\BOTK\Filters::FILTER_NORMALIZZE_ADDRESS',
	 *		                   ),
	 *	'alternateName'		=> array(		
                            	'flags'  	=> FILTER_FORCE_ARRAY,
	 * 						),
	 * 	'postalCode'		=> array(	// italian rules
	 *							'filter'    => FILTER_VALIDATE_REGEXP,	
	 *	                        'options' 	=> array('regexp'=>'/^[0-9]{5}$/'),
	 *                      	'flags'  	=> FILTER_REQUIRE_SCALAR,
	 *		                   ),
	 * )
	 */
	protected static $DEFAULT_OPTIONS  = array(
		'uri'				=> array(
								'filter'    => FILTER_SANITIZE_URL,
                            	'flags'  	=> FILTER_REQUIRE_SCALAR,
			                   ),
		'base'				=> array(
								'default'	=> 'http://linkeddata.center/botk/resource/',
								'filter'    => FILTER_SANITIZE_URL,
                            	'flags'  	=> FILTER_REQUIRE_SCALAR,
			                   ),
		'id'				=> array(		
								'filter'    => FILTER_CALLBACK,
		                        'options' 	=> '\BOTK\Filters::FILTER_SANITIZE_ID',
                            	'flags'  	=> FILTER_REQUIRE_SCALAR,
			                   ),
		'page'				=> array(	
								'filter'    => FILTER_SANITIZE_URL,
                            	'flags'  	=> FILTER_FORCE_ARRAY,
			                   ),
		'homepage'			=> array(	
								'filter'    => FILTER_SANITIZE_URL,
                            	'flags'  	=> FILTER_FORCE_ARRAY,
			                   ),
		'mailbox'			=> array(	
								'filter'    => FILTER_CALLBACK,
		                        'options' 	=> '\BOTK\Filters::FILTER_SANITIZE_EMAIL',
                            	'flags'  	=> FILTER_FORCE_ARRAY,
			                   ),
	);
	
	protected $options ;
	protected $vocabulary = array(
		'rdf'		=> 'http://www.w3.org/1999/02/22-rdf-syntax-ns#',
		'rdfs'		=> 'http://www.w3.org/2000/01/rdf-schema#',
		'owl'		=> 'http://www.w3.org/2002/07/owl#',
		'xsd' 		=> 'http://www.w3.org/2001/XMLSchema#',
		'dct' 		=> 'http://purl.org/dc/terms/',
		'void' 		=> 'http://rdfs.org/ns/void#',
		'prov' 		=> 'http://www.w3.org/ns/prov#',
		'schema'	=> 'http://schema.org/',
		'wgs' 		=> 'http://www.w3.org/2003/01/geo/wgs84_pos#',
		'foaf' 		=> 'http://xmlns.com/foaf/0.1/',
		'dq'		=> 'http://purl.org/linked-data/cube#',
		'daq'		=> 'http://purl.org/eis/vocab/daq#',
		'botk' 		=> 'http://http://linkeddata.center/botk/v1#',
	);
	
	protected $data;
	protected $rdf =null; //lazy created
	protected $tripleCount=0; //lazy created
	protected $uniqueIdGenerator=null; // dependency injections
	protected $droppedFields = array();
	
	abstract public function asTurtle();

	protected function mergeOptions( array $options1, array $options2 )
	{
    	foreach($options2 as $property=>$option){
			
			$options1[$property]=isset($options1[$property])
				?array_merge($options1[$property], $option)
				:$option;
    	}
		
		return $options1;
	}

    public function __construct(array $data = array(), array $customOptions = array()) 
    {
		$options = $this->mergeOptions(self::$DEFAULT_OPTIONS,$customOptions);
		
		// set default values
		foreach( $options as $property=>$option){	
			if(empty($data[$property]) && isset($option['default'])){
				$data[$property] = $option['default'];
			}
		}

		// ensure data are sanitized and validated
		$sanitizedData = array_filter( filter_var_array($data, $options));
		
		// find and register dropped fields
		foreach($data as $property=>$value){
			if($value && empty($sanitizedData[$property])){
				$this->droppedFields[]=$property;
			}
		}

		$this->options = $options;
		$this->data = $sanitizedData;
		$this->setIdGenerator(function($data){return uniqid();});
    }
	
	
	public function getDroppedFields()
	{
		return $this->droppedFields;
	}

	
	/**
	 * dependecy injection setter 
	 */
	public function setIdGenerator($generator)
	{
		assert( is_callable($generator));
		$this->uniqueIdGenerator = $generator;
		
		return $this;
	}


	/**
	 * a generic implementation that use uri, base and id property (all optionals)
	 */
	public function getUri()
	{
		if(!empty($this->data['uri'])){
			$uri =  $this->data['uri'];
		} elseif(!empty($this->data['base'])) {
			$idGenerator=$this->uniqueIdGenerator;
			$uri = $this->data['base'];
			$uri.=empty($this->data['id'])?$idGenerator($this->data):$this->data['id'];
		} else{
			$idGenerator=$this->uniqueIdGenerator;
			$uri = 'urn:local:botk:'.$idGenerator($this->data);
		}
		
		return $uri;
	}
		

	public function asArray()
	{
		return $this->data;
	}

	
	public function getOptions()
	{
		return $this->options;
	}


	public function getVocabularies()
	{
		return $this->vocabulary;
	}
	
	
	public function setVocabulary($prefix,$ns)
	{
		$this->vocabulary[$prefix] = $ns;
	}
	
	
	public function unsetVocabulary($prefix)
	{
		unset($this->vocabulary[$prefix]);
	}	
	
	
	public function getTurtleHeader($base=null)
	{
		$header = empty($base)?'': "@base <$base> .\n";
		foreach( $this->vocabulary as $prefix=>$ns ){
			$header.="@prefix $prefix: <$ns> .\n";
		}
		
		return $header;
	}

	
	public function getTripleCount()
	{
		// triple count is computed during rdf creation
		if (is_null($this->rdf)){
			$this->asTurtle();
		}
		
		return $this->tripleCount;
	}
	
		
	public function __toString() 
	{
		return $this->getTurtleHeader() ."\n". $this->asTurtle();
	}
}