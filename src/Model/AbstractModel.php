<?php
namespace BOTK\Model;

use BOTK\Exceptions\DataModelException;


/**
 * An ibrid class that merge the semantic of schema:organization, schema:place and schema:geo, 
 * it is similar to schema:LocalBusiness.
 * Allows the bulk setup of properties
 */
abstract class AbstractModel 
{

	static protected $DEFAULT_OPTIONS = array (
	);
	
	
	protected $data;
	protected $options;
	protected $rdf =null; //lazy created
	protected $tripleCount=0; //lazy created
	
	
    public function __construct(array $data = array(), array $options = array()) 
    {
    	// ensure  proper defaults exists
    	$this->options = array_merge(static::$DEFAULT_OPTIONS, $options);

		// set default values
		foreach( $this->options as $property=>$option){	
			if(empty($data[$property]) && isset($this->options[$property]['default'])){
				$data[$property] = $this->options[$property]['default'];
			}
		}

		// ensure data are sanitized and validated
		$sanitizedData = array();
		foreach( $data as $property=>$value) {
			
			// property must exist
			if(!isset($this->options[$property])){
				throw new DataModelException("Unknown property $property");
			}
			
			// apply filters to not empty variables
			if( $value  ){
				if(isset($this->options[$property]['filter'])){
					$sanitizedValue = filter_var($value, $this->options[$property]['filter'], $this->options[$property]);
					if(!$sanitizedValue){
						throw new DataModelException("Invalid property value for $property ($value)");
					}
					$sanitizedData[$property]=$sanitizedValue;					
				} else {
					$sanitizedData[$property]=$value;
				}
			}
		}
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
		return $this->asTurtle();
	}
}