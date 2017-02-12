<?php
namespace BOTK;

class FactsFactory implements FactsFactoryInterface {
	
	protected $profile;
	protected $tripleCount =0;
	protected $errors = array();
	
	
	public function __construct( array $profile )
	{
		assert(!empty($profile['model']) && class_exists('\BOTK\Model\\'.$profile['model']));
		assert(isset($profile['datamapper']) && is_callable($profile['datamapper']));
		assert(isset($profile['options']) && is_array($profile['options']));
		$this->profile = $profile;
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
	
	
	public function factualize( array $rawData )
	{
		$datamapper = $this->profile['datamapper'];
		$class = '\BOTK\Model\\'.$this->profile['model'];
		$data =$this->removeEmpty($datamapper($rawData));
		return new $class($data,$this->profile['options']);
	}
	
	
	public function generateLinkedDataHeader()
	{
		$class = '\BOTK\Model\\'.$this->profile['model'];
		$model = new $class();
		return $model->getTurtleHeader(); 
	}
	
	
	public function generateLinkedDataFooter()
	{
		$errorCount = count($this->errors);
		return "\n#\n# Generated {$this->tripleCount} triples and $errorCount errors\n#\n"; 
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
		return $this;
	}

}