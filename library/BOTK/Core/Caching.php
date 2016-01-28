<?php
namespace BOTK\Core;

use BOTK\Models\HttpProblem;

class Caching
{
    const
        NO              = 'noCachingControl',
        DOS_PROTECTION  = 'dosProtectionCachingControl',
        SHORT           = 'shortCachingControlControl',
        CONSERVATIVE    = 'conservativeCachingControl',
        AGGRESSIVE      = 'aggressiveCachingControl',
        VERY_AGGRESSIVE = 'veryAggressiveCachingControl';


    public static function noCachingControl() //3 sec
    {
        return function ($data) { return Caching::processor($data);};
    } 
    
         
    public static function dosProtectionCachingControl() //3 sec
    {
        return function ($data) { return Caching::processor($data, 3);};
    }              
 
    public static function shortCachingControl() //30 sec
    {
        return function ($data) { return Caching::processor($data,30);};
    }
 
    public static function conservativeCachingControl() //three minute
    {
        return function ($data) { return Caching::processor($data, 60*3);};
    }
    
    public static function aggressiveCachingControl() //one hour
    {
        return function ($data) { return Caching::processor($data, 60*60);};
    }
    
    public static function veryAggressiveCachingControl() //one day
    {
        return function ($data) { return Caching::processor($data, 60*60*24);};
    }

    public static function setCacheControl($maxAge=0)
    {
        $cacheControl= (($sec=intval($maxAge))>0)?"max-age=$sec":'';
        if ($cacheControl) header ('Cache-Control: '.$cacheControl);
        
        return $cacheControl;
    }
    
    /**
     * This routine drive http caching process
     * Not sure to have well understand caching :-(
     * 
     * Note that this routine is called by stateTransfer and may be called also from
     * End-Point routine after the controller exit.
     *  
     * @link http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html
     */   
    public static function processor($data, $sec=0)
    {
        // ensure not negative caching age!
        if ($sec<0) $sec=0;
 
        $etag = Http::setETagHeader($data);     
        $lastModifiedOn = Http::setLastModifiedHeader($data, new \DateTime());
  
        // this is the caching algorithm: only return data if necessary..
        if( self::isNotModified($etag,  $lastModifiedOn) ){
            // Return http status 304 Not Modified and stop data flow processing
            Http::setHttpResponseCode(304);
            return ''; // return empty data
        } 
        
        // advertise caching policy
        self::setCacheControl($sec);

        return $data;
    }
 
     
    /**
     * Compare Etag against HTTP_IF_NONE_MATCH
     * 
     * @return false in no match found, 
     */
    private static function anyETagMatched($myTag)
    {
        if (!$myTag) return false;
        $myTag=str_replace('"', '', $myTag); //remove quoting
        
        $if_none_match = isset($_SERVER['HTTP_IF_NONE_MATCH']) ?
            str_replace('"', '', stripslashes($_SERVER['HTTP_IF_NONE_MATCH'])) : 
            false ;
    
        // warning, this assumes that , is never part of ETag!
        if( false !== $if_none_match ) {
            $tags = explode( ", ", $if_none_match ) ;
            foreach( $tags as $tag ) {
                if( strpos($tag,$myTag)===0  ) return true ; // partial tag match
            }
            return false ;
        }
        
        return null ;
    }
    
    
    /**
     * Compare HTTP_IF_MODIFIED_SINCE with a date (date tyme format)
     */
    private static function isExpired($lastModifiedOn) 
    {
        $if_modified_since = isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) 
            ? new \DateTime($_SERVER['HTTP_IF_MODIFIED_SINCE'])
            : false;
    
        if( false !== $if_modified_since ) {
            return ( $if_modified_since < $lastModifiedOn ) ;
        }
    
        return true ;
    }    
    
    
    /**
     * Apply cache controls algorithm respect protocol requirement.
     */
    private static function isNotModified($ETag=null, \DateTime $lastModifiedOn)
    {
        $anyTagMatched = self::anyETagMatched($ETag) ;
        
        return ( $anyTagMatched || ( ( is_null($anyTagMatched) && !self::isExpired($lastModifiedOn) ) ));
    }    
}
