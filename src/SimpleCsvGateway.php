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
		    'silent'            => false,
		);
		$this->options = array_merge($defaults,$options);
		$this->factsFactory = new \BOTK\FactsFactory($options['factsProfile']);
	}
	
	
	protected function readRawData()
	{
		$this->currentRow++;
		return fgetcsv(STDIN, $this->options['bufferSize'], $this->options['fieldDelimiter']);
	}
	
	protected function message($message)
	{
	    if (!$this->options['silent']){
	        echo $message;
	    }
	}
	
	
	public function run()
	{
	    while ($rawdata = $this->readRawData()) {
	        if($this->currentRow==1) {
	            echo $this->factsFactory->generateLinkedDataHeader();
	            if ( $this->options['skippFirstLine']){
    	    	    $this->message ("# Header skipped\n");
    	    		continue;
	            }
			}
    		try {
    			$facts =$this->factsFactory->factualize($rawdata);
    			// on first line write headers
    			echo $facts->asTurtleFragment() , "\n";
				$droppedFields = $facts->getDroppedFields();
		    	if(!empty($droppedFields) && $this->options['missingFactsIsError']) {
		    	    $this->message ("\n# WARNING MISSING FACT on row {$this->currentRow}: dropped ".implode(",", $droppedFields)."\n");
					$this->factsFactory->addToCounter('error');
				}	    
			} catch (\BOTK\Exception\Warning $e) {
			    $this->message ("\n# WARNING on row {$this->currentRow}: ".$e->getMessage()."\n");
			} 
	    }
		
		echo $this->factsFactory->generateLinkedDataFooter();		
	}
}
