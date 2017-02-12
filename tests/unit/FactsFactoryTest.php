<?php

class FactsFactoryTest extends PHPUnit_Framework_TestCase
{	

	public function testMakeLocalBusiness()
	{
		$profile = array(
			'model'			=> 'LocalBusiness',
			'options'		=> array(
				'base' => array( 'default'=> 'urn:test')
			),
			'datamapper'	=> function(array $rawdata){
				$data = array();
				$data['id'] = $rawdata[0];
				$data['legalName'] = $rawdata[2] . ' ' . $rawdata[1];
				$data['alternateName'] = $rawdata[2];
				$data['vatID'] = $rawdata[3];
				$data['email'] = $rawdata[4];
				$data['addressLocality'] = $rawdata[5];
				$data['postalCode'] = $rawdata[7];
				$data['addressRegion'] = $rawdata[8];
				$data['streetAddress'] = $rawdata[9] . ' ' . $rawdata[10] . ', ' . $rawdata[11];
				$data['long'] = $rawdata[14];			
				$data['lat'] = $rawdata[15];
				
				return $data;
			},
		);
		$rawdata = array(
			'10042650',
			'ERBORISTERIA I PRATI DI GIOVANNA MONAMI',
			'',
			'01209991007',
			'',
			'ROMA',
			'ROMA',
			'00195',
			'RM',
			'VIA',
			'ANTONIO MORDINI',
			'3',
			'058091',
			'0580912017145',
			'12.464163',
			'41.914001'
		);

		$factsFactory = new \BOTK\FactsFactory($profile);
		$facts = $factsFactory->factualize($rawdata);
		$structuredData = $facts->asArray();
		
		$this->assertInstanceOf('\BOTK\Model\LocalBusiness', $facts);
		$this->assertEquals($structuredData['vatID'], '01209991007');
		$this->assertEquals($structuredData['id'], '10042650');
		$this->assertEquals($structuredData['long'], '12.464163');
		$this->assertEquals($structuredData['streetAddress'], 'VIA ANTONIO MORDINI, 3');
	}

}

