<?php
use BOTK\Core\EndPoint;


/**
 * @covers BOTK\Core\EndPoint
 */
class EndPointTest extends PHPUnit_Framework_TestCase
{
    public $app;

    public function setUp()
    {
        // Simulate an HTTP Request
        $_SERVER['SCRIPT_NAME'] = '/samples/index.php';
        $_SERVER['REQUEST_URI'] = '/samples/hello?_output=text/plain';
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['HTTP_ACCEPT'] = 'text/html';
        
        $_GET['_output'] = 'text/html';
        $_REQUEST =& $_GET;

    }


    /**
     */
    public function testEndPointDefaultConstructor()
    {
        $endPoint = new EndPointStub;
        $this->assertEquals('/samples',$endPoint->getEndPointPath());
        $this->assertEquals(false,$endPoint->isAutoDispatched, 'isAutoDispatched should be false');
        $this->assertEquals(true, $endPoint->methodOverriding,'methodOverriding should be true');
        //$this->assertEquals('text/plain',$_SERVER['HTTP_ACCEPT']);
    }
}
