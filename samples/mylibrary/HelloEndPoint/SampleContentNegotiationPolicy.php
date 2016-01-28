<?php
namespace mylibrary\HelloEndPoint;

use BOTK\Core\Representations\Standard;

/**
 * Supports content negotiation policy and define a custom html renderer
 * 
 * Sample Representation redefine default rendering to place html as the 
 * first choice when an accept header is not available.
 */
final class SampleContentNegotiationPolicy extends Standard
{
    /**
     * redefine the rendering priority in content negotion
     * putting html and plain text firt. .
     */
    protected static $renderers_filter =  array(
        'text/html',
        'text/plain',      
        'application/json',
        'application/xml' , 
        'application/x-php',
        'text/x-php',
    );
    
    /**
     * Customize html css using botk one...
     */
    public static $htmlMetadata = array(
        '<title>Hello world</title>',
        '<link rel="stylesheet" type="text/css" href="http://ontology.it/tools/bofw/v4/css/doc.css" />'
    );
     
}
