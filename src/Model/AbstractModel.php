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
        'base'				=> array(
            'default'	=> 'urn:resource:',
            'filter'    => FILTER_CALLBACK,
            'options' 	=> '\BOTK\Filters::FILTER_VALIDATE_URI',
            'flags'  	=> FILTER_REQUIRE_SCALAR,
        ),
    );
    
    
	/**
	 * known vocabularies
	 */
	protected static $VOCABULARY  = array(
		'rdf'		=> 'http://www.w3.org/1999/02/22-rdf-syntax-ns#',
	    'xsd' 		=> 'http://www.w3.org/2001/XMLSchema#',
	);
	

	protected $options ;
	
	protected $data;
	protected $rdf =null; //lazy created
	protected $tripleCount=0; //lazy created
	protected $uniqueIdGenerator=null; // dependency injections
	protected $droppedFields = array();
	

	protected static function mergeOptions( array $options1, array $options2 )
	{
    	foreach($options2 as $property=>$option){
			
			$options1[$property]=isset($options1[$property])
				?array_merge($options1[$property], $option)
				:$option;
    	}
		
		return $options1;
	}


	protected static function constructOptions()
	{
		//http://stackoverflow.com/questions/22377022/using-array-merge-to-initialize-static-class-variable-in-derived-class-based-on
		$thisClass = get_called_class();
		$parentClass = get_parent_class($thisClass);
		$exists = method_exists($parentClass, __FUNCTION__); 
		return $exists ? 
			self::mergeOptions($parentClass::constructOptions(), $thisClass::$DEFAULT_OPTIONS) : 
			$thisClass::$DEFAULT_OPTIONS;		
	}


	/**
	 * Do not call directlty constructor, use fromArray or other factory methodsinstead
	 */
    protected function __construct(array $data = array(), array $customOptions = array()) 
    { 		
 		$options = self::mergeOptions(self::constructOptions(),$customOptions);
		
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
	
	
	
	/**
	 * Create an instance from an associative array
	 */
	public static function fromArray(array $data, array $customOptions = array())
	{
		return new static($data,$customOptions);
	}
	
	
	/**
	 * Create an instance from an generic standard object
	 */
	public static function fromStdObject( \stdClass $obj, array $customOptions = array())
	{
		return static::fromArray((array)$obj);
	}


	public static function getVocabularies()
	{
		//http://stackoverflow.com/questions/22377022/using-array-merge-to-initialize-static-class-variable-in-derived-class-based-on
		$thisClass = get_called_class();
		$parentClass = get_parent_class($thisClass);
		$exists = method_exists($parentClass, __FUNCTION__); 
		return $exists ? 
			array_merge($parentClass::getVocabularies(), $thisClass::$VOCABULARY) : 
			$thisClass::$VOCABULARY;
	}

	
	public static function getTurtleHeader($base=null)
	{
		$vocabulariers = static::getVocabularies();
		$header = empty($base)?'': "@base <$base> .\n";
		foreach( $vocabulariers as $prefix=>$ns ){
			$header.="@prefix $prefix: <$ns> .\n";
		}
		
		return $header;
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
	 * returns an uri
	 */
	public function getUri($id=null)
	{
	    assert(!empty($this->data['base'])) ;
	    
	    if (empty($id)) {
	        $idGenerator=$this->uniqueIdGenerator;
	        $id=$idGenerator($this->data);
	    }

		return $this->data['base'] . $id;
	}

	
	public function getOptions()
	{
		return $this->options;
	}


	public function getTripleCount()
	{
		// triple count is computed during rdf creation
		if (!empty($this->data) && is_null($this->rdf)){
			$this->asTurtleFragment();
		}
		
		return $this->tripleCount;
	}
		

	public function asArray()
	{
		return $this->data;
	}	



	public function asStdObject()
	{
		return (object) $this->asArray();
	}
	
	
	/**
	 * metadata not yet implemented
	 */		
	public function asLinkedData() 
	{
		return $this->getTurtleHeader() ."\n". $this->asTurtleFragment();
	}
	
	
	public function asString() 
	{
		return $this->asLinkedData();
	}
	
		
	public function __toString() 
	{
		return $this->asString();
	}


	/**
	 * adds a turtle fragment managing cardinality > 1
	 */
	protected function addFragment($format, $var, $sanitize=true){
		foreach((array)$var as $v){
			if($var){
				$this->rdf .= sprintf($format, $sanitize?\BOTK\Filters::FILTER_SANITIZE_TURTLE_STRING($v):$v);
				$this->tripleCount++;
			}
		}
	}

	/**
	 * this must be implemented
	 */
	abstract public function asTurtleFragment();
}