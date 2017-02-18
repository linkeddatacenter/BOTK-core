<?php
namespace BOTK\Exception;

class InvalidRawDataWarning extends Warning
{
	protected  $data;
	
	// Redefine the exception adding an extra paramether
    public function __construct($message,  $data, $code = 0, Exception $previous = null) {
        $this->data = $data;
    
        // make sure everything is assigned properly
        parent::__construct($message, $code, $previous);
    }


    public function getData() {
        return $this->data;
    }
}
