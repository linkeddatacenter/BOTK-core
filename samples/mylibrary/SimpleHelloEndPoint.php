<?php
namespace mylibrary;

use BOTK\Core\EndPoint;

/**
 * EndPoint class extends Respect\Rest\Controller. 
 * See documentation at https://github.com/Respect/Rest
 * 
 * managed resources:
 *  GET /
 *      it will print on browser 'Hello to everybody'
 * 
 *  GET /<name>
 *      it will print on browser 'Hello to <name>' 
 * 
 *  GET /botk
 *      it will print on browser 'Thank you!!!'
 *
 *  POST /botk
 *      it will print on browser 'Thank you posting me!!!'
 * 
 * No content negotiation support. 
 * No error management installed. 
 */
final class SimpleHelloEndPoint extends EndPoint
{
    /**
     * Order matters!
     * declare routes from the most specific to the most generic.
     */
    protected function setRoutes()
    {
        // No content negotiation. Direct routing throw closures

        $this->get('/botk', function () {
             return 'Thank you!!!'; 
        });
    
        $this->post('/botk', function () {
             return 'Thank you posting me!!!'; 
        });
        
        $this->get('/*', function ($id = 'everybody') {
            return "Hello to $id. Sorry I do not support content management, just text...but you can post me ;-)";
        });
    }
}
