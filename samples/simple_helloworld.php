<?php
/**
 * see sample doc in index.html
 */
require '../vendor/autoload.php';
use BOTK\Core\EndPoint, BOTK\Core\EndPointFactory, BOTK\Core\ErrorManager,
    BOTK\Core\Representations\Standard;

class MyEndPoint extends EndPoint {
    protected function setRoutes() { 
        $this->get('/', 'Hello world')->accept(Standard::renderers()); 
    }
}

try {                                                      
    echo EndPointFactory::make('MyEndPoint')->run();
} catch ( Exception $e) {
    echo ErrorManager::getInstance()->render($e); 
}