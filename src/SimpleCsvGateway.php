<?php
namespace BOTK;

/**
 * just a simple example class to integrate FactsFactory and a Data Model
 */
class SimpleCsvGateway 
{
	
	protected $options;
	protected $factsFactory;
	
	
	static public function factory(array $options)
	{
		return new SimpleCsvGateway($options);
	}
	
	
	public function __construct(array $options)
	{
		assert(!empty($options['factsProfile']['model']) && isset($options['factsProfile']['options']) && is_array($options['factsProfile']['options'])) ;
		$defaults = array(
			'bufferSize' 		=> 2000,
			'skippFirstLine'	=> true,
			'fieldDelimiter' 	=> ','
		);
		$this->options = array_merge($defaults,$options);
		$this->factsFactory = new \BOTK\FactsFactory($options['factsProfile']);
	}
	
	
	protected function readRawData()
	{
		if( $this->factsFactory->tooManyErrors()) return false;
		
		return fgetcsv(STDIN, $this->options['bufferSize'], $this->options['fieldDelimiter']);
	}
	
	
	public function run()
	{
		echo $this->factsFactory->generateLinkedDataHeader();
		if($this->options['skippFirstLine']){ $this->readRawData();}
	
	    while ($rawdata = $this->readRawData()) {
	    	if($this->factsFactory->acceptable($rawdata)){
		    	try{
		    		$facts =$this->factsFactory->factualize($rawdata);
		    		echo $facts->asTurtle(), "\n";
		    	}catch (Exception $e) {
					$this->factsFactory->addError($e->getMessage());
				    echo "\n# Caught exception: " ,  $e->getMessage(), "\n";
				}	    		
	    	}
	    }
		
		echo $this->factsFactory->generateLinkedDataFooter();		
	}
}
