<?php
namespace BOTK\Core\Models;

use BOTK\Core\Http;

/**
 * This class represents the http error data model that should always
 * returned as the payload of a 4xx or 5xx http response (rendered in
 * a appropriate representation)
 * 
 * sholud be serialized in json as described in following:
 * 
 * @link http://tools.ietf.org/html/draft-nottingham-http-problem-04
 */
class HttpProblem
{
    public $problemType;
    public $title;
    public $detail;
    public $problemInstance;    
    public $httpStatus;

    public function __construct($httpStatus=null, $title=null, $detail=null, $problemInstance=null, $problemType= null)
    {
        // sanitize values
        if ($httpStatus<400 || $httpStatus> 599) $httpStatus=500;
        
        // set defaults
        if ( is_null($problemType))       $problemType = 'http://dbpedia.org/resource/Category:HTTP_status_codes';
        if ( is_null($httpStatus))        $httpStatus = 500;
        if ( is_null($title))             $title = isset(Http::$STATUS_CODES[$httpStatus])?Http::$STATUS_CODES[$httpStatus]:'Unknown server error';
        if ( is_null($detail))            $detail = '';
        if ( is_null($problemInstance))   $problemInstance = 'http://dbpedia.org/resource/HTTP_'.$httpStatus;

        $this->httpStatus = $httpStatus;
        $this->title = $title;
        $this->problemInstance = $problemInstance;
        $this->detail = $detail;
        $this->problemType = $problemType;
    }
    
    public static function factory($httpStatus=null, $title=null, $detail=null, $problemInstance=null, $problemType= null)
    {
        return new static($httpStatus, $title, $detail, $problemInstance, $problemType);
    }
}
