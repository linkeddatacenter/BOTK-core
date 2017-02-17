<?php

class DummyModel extends BOTK\Model\AbstractModel
{
	public function asTurtle() { return '<urn:a:b> owl:sameAs <urn:a:b> .';}
}

class AbstractModelTest extends PHPUnit_Framework_TestCase
{
	protected $vocabulary =  array(
		'rdf'		=> 'http://www.w3.org/1999/02/22-rdf-syntax-ns#',
		'rdfs'		=> 'http://www.w3.org/2000/01/rdf-schema#',
		'owl'		=> 'http://www.w3.org/2002/07/owl#',
		'xsd' 		=> 'http://www.w3.org/2001/XMLSchema#',
		'dct' 		=> 'http://purl.org/dc/terms/',
		'void' 		=> 'http://rdfs.org/ns/void#',
		'prov' 		=> 'http://www.w3.org/ns/prov#',
		'schema'	=> 'http://schema.org/',
		'wgs' 		=> 'http://www.w3.org/2003/01/geo/wgs84_pos#',
		'foaf' 		=> 'http://xmlns.com/foaf/0.1/',
		'dq'		=> 'http://purl.org/linked-data/cube#',
		'daq'		=> 'http://purl.org/eis/vocab/daq#',
		'kees'		=> 'http://linkeddata.center/kees/v1#',
	);
	
	
    /**
     * @dataProvider data
     */	
	public function testConstructor($data, $expectedData)
	{
		$localBusiness = new DummyModel($data);		
		$this->assertEquals($expectedData, $localBusiness->asArray());
	}
	public function data()
    {
    	return array( 
    		array( array(), array('base'=> 'http://linkeddata.center/botk/resource/'),),
    		array( array('base'	=> 'urn:a:','id'=>'x'), array('base'=> 'urn:a:','id'=>'x')),
		);
   	}


	public function testConstructorWithCustomOptions()
	{
		$localBusiness = new DummyModel(array(), array (
			'base'	=> array('default'	=> 'urn:a:'),
			'lang'	=> array('default'	=> 'en'),
		));
		$options = $localBusiness->getOptions();
		$this->assertEquals(
			array(		
				'default'    => 'urn:a:',
				'filter'    => FILTER_SANITIZE_URL,
            	'flags'  	=> FILTER_REQUIRE_SCALAR,
			),
			$options['base']
		);
		$this->assertEquals(array('default'    => 'en'),$options['lang']);
	}
	
		
	public function testgetVocabularies()
	{
		$obj = new DummyModel(array());
		
		$this->assertEquals($this->vocabulary,  $obj->getVocabularies());
	}
	
	

    /**
     * @dataProvider uris
     */	
	public function testGetUri($data, $expectedData)
	{
		$obj = new DummyModel($data);
		$obj->setIdGenerator(function($d){return'abc';});
		$this->assertEquals($expectedData, $obj->getUri());
	}
	public function uris()
    {
    	return array( 
	    	array( array(),	'http://linkeddata.center/botk/resource/abc'),
	    	array( array('base'=>'http://example.com/resource/'),	'http://example.com/resource/abc'),
	    	array( array('base'=>'http://example.com/resource/', 'id'=>'efg'),	'http://example.com/resource/efg'),
	    	array( array('uri'=>'http://example.com/resource/ijk'),	'http://example.com/resource/ijk'),	
		);
   	}

	public function testTurtleHeader()
	{		
		$obj = new DummyModel(array());
		$s ="";
		foreach( $this->vocabulary as $p=>$v){
			$s.= "@prefix $p: <$v> .\n";
		}
		
		$this->assertEquals($s,  $obj->getTurtleHeader());
	}
	
	
	public function testTurtleHeaderWithBase()
	{		
		$obj = new DummyModel(array());
		$s ="@base <urn:a:b> .\n";
		foreach( $this->vocabulary as $p=>$v){
			$s.= "@prefix $p: <$v> .\n";
		}
		
		$this->assertEquals($s,  $obj->getTurtleHeader('urn:a:b'));
	}
	
	

	public function testAsString()
	{		
		$obj = new DummyModel(array());
		$s= $obj->getTurtleHeader() ."\n<urn:a:b> owl:sameAs <urn:a:b> .";
		
		$this->assertEquals($s,  (string)$obj);
	}

}

