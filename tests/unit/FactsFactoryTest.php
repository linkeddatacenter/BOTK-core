<?php
use PHPUnit\Framework\TestCase;

class FactsFactoryTest extends TestCase
{	

	public function testMakeThing()
	{
		$profile = array(
			'datamapper'	=> function(array $rawdata){
				$data = array();
				$data['identifier'] = $rawdata[0];
				$data['homepage'] = $rawdata[1] ;
				$data['alternateName'][] = $rawdata[2];
				$data['alternateName'][] = $rawdata[3];			
				return $data;
			},
		);
		$rawdata = array(
			'1',
			'linkeddata.center',
			'LinkedData.Center',
			'LDC'
		);

		$factsFactory = new \BOTK\FactsFactory($profile);
		$facts = $factsFactory->factualize($rawdata);
		$structuredData = $facts->asArray();
		$this->assertInstanceOf('\BOTK\Model\SampleSchemaThing', $facts);
		$this->assertEquals($structuredData['identifier'], ['1']);
		$this->assertEquals($structuredData['homepage'], 'http://linkeddata.center');
		$this->assertEquals(2, count($structuredData['alternateName']));
	}

}

