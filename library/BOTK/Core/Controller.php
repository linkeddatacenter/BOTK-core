<?php
namespace BOTK\Core;

use Respect\Rest\Routable;

abstract class Controller implements Routable
{
    public static function factory()
    {
        return new static();
    }


    public function head() 
    {
        if (method_exists($this, 'get')) {
            call_user_func_array(array($this, 'get'), func_get_args());
        } else {
            Http::setHttpResponseCode(405);
        }
        return'';
    }

  
    public function options() 
    {
        $supportedOptions[]='OPTIONS';
        if( method_exists($this, 'get')) { $supportedOptions[]='GET'; $supportedOptions[]='HEAD' ;}
        if( method_exists($this, 'post')) $supportedOptions[]='POST';
        if( method_exists($this, 'put')) $supportedOptions[]='PUT';
        if( method_exists($this, 'delete')) $supportedOptions[]='DELETE';
        if( method_exists($this, 'patch')) $supportedOptions[]='PATCH';
        if( method_exists($this, 'trace')) $supportedOptions[]='TRACE';
        if( method_exists($this, 'connect')) $supportedOptions[]='CONNECT';
        
        header ( 'Allow: '. implode(',', $supportedOptions));

        return'';   
    }

   
    /**
     * @param mixed $links can be a weblink, an array of or a single or null to bypas hypermedia management
     * @param mixed cacheFor  can be an integer that represents the max-age of cache or null to bypass caching
     */
    public function stateTransfer($resource=null,  $links=array())
    {
        // send links header: manage both a single link both an array
        if( is_array($links)){
            foreach ($links as $link){
                header($link->httpSerializer(),false);
            }
        } elseif( $links){
            header($links->httpSerializer());
        }           
        
        return $resource;
    }
    
    public function setState($resource=null,  $restorers=array())
    {
        // TBD
        return $resource;
    }

    
    public function resourceCachingProcessor($resouce, $sec=0)
    {
        return Caching::processor($resouce, $sec);
    }
    
}
