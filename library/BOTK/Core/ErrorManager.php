<?php
namespace BOTK\Core;

use \ErrorException, \Exception;
use BOTK\Core\Models\HttpProblem;

/*
 * It is a singleton
 */
class ErrorManager extends Singleton
{        
    protected $contentPolicy = '\\BOTK\\Core\\Representations\\Error';
    protected $priority = 0;
    protected $errorHandler = null;


    public function setContentPolicy($contentPolicy)
    {
        if (!is_subclass_of($contentPolicy,'\\BOTK\\Core\\Representations\\Error')) {
            // do not use erromanagement inside erro management :-)
            die("$contentPolicy is not a valid Content Negotiation Policy");
        }
        $this->contentPolicy = $contentPolicy;
        
        return $this;
    }
    
    public function getContentPolicy()
    {
        return $this->contentPolicy;
    }


    public function setErrorPriority($priority)
    {
        $this->priority = $priority;
        return $this;
    }
    
    /**
     * Register or unregister an error handler
     */
    public function registerErrorHandler( $f = null)
    {
        if (is_null($this->errorHandler) && is_callable($f)){
            // Initialize
            $this->errorHandler = $f;
            $prioryty = $this->priority;
            set_error_handler($f);
        } elseif ($this->errorHandler && is_null($f)) {
            // Reset error handler
            restore_error_handler();
            $this->errorHandler = null;
        } elseif (is_null($this->errorHandler) &&  is_null($f)) {
            // Install default
            $priority = $this->priority;
            set_error_handler( 
                function ($errno, $errstr, $errfile, $errline) use ($priority) 
                {
                    throw new ErrorException($errstr, $errno, $priority, $errfile, $errline);
                }
            );
            $this->errorHandler = function() {};
        } elseif ($this->errorHandler && is_callable($f)) {
            // Override existing handler
            restore_error_handler();
            set_error_handler($f);
            $this->errorHandler = $f;
        }
        
        return $this;
    }
 
       
    public function registerViewer( array $viewer, $errorCode = 0)
    {
        $this->viewers[$errorCode] = $viewer;          
    }

 
    public function getErrorHandler()
    {
        return $this->errorHandler;
    }
    
    public function serializeHttpProblem(HttpProblem $problem)
    {
        $CNPClassName = $this->contentPolicy;
        return  $CNPClassName::render($problem,$CNPClassName::renderers());
    }
    
    public function render( Exception $e)
    {          
        // build error model from Exception
        if ($e instanceof HttpErrorException){
            $problem = $e->getHttpProblem();
        } else {
            // guess http status code
            $errorCode  = $e->getCode();
            $isHttpErrorCode = ($errorCode >=400 && array_key_exists($errorCode, Http::$STATUS_CODES));
            $statusCode = $isHttpErrorCode?$errorCode:500;

            $phpErrorURI='http://php.net/manual/errorfunc.constants';
            $problem = new HttpProblem(  // Populate a new HttpProblem model
                $statusCode,
                null, // use default title
                (string) $e,// developer description is string view for exception
                $isHttpErrorCode?null:($phpErrorURI.'#'.$errorCode), // guess problem instance
                $isHttpErrorCode?null:$phpErrorURI // guess problem type
              );
        }
         
       // Set header and rendering payload
        Http::setHttpResponseCode($problem->httpStatus);
        return  $this->serializeHttpProblem($problem);
    }
}
