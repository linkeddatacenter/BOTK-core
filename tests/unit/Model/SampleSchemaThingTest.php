<?php
use PHPUnit\Framework\TestCase;


class SampleSchemaThingTest extends TestCase
{	

	public function testConstructorWithCustomOptions()
	{
	    $thing = BOTK\Model\SampleSchemaThing::fromArray(array(), array (
			'base'	=> array('default'	=> 'urn:a:'),
			'lang'	=> array('default'	=> 'en'),
		));
		$options = $thing->getOptions();
		$this->assertEquals(
			array(
			'default'	=> 'urn:a:',
			'filter'    => FILTER_CALLBACK,
            'options' 	=> '\BOTK\Filters::FILTER_VALIDATE_URI',
        	'flags'  	=> FILTER_REQUIRE_SCALAR,
           ),
			$options['base']
		);
		$this->assertEquals(array('default'    => 'en'),$options['lang']);
	}
	

    /**
     * @dataProvider uris
     */	
	public function testGetUri($data, $expectedData)
	{
	    $obj = BOTK\Model\SampleSchemaThing::fromArray($data);
		$obj->setIdGenerator(function($d){return'abc';});
		$this->assertEquals($expectedData, $obj->getUri());
	}
	public function uris()
    {
    	return array( 
	    	array( array(),	'urn:resource:abc'),
	    	array( array('base'=>'http://example.com/resource/'),	'http://example.com/resource/abc'),
		);
   	}


	public function testAsStdObject()
	{
		$data = new \stdClass;
		
		$expectedData = clone($data);
		$expectedData->base = 'urn:resource:';
		
		$dummyObj = BOTK\Model\SampleSchemaThing::fromStdObject($data);
		
		$this->assertEquals($expectedData, $dummyObj->asStdObject());
	}

}

