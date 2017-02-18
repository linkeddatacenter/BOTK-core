<?php
namespace BOTK\Exception;

class FactsValidatorWarning extends Warning
{
	protected  $data;
	
	// Redefine the exception adding an extra paramether
    public function __construct($message, \BOTK\ModelInterface $data, $code = 0, Exception $previous = null) {
        $this->data = $data;
    
        // make sure everything is assigned properly
        parent::__construct($message, $code, $previous);
    }


    public function getFacts() {
        return $this->data;
    }
}
