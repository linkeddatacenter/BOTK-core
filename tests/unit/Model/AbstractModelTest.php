<?php
use PHPUnit\Framework\TestCase;

class DummyModel extends BOTK\Model\AbstractModel
{
	public function asTurtleFragment() { return '<urn:a:b> owl:sameAs <urn:a:b> .';}
}

class AbstractModelTest extends TestCase
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
		'skos'		=> 'http://www.w3.org/2004/02/skos/core#',
		'kees'		=> 'http://linkeddata.center/kees/v1#',
		'botk'		=> 'http://linkeddata.center/botk/v1#',
	);
	
	
    /**
     * @dataProvider data
     */	
	public function testArrayConstructor($data, $expectedData)
	{
		$dummy = DummyModel::fromArray($data);		
		$this->assertEquals($expectedData, $dummy->asArray());
	}
	
	
	/**
     * @dataProvider data
     */	
	public function testStdObjectConstructor($data, $expectedData)
	{
		$dummy = DummyModel::fromStdObject((object) $data);		
		$this->assertEquals($expectedData, $dummy->asArray());
	}
	
	
	public function data()
    {
    	return array( 
    		array( array(), array(),),
    		array( array('base'	=> 'urn:a:','id'=>'x'), array()),
		);
   	}

		
	public function testgetVocabularies()
	{
		$obj = DummyModel::fromArray(array());
		
		$this->assertEquals($this->vocabulary,  $obj->getVocabularies());
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
		
		$expectedData = clone($data);
		
		$dummyObj = DummyModel::fromStdObject($data);
		
		$this->assertEquals($expectedData, $dummyObj->asStdObject());
	}

}

