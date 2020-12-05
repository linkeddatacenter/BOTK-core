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
        'xsd' 		=> 'http://www.w3.org/2001/XMLSchema#',
	);
	
		
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
		$s= $obj->getTurtleHeader('urn:resource:') ."\n<urn:a:b> owl:sameAs <urn:a:b> .";
		
		$this->assertEquals($s,  (string)$obj);
	}


}

