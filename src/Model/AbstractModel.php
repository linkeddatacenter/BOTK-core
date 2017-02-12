<?php
namespace BOTK\Model;

use BOTK\Exceptions\DataModelException;


abstract class AbstractModel 
{
	
	/**
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
	static protected $DEFAULT_OPTIONS = array();
	
	protected $data;
	protected $options;
	protected $rdf =null; //lazy created
	protected $tripleCount=0; //lazy created
	protected $vocabulary = array(
		'botk' 		=> 'http://http://linkeddata.center/botk/v1#',
		'schema'	=> 'http://schema.org/',
		'wgs' 		=> 'http://www.w3.org/2003/01/geo/wgs84_pos#',
		'xsd' 		=> 'http://www.w3.org/2001/XMLSchema#',
		'dct' 		=> 'http://purl.org/dc/terms/',
		'foaf' 		=> 'http://xmlns.com/foaf/0.1/',
	);
	
	
    public function __construct(array $data = array(), array $customOptions = array()) 
    {
    	// merge options with default options or create new ones
    	$options = static::$DEFAULT_OPTIONS;
    	foreach($customOptions as $property=>$option){
    		assert(is_array($option));
    		if(isset($options[$property])){
    			$options[$property] = array_merge($options[$property], $option);
    		} else {
    			$options[$property] = $option;
    		}
    	}
		
		// set default values
		foreach( $options as $property=>$option){	
			if(empty($data[$property]) && isset($options[$property]['default'])){
				$data[$property] = $options[$property]['default'];
			}
		}

		// ensure data are sanitized and validated
		$sanitizedData = array_filter( filter_var_array($data, $options), function($value,$property) use($data,$options){
			if ($value===false && isset($data[$property]) && $data[$property]!==false){
				throw new DataModelException("Invalid property value for $property");
			}
			return !is_null($value);
		} , ARRAY_FILTER_USE_BOTH);

		$this->options = $options;
		$this->data = $sanitizedData;
    }

	
	public function asArray()
	{
		return $this->data;
	}

	
	public function getOptions()
	{
		return $this->options;
	}


	public function getVocabulary()
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

	
	abstract public function asTurtle();

	
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