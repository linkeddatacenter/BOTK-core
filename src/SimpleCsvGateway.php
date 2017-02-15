<?php
namespace BOTK;

/**
 * just a simple example class to integrate FactsFactory and a Data Model
 */
class SimpleCsvGateway 
{
	
	protected $options;
	protected $factsFactory;
	protected $currentRow = 0;
	
	
	public static function factory(array $options)
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
		
		$this->currentRow++;
		return fgetcsv(STDIN, $this->options['bufferSize'], $this->options['fieldDelimiter']);
	}
	
	
	public function run()
	{
		echo $this->factsFactory->generateLinkedDataHeader();
		if($this->options['skippFirstLine']){ $this->readRawData();}
	
	    while ($rawdata = $this->readRawData()) {
	    	if($this->factsFactory->acceptable($rawdata)){
	    		$facts =$this->factsFactory->factualize($rawdata);
	    		echo $facts->asTurtle(), "\n";
				$droppedFields = $facts->getDroppedFields();
		    	if(!empty($droppedFields)) {
		    		$msg = "on row {$this->currentRow} dropped ".implode(",", $droppedFields);
					$this->factsFactory->addError($msg);
				    echo "\n# WARNING: $msg\n";
				}	    		
	    	} else {
	    		echo "\n# WARNING: row {$this->currentRow} ignored.\n";
	    	}
	    }
		
		echo $this->factsFactory->generateLinkedDataFooter();		
	}
}
