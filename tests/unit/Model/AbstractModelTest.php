<?php

class DummyModel extends BOTK\Model\AbstractModel
{
	public function asTurtleFragment() { return '<urn:a:b> owl:sameAs <urn:a:b> .';}
}

class AbstractModelTest extends PHPUnit_Framework_TestCase
{
	protected $vocabulary =   array(
		'rdf'		=> 'http://www.w3.org/1999/02/22-rdf-syntax-ns#',
		'rdfs'		=> 'http://www.w3.org/2000/01/rdf-schema#',
		'owl'		=> 'http://www.w3.org/2002/07/owl#',
		'xsd' 		=> 'http://www.w3.org/2001/XMLSchema#',
		'dct' 		=> 'http://purl.org/dc/terms/',
		'void' 		=> 'http://rdfs.org/ns/void#',
		'prov' 		=> 'http://www.w3.org/ns/prov#',
		'sd'		=> 'http://www.w3.org/ns/sparql-service-description#',
		'schema'	=> 'http://schema.org/',
		'wgs' 		=> 'http://www.w3.org/2003/01/geo/wgs84_pos#',
		'foaf' 		=> 'http://xmlns.com/foaf/0.1/',
		'qb'		=> 'http://purl.org/linked-data/cube#',
		'daq'		=> 'http://purl.org/eis/vocab/daq#',
		'kees'		=> 'http://linkeddata.center/kees/v1#',
		'botk'		=> 'http://botk.linkeddata.center/#',
	);
	
	
    /**
     * @dataProvider data
     */	
	public function testArrayConstructor($data, $expectedData)
	{
		$localBusiness = DummyModel::fromArray($data);		
		$this->assertEquals($expectedData, $localBusiness->asArray());
	}
	
	
	/**
     * @dataProvider data
     */	
	public function testStdObjectConstructor($data, $expectedData)
	{
		$localBusiness = DummyModel::fromStdObject((object) $data);		
		$this->assertEquals($expectedData, $localBusiness->asArray());
	}
	
	
	public function data()
    {
    	return array( 
    		array( array(), array('base'=> 'urn:local:'),),
    		array( array('base'	=> 'urn:a:','id'=>'x'), array('base'=> 'urn:a:','id'=>'x')),
		);
   	}


	public function testConstructorWithCustomOptions()
	{
		$localBusiness = DummyModel::fromArray(array(), array (
			'base'	=> array('default'	=> 'urn:a:'),
			'lang'	=> array('default'	=> 'en'),
		));
		$options = $localBusiness->getOptions();
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
	
		
	public function testgetVocabularies()
	{
		$obj = DummyModel::fromArray(array());
		
		$this->assertEquals($this->vocabulary,  $obj->getVocabularies());
	}
	
	

    /**
     * @dataProvider uris
     */	
	public function testGetUri($data, $expectedData)
	{
		$obj = DummyModel::fromArray($data);
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

	public function testTurtleHeader()
	{		
		$obj = DummyModel::fromArray(array());
		$s ="";
		foreach( $this->vocabulary as $p=>$v){
			$s.= "@prefix $p: <$v> .\n";
		}
		
		$this->assertEquals($s,  $obj->getTurtleHeader());
	}
	
	
	public function testTurtleHeaderWithBase()
	{		
		$obj = DummyModel::fromArray(array());
		$s ="@base <urn:a:b> .\n";
		foreach( $this->vocabulary as $p=>$v){
			$s.= "@prefix $p: <$v> .\n";
		}
		
		$this->assertEquals($s,  $obj->getTurtleHeader('urn:a:b'));
	}
	
	

	public function testAsString()
	{		
		$obj = DummyModel::fromArray(array());
		$s= $obj->getTurtleHeader() ."\n<urn:a:b> owl:sameAs <urn:a:b> .";
		
		$this->assertEquals($s,  (string)$obj);
	}
	

	public function testAsStdObject()
	{
		$data = new \stdClass;
		$data->uri = 'urn:test:a';
		
		$expectedData = clone($data);
		$expectedData->base = 'urn:local:';
		
		$dummyObj = DummyModel::fromStdObject($data);
		
		$this->assertEquals($expectedData, $dummyObj->asStdObject());
	}

}

