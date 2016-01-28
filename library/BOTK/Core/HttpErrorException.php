<?php
namespace BOTK\Core;

use BOTK\Core\Models\HttpProblem,
    BOTK\Core\Representations\Error;

/**
 * HttpError Exception
 *
 * This Exception a superclass for 4xx and 5xx http errors
 *
 */
class HttpErrorException extends \Exception
{
    protected $httpProblem;

    public function __construct( HttpProblem $httpProblem = null, $previous=null )
    {
        if (is_null($httpProblem)) $httpProblem = new HttpProblem;    
        parent::__construct($httpProblem->title, $httpProblem->httpStatus, $previous);
        $this->httpProblem = $httpProblem; 
    }


    public static function factory(HttpProblem $httpProblem = null, $previous=null )
    {
        return new static($httpProblem, $previous );
    }


    final public function getHttpProblem()
    {
        return $this->httpProblem;
    }
    
    /**
     * Redefine to string
     */
    public function __toString()
    {
        $problem = $this->getHttpProblem();
        return "$problem->title($problem->httpStatus). $problem->detail";
    }
}
