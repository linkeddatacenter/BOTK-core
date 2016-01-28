<?php
namespace BOTK\Core\Representations;

use PHPUnit_Framework_TestCase;
use BOTK\Core\Representations\Standard as Representation;
/**
 * @covers BOTK\Core\Representations\Standard
 * @covers BOTK\Core\Representations\AbstractContentNegotiationPolicy
 */
class RepresentationTest extends PHPUnit_Framework_TestCase
{
    public function testRepresentationIndex()
    {
        $r = new Representation;
        $array= $r->renderers();
        $this->assertEquals(array_keys($array),array(
        'application/json',
        'application/xml', 
        'text/html',
        'application/x-php',
        'text/x-php',
        'text/plain',
        ));
    }
    
       
    public function testHtmlSerializer()
    {
        $array1 = array('1',2,'abc','de'=>'ef');    
        $tmp=Representation::htmlSerializer($array1,Representation::$htmlMetadata);
        $this->assertStringStartsWith('<',$tmp);
    } 


    
}
