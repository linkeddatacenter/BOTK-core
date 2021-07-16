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
	protected $storage = null;
	
	
	public static function factory(array $options)
	{
		return new static($options);
	}
	
	
	public function __construct(array $options, object $storage = null)
	{
		$defaults = array(
			'factsProfile'		=> array(),
			'missingFactsIsError' => true, // if a missing fact must considered an error
			'bufferSize' 		=> 2000,
			'skippFirstLine'	=> true,
			'fieldDelimiter' 	=> ',',
		    'silent'            => false,
		    'inputStream'      => STDIN,
		    'outputStream'     => STDOUT,
		);
		$this->options = array_merge($defaults,$options);
		$this->factsFactory = new \BOTK\FactsFactory($options['factsProfile']);
		$this->storage= is_null($storage)?(new \stdClass):$storage;
	}
	
	
	protected function readRawData()
	{
		$this->currentRow++;
		return fgetcsv($this->options['inputStream'], $this->options['bufferSize'], $this->options['fieldDelimiter']);
	}
	
	protected function message($message)
	{
	    if (!$this->options['silent']){
	        fputs($this->options['outputStream'],  $message);
	    }
	}

	public function getStorage()
	{
	    return $this->storage;
	}
	
	
	public function setStorage($data=[])
	{
	    $this->storage = $data;
	    return $this;
	}
	
	public function run()
	{
	    while ($rawdata = $this->readRawData()) {
	        if($this->currentRow==1) {
	            fputs($this->options['outputStream'], $this->factsFactory->generateLinkedDataHeader());
	            if ( $this->options['skippFirstLine']){
    	    	    $this->message ("# Header skipped\n");
    	    		continue;
	            }
			}
    		try {
    		    $facts =$this->factsFactory->factualize($rawdata, $this->storage );
    			// on first line write headers
    			fputs($this->options['outputStream'], $facts->asTurtleFragment() );
    			fputs($this->options['outputStream'], "\n");
				$droppedFields = $facts->getDroppedFields();
		    	if(!empty($droppedFields) && $this->options['missingFactsIsError']) {
		    	    $this->message ("\n# WARNING MISSING FACT on row {$this->currentRow}: dropped ".implode(",", $droppedFields)."\n");
					$this->factsFactory->addToCounter('error');
				}	    
			} catch (\BOTK\Exception\Warning $e) {
			    $this->message ("\n# WARNING on row {$this->currentRow}: ".$e->getMessage()."\n");
			} 
	    }
		
	    fputs( $this->options['outputStream'], $this->factsFactory->generateLinkedDataFooter());
	}
}
