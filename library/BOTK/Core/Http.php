<?php
namespace BOTK\Core;
/**
 * A set of helpers for managing http protocols
 */
 
class Http
 {
    public static $STATUS_CODES = array(100 => "Continue", 101 => "Switching Protocols", 102 => "Processing", 
        200 => "OK", 201 => "Created", 202 => "Accepted", 203 => "Non-Authoritative Information", 
        204 => "No Content", 205 => "Reset Content", 206 => "Partial Content", 207 => "Multi-Status", 
        300 => "Multiple Choices", 301 => "Moved Permanently", 302 => "Found", 303 => "See Other", 
        304 => "Not Modified", 305 => "Use Proxy", 306 => "(Unused)", 307 => "Temporary Redirect", 
        308 => "Permanent Redirect", 400 => "Bad Request", 401 => "Unauthorized", 
        402 => "Payment Required", 403 => "Forbidden", 404 => "Not Found", 
        405 => "Method Not Allowed", 406 => "Not Acceptable", 407 => "Proxy Authentication Required", 
        408 => "Request Timeout", 409 => "Conflict", 410 => "Gone", 411 => "Length Required", 
        412 => "Precondition Failed", 413 => "Request Entity Too Large", 414 => "Request-URI Too Long", 
        415 => "Unsupported Media Type", 416 => "Requested Range Not Satisfiable", 417 => "Expectation Failed",
        418 => "I'm a teapot", 419 => "Authentication Timeout", 420 => "Enhance Your Calm", 
        422 => "Unprocessable Entity", 423 => "Locked", 424 => "Failed Dependency", 424 => "Method Failure", 
        425 => "Unordered Collection", 426 => "Upgrade Required", 428 => "Precondition Required", 
        429 => "Too Many Requests", 431 => "Request Header Fields Too Large", 444 => "No Response", 
        449 => "Retry With", 450 => "Blocked by Windows Parental Controls", 
        451 => "Unavailable For Legal Reasons", 494 => "Request Header Too Large", 495 => "Cert Error", 
        496 => "No Cert", 497 => "HTTP to HTTPS", 499 => "Client Closed Request", 
        500 => "Internal Server Error", 501 => "Not Implemented", 502 => "Bad Gateway", 
        503 => "Service Unavailable", 504 => "Gateway Timeout", 505 => "HTTP Version Not Supported", 
        506 => "Variant Also Negotiates", 507 => "Insufficient Storage", 508 => "Loop Detected", 
        509 => "Bandwidth Limit Exceeded", 510 => "Not Extended", 511 => "Network Authentication Required", 
        598 => "Network read timeout error", 599 => "Network connect timeout error");
        
    /*
     * Here is a PHP 5.3.x implementation of http_response_code available in > PHP 5.4
     */
    public static function setHttpResponseCode($statusCode)
    {
        if (version_compare(phpversion(), '5.4', '<')) {         
            $text = ($statusCode && isset( $httpStatusCodes[$statusCode]))
                ? Http::$STATUS_CODES[$statusCode]
                : '';
            $protocol = (isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.1');
            $GLOBALS['http_response_code'] = $statusCode; // no other way to simulate http_response_code()
            header($protocol . ' ' . $statusCode . ' ' . $text);
        } else {
           http_response_code($statusCode);
        }
    } 

    
    public static function getHttpResponseCode()
    {
        if (version_compare(phpversion(), '5.4', '<')) {
            // Warning this works only if status was set using setHttpResponseCode
            $statusCode = isset($GLOBALS['http_response_code'])
                ? $GLOBALS['http_response_code']
                : 200;
        } else {
           $statusCode=http_response_code();
        }
        
        return $statusCode;
    } 
    
    /*************************************************************************
     * Parsing of response Header
     *************************************************************************/
    public static function getResponseHeader($name)
    {
        $sentHeaders = headers_list();
        foreach ($sentHeaders as $sentHeader) {
            if (preg_match("/$name:(.+)/i", $sentHeader,$matches)){
                return trim($matches[1]);
            }
        }
        return '';
    }

    public static function getETagResponse()
    {
        return preg_match('/"(.*)"/', self::getResponseHeader('ETag'),$matches)?trim($matches[1]):'';
    }


    public static function getResponseLinks()
    {
        $sentHeaders = headers_list();
        $result=array();
        foreach ($sentHeaders as $sentHeader) {
            if (strpos($sentHeader, 'Link:')===0){
                $l = new WebLink;
                $result[] =$l->httpParse($sentHeader);
            }
        }
        return $result;
    }
    
    
    /**
     * Override ETag with a new calculated value from $data
     */
    public static function setETagHeader($data,$etag=null)
    {
        // Recaluclate ETag overridin existent one Use  Weak validation
        if (is_null($etag)) {
            if (is_resource($data)){
                $etag = uniqid();
                //try http wrapper to get a better value,
                $meta = stream_get_meta_data($data);
                for ($j = 0; isset($meta['wrapper_data'][$j]); $j++){
                    if (strstr(strtolower($meta['wrapper_data'][$j]), 'etag')){
                       $etag = str_replace('"', '', trim(substr($meta['wrapper_data'][$j], 6)));
                       break;
                    }
                }
            } elseif (is_object($data) && method_exists($data, 'getEtag')){
                $etag = $data->getEtag();
            } else {
                $etag = md5(serialize($data));
            }
            
        }
        // take into account ETags alread sent:
        $oldETag=self::getETagResponse();
        if($oldETag){
            $etag= "\"$oldETag/$etag\"";
        } else {
            $etag= "\"$etag\"";            
        }
        header ('ETag: '.$etag);  // use Weak for semantically equivalent value
        return $etag;
    }

    /**
     * send Last-Modified header if not already set
     */
    public static function setLastModifiedHeader($data,\DateTime $defaultDate)
    {
        // Skypp if already sent header...
        if ( $lastModifiedOnHeaderVal=self::getResponseHeader('Last-Modified')) {
             return  $lastModifiedOn  = new \DateTime(trim($lastModifiedOnHeaderVal));
        } 

        //if $data is a resource try to get creation date from resource metadata
        if ( is_resource($data)){
            // try fstat first
            $fstat = @fstat($data);
            if (!empty($fstat)){
                $lastModifiedOn = new \DateTime(date('r',$fstat['mtime']));
            } else {
                //try http wrapper
                $meta = stream_get_meta_data($data);
                $lastModifiedOn = $defaultDate;
                
                for ($j = 0; isset($meta['wrapper_data'][$j]); $j++){
                    if (strstr(strtolower($meta['wrapper_data'][$j]), 'last-modified')){
                       $modtime = substr($meta['wrapper_data'][$j], 15);
                       $lastModifiedOn = new \DateTime($modtime);
                       break;
                    }
                }
            }
        } elseif (is_object($data) && method_exists($data, 'getLastModifiedDate')) {
            $lastModifiedOn = $data->getLastModifiedDate();
        } else {
            $lastModifiedOn  = $defaultDate;
        }
        header('Last-Modified: '.$lastModifiedOn->format(\DateTime::RFC2822));
        
        return $lastModifiedOn;
    }
 }
