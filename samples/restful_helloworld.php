<?php
/**
 * see sample doc in index.html
 */
require '../vendor/autoload.php';

use BOTK\Core\EndpointFactory,          // Create end-point
    BOTK\Core\EndPoint,                 // provides the web service runtime
    BOTK\Core\Controller,               // controls http protocol
    BOTK\Core\ErrorManager,             // Controls errors
    BOTK\Core\Representations\Standard, // provides many resource representations
    BOTK\Core\WebLink,                  // add hypermedia capability
    BOTK\Core\Caching;                  // manage HTTP caching

/* This class implements MVC Model */
class Greeting
{
    public $greeting = 'Hello', $to = '', $by = 'http://www.e-artspace.com/';  
    public function __construct($to='World') { $this->to=$to;}
    public function __toString() {return "$this->greeting $this->to";}
}

/* This class implements MVC View */
class GreetingRepresentation extends Standard {}

/* This class implements MVC Controller for Resource*/
class HelloworldController extends Controller
{
    public function get($to='World')
    {
        return $this->stateTransfer(
            $hello = new Greeting($to),
            WebLink::factory($hello->by)->rel('next')
        );
    }
}

/* This class implements MVC Controller for End-Point*/
class Helloworld extends EndPoint
{
    protected function setRoutes()
    {
        $this->get('/*', new HelloworldController)
            ->accept(GreetingRepresentation::renderers())
            ->through($this->representationCachingProcessor(Caching::SHORT));
    }
}

//uncomment above to use your css:
//Standard::$htmlMetadata = 'http://www.w3.org/StyleSheets/Core/parser.css?family=6&doc=XML';
try {                                                      
    echo EndPointFactory::make('Helloworld')->run();
} catch ( Exception $e) {
    echo ErrorManager::getInstance()->render($e); 
}

