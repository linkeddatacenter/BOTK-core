<?php

class DummyModel extends BOTK\Model\AbstractModel
{
	public function asTurtle() { return '<urn:a:b> owl:sameAs <urn:a:b> .';}
}

class AbstractModelTest extends PHPUnit_Framework_TestCase
{
	protected $vocabulary = array(
		'botk' 		=> 'http://http://linkeddata.center/botk/v1#',
		'schema'	=> 'http://schema.org/',
		'wgs' 		=> 'http://www.w3.org/2003/01/geo/wgs84_pos#',
		'xsd' 		=> 'http://www.w3.org/2001/XMLSchema#',
		'dct' 		=> 'http://purl.org/dc/terms/',
		'foaf' 		=> 'http://xmlns.com/foaf/0.1/',
	);
	
	
	public function testGetVocabulary()
	{
		$vocabulary = array(
			'botk' 		=> 'http://http://linkeddata.center/botk/v1#',
			'schema'	=> 'http://schema.org/',
			'wgs' 		=> 'http://www.w3.org/2003/01/geo/wgs84_pos#',
			'xsd' 		=> 'http://www.w3.org/2001/XMLSchema#',
			'dct' 		=> 'http://purl.org/dc/terms/',
			'foaf' 		=> 'http://xmlns.com/foaf/0.1/',
		);
		
		$obj = new DummyModel(array());
		
		$this->assertEquals($this->vocabulary,  $obj->getVocabulary());
	}
	
	
	public function testSetVocabulary()
	{
		$vocabulary = $this->vocabulary;
		$vocabulary['my'] = 'urn:test:';
		
		$obj = new DummyModel(array());
		$obj->setVocabulary('my','urn:test:');
		
		$this->assertEquals($vocabulary,  $obj->getVocabulary());
	}
	
	
	public function testUnsetVocabulary()
	{
		$vocabulary = $this->vocabulary;
		unset($vocabulary['foaf']);
		
		$obj = new DummyModel(array());
		$obj->unsetVocabulary('foaf');
		
		$this->assertEquals($vocabulary,  $obj->getVocabulary());
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
	
	

	public function testasString()
	{		
		$obj = new DummyModel(array());
		$s= $obj->getTurtleHeader() ."\n<urn:a:b> owl:sameAs <urn:a:b> .";
		
		$this->assertEquals($s,  (string)$obj);
	}
	
	

}

