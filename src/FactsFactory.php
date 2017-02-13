<?php
namespace BOTK;

class FactsFactory implements FactsFactoryInterface {
	
	protected $profile;
	protected $tripleCount =0;
	protected $errors = array();
	protected $errorCount=0;
	protected $unacceptableCount=0;
	protected $entityCount=0;
	
	
	public function __construct( array $profile )
	{
		assert(!empty($profile['model']) && class_exists('\BOTK\Model\\'.$profile['model']));
		assert(isset($profile['options']) && is_array($profile['options']));
		$defaults = array(
			'landingPage' 			  => 'php://stdin',
			'resilience' 			  => 0.3,
			'datamapper'			  => function($rawdata){return array();},
			'rawDataValidationFilter' => function( $rawdata){return is_array($rawdata);},	
		);
		$this->profile = array_merge($defaults,$profile);
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
		
		$facts = new $class($data,$this->profile['options']);
		if($facts){
			$this->entityCount++;
			$this->tripleCount+=$facts->getTripleCount();
		}
		return $facts;
	}
	
	
	public function generateLinkedDataHeader()
	{
		$class = '\BOTK\Model\\'.$this->profile['model'];
		$model = new $class();
		return $model->getTurtleHeader(); 
	}
	
	
	public function generateLinkedDataFooter()
	{	
		$now = date('c');
		if( $this->tooManyErrors()) {
			$this->tripleCount += 6;
			return <<<EOT
<> foaf:primaryTopic [
	a void:Dataset;
	prov:wasDerivedFrom <{$this->options['landingPage']}>;
	prov:invalidatedAtTime "$now"^^xsd:dateTime;
	void:triples {$this->tripleCount} ;
	void:entities {$this->entityCount} ;
] .

#
# WARNING: INVALID DATASET BECAUSE TOO MANY ERRORS
# {$this->tripleCount} good triples from  {$this->entityCount} entities ({$this->unacceptableCount} ignored), {$this->errorCount} errors"
#

EOT;
		}else {
			$this->tripleCount += 6;
			return <<<EOT
<> foaf:primaryTopic [
	a void:Dataset;
	prov:wasDerivedFrom <{$this->profile['landingPage']}>;
	prov:generatedAtTime "$now"^^xsd:dateTime;
	void:triples {$this->tripleCount} ;
	void:entities {$this->entityCount} ;
] .

#
# Generated {$this->tripleCount} good triples from  {$this->entityCount} entities ({$this->unacceptableCount} ignored), {$this->errorCount} errors"
#

EOT;
		}
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
		
		return ($this->errorCount/$this->entityCount) > $this->profile['reslience'];
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