<?php
namespace mylibrary\HelloEndPoint;


/*
 * This end-point defines some routes involving three different controllers:
 *      one is to manage about resource
 *      one is to manage some special resource (for and to)
 *      and one to manage other resource.
 * 
 * managed resources:
 * /about :demonstrate access to a model
 * /about/version : demonstrate access to a model attribute
 * /about?process=sum : demonstrate access to a model method
 * /<name> : demonstrate a controller that use a paramether
 * /to/<name1>/and/<name2>  : ex. /to/Enrico/and/Paola demonstrate a controller with two params
 * 
 * 
 * All routes support content negotiation using a custom render that specialize
 * the default one.
 */  

use BOTK\Core\EndPoint;

final class Router extends EndPoint
{
    protected function setRoutes()
    { 
        $this->get('/about/*', new Controllers\AboutController);
        $this->any('/*', new Controllers\WorldController);
        $this->get('/to/*/and/*', new Controllers\SpecialController);
        
        // All routes accept custom content Negotiation
        $this->always('accept', SampleContentNegotiationPolicy::renderers());
    }
}
