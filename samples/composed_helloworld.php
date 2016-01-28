<?php
/**
 * see sample doc in index.html
 */
$loader = require '../vendor/autoload.php'; 
$loader->add('mylibrary\\', __DIR__);// local end-points inclusion by composer autoloder

define ('_BOTK', true);                                      // extra security in includes

use BOTK\Core\EndPointFactory;
use BOTK\Core\EndPoint;
use BOTK\Core\ErrorManager;

class Helloworld extends EndPoint {
    protected function setRoutes() {$this->get('/', 'Try /hi and /hello resources.');}
}


$errorManager = ErrorManager::getInstance()->registerErrorHandler();    
try {                                                      
    echo EndPointFactory::make('Helloworld')
        ->mountEndPoint('hi',    '\\mylibrary\\SimpleHelloEndPoint')
        ->mountEndPoint('hello', '\\mylibrary\\HelloEndPoint\\Router')
        ->run();
} catch ( Exception $e) {
    echo $errorManager->render($e); 
}
