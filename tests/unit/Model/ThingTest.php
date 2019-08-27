<?php
use PHPUnit\Framework\TestCase;


class ThingTest extends TestCase
{
	

	public function testConstructorWithCustomOptions()
	{
		$thing = BOTK\Model\Thing::fromArray(array(), array (
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
	



    public function testGetDefaultOptions()
    {	
    	$expectedOptions =  array (
		'uri'				=> array(
								'filter'    => FILTER_CALLBACK,
		                        'options' 	=> '\BOTK\Filters::FILTER_VALIDATE_URI',
                            	'flags'  	=> FILTER_REQUIRE_SCALAR,
			                   ),
		'base'				=> array(
								'default'	=> 'urn:local:',
								'filter'    => FILTER_CALLBACK,
		                        'options' 	=> '\BOTK\Filters::FILTER_VALIDATE_URI',
                            	'flags'  	=> FILTER_REQUIRE_SCALAR,
			                   ),
		'id'				=> array(
								'filter'    => FILTER_CALLBACK,
		                        'options' 	=> '\BOTK\Filters::FILTER_SANITIZE_ID',
                            	'flags'  	=> FILTER_REQUIRE_SCALAR,
			                   ),
		'page'				=> array(	
								'filter'    => FILTER_CALLBACK,
		                        'options' 	=> '\BOTK\Filters::FILTER_SANITIZE_HTTP_URL',
                            	'flags'  	=> FILTER_FORCE_ARRAY,
			                   ),
		'homepage'			=> array(	
								'filter'    => FILTER_CALLBACK,
		                        'options' 	=> '\BOTK\Filters::FILTER_SANITIZE_HTTP_URL',
                            	'flags'  	=> FILTER_FORCE_ARRAY,
			                   ),
		'disambiguatingDescription'=> array(	
								'filter'    => FILTER_DEFAULT,
                            	'flags'  	=> FILTER_FORCE_ARRAY,
			                   ),
		'subject'			=> array(	
								'filter'    => FILTER_CALLBACK,
		                        'options' 	=> '\BOTK\Filters::FILTER_VALIDATE_URI',
                            	'flags'  	=> FILTER_FORCE_ARRAY,
			                   ),
		'image'			=> array(	
								'filter'    => FILTER_CALLBACK,
		                        'options' 	=> '\BOTK\Filters::FILTER_SANITIZE_HTTP_URL',
                            	'flags'  	=> FILTER_FORCE_ARRAY,
			                   ),
		'sameAs'			=> array(	
								'filter'    => FILTER_CALLBACK,
		                        'options' 	=> '\BOTK\Filters::FILTER_VALIDATE_URI',
                            	'flags'  	=> FILTER_FORCE_ARRAY,
			                   ),
		'name'				=> array(		
								'filter'    => FILTER_DEFAULT,
                            	'flags'  	=> FILTER_FORCE_ARRAY,
			                   ),
		'alternateName'		=> array(		
								'filter'    => FILTER_DEFAULT,
                            	'flags'  	=> FILTER_FORCE_ARRAY,
			                   ),
		'description'		=> array(		
								'filter'    => FILTER_DEFAULT,
                            	'flags'  	=> FILTER_FORCE_ARRAY,
			                   ),
		'similarTo'		=> array(	
			'filter'    => FILTER_CALLBACK,
			'options' 	=> '\BOTK\Filters::FILTER_VALIDATE_URI',
			'flags'  	=> FILTER_FORCE_ARRAY
			),	

		);

	$thing = BOTK\Model\Thing::fromArray(array());
	$this->assertEquals($expectedOptions, $thing->getOptions());
}


    /**
     * @dataProvider uris
     */	
	public function testGetUri($data, $expectedData)
	{
		$obj = BOTK\Model\Thing::fromArray($data);
		$obj->setIdGenerator(function($d){return'abc';});
		$this->assertEquals($expectedData, $obj->getUri());
	}
	public function uris()
    {
    	return array( 
	    	array( array(),	'urn:local:abc'),
	    	array( array('base'=>'http://example.com/resource/'),	'http://example.com/resource/abc'),
	    	array( array('base'=>'http://example.com/resource/', 'id'=>'efg'),	'http://example.com/resource/efg'),
	    	array( array('uri'=>'http://example.com/resource/ijk'),	'http://example.com/resource/ijk'),	
		);
   	}


	public function testAsStdObject()
	{
		$data = new \stdClass;
		$data->uri = 'urn:test:a';
		
		$expectedData = clone($data);
		$expectedData->base = 'urn:local:';
		
		$dummyObj = BOTK\Model\Thing::fromStdObject($data);
		
		$this->assertEquals($expectedData, $dummyObj->asStdObject());
	}

}

