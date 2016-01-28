<?php

use BOTK\Core\EndPointFactory,
    BOTK\Core\EndPoint,
    BOTK\Core\HttpErrorException;

/**
 * @covers BOTK\Core\Application
 * @covers BOTK\Core\EndPoint
 */
class ApplicationTest extends PHPUnit_Framework_TestCase
{

    public function setUp()
    {
        $_SERVER['SCRIPT_NAME'] = $_SERVER['REQUEST_URI'] = $_SERVER['PATH_INFO'] = '';  
        $_SERVER['REQUEST_METHOD'] = 'GET';
    }

    /**
     */
    public function testEndPointWithDirectScriptRouting()
    {
        $_SERVER['SCRIPT_NAME'] = '/botk/core/samples/helloworld.php';
        $_SERVER['REQUEST_URI'] = '/botk/core/samples/helloworld.php/hello?thisisavar=value';

       $result=EndPointFactory::make()
                    ->mountEndPoint('hello', 'EndPointStub')
                    ->run();
                    
        $this->assertEquals($result, 'root');
    }
    
    
    /**
     */
    public function testEndpointWithSimpleScriptRedirection()
    {
        $_SERVER['SCRIPT_NAME'] = '/botk/core/samples/index.php';
        $_SERVER['REQUEST_URI'] = '/botk/core/samples/hello/testpath?thisisavar=value';

       $result=EndPointFactory::make()
                    ->mountEndPoint('hello', 'EndPointStub')
                    ->run();
                    
        $this->assertEquals($result, 'path');
    }
    
 
    
    /**
     */
    public function testEndpointWithComplexRedirectionRequest()
    {
        $_SERVER['SCRIPT_NAME'] = '/apps/index.php';
        $_SERVER['REQUEST_URI'] = '/botk/core/samples/v1/hello/testpar/mypar?thisisavar=value';  
        $_SERVER['PATH_INFO'] = '/hello/testpar/mypar';  
        
       $result=EndPointFactory::make()
                    ->mountEndPoint('hello', 'EndPointStub')
                    ->run();
                
        $this->assertEquals($result, 'mypar');
    }

    
    
}
