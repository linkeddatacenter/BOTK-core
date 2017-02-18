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
		return new static($options);
	}
	
	
	public function __construct(array $options)
	{
		$defaults = array(
			'factsProfile'		=> array(),
			'missingFactsIsError' => true, // if a missing fact must considered an error
			'bufferSize' 		=> 2000,
			'skippFirstLine'	=> true,
			'fieldDelimiter' 	=> ',',
		);
		$this->options = array_merge($defaults,$options);
		$this->factsFactory = new \BOTK\FactsFactory($options['factsProfile']);
	}
	
	
	protected function readRawData()
	{
		$this->currentRow++;
		return fgetcsv(STDIN, $this->options['bufferSize'], $this->options['fieldDelimiter']);
	}
	
	
	public function run()
	{
		echo $this->factsFactory->generateLinkedDataHeader();
	
	    while ($rawdata = $this->readRawData()) {
	    	if($this->currentRow==1 && $this->options['skippFirstLine']){
	    		echo "# Header skipped\n";
	    		continue;
			}
    		try {
    			$facts =$this->factsFactory->factualize($rawdata);
	    		echo $facts->asTurtleFragment(), "\n";
				$droppedFields = $facts->getDroppedFields();
		    	if(!empty($droppedFields) && $this->options['missingFactsIsError']) {
				    echo "\n# WARNING MISSING FACT on row {$this->currentRow}: dropped ".implode(",", $droppedFields)."\n";
					$this->factsFactory->addToCounter('error');
				}	    
			} catch (\BOTK\Exception\Warning $e) {
				echo "\n# WARNING on row {$this->currentRow}: ".$e->getMessage()."\n";
			} 
	    }
		
		echo $this->factsFactory->generateLinkedDataFooter();		
	}
}
