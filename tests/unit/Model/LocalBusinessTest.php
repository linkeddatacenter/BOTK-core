<?php

class LocalBusinessTest extends PHPUnit_Framework_TestCase
{	
    /**
     * @dataProvider goodLocalBusiness
     */	
	public function testDataFilteringWithValidDataAndDefaultOptions($data, $expectedData)
	{
		$localBusiness = new BOTK\Model\LocalBusiness($data);		
		$this->assertEquals($expectedData, $localBusiness->asArray());
	}
	
	public function goodLocalBusiness()
    {
    	return array( 
    		array(
	    		array(),
	    		array(
					'base'				=> 'http://linkeddata.center/botk/resource/',	
					'lang'				=> 'it',
					'addressCountry'	=> 'IT',
				),
			),
			
    		array(
	    		array(
					'base'				=> 'http://linkeddata.center/botk/resource#',
					'lang'				=> 'en',
					'addressCountry'	=> 'US',
				),
	    		array(
					'base'				=> 'http://linkeddata.center/botk/resource#',
					'lang'				=> 'en',
					'addressCountry'	=> 'US',
				),
			),
			
    		array(
	    		array(
	    			'id'				=> '1234567890',
					'taxID'				=> 'fgn nrc 63S0 6F205 A',
					'vatID'				=> '01234567890',
					'legalName'			=> 'Test  soc srl',
					'businessName'		=> 'Test  soc srl',
					'addressCountry'	=> 'IT',
					'addressLocality'	=> 'LECCO',
					'addressRegion'		=> 'LC',
					'streetAddress'		=> 'Via  F. Valsecchi,124',
					'postalCode'		=> '23900',
					'page'				=> 'http://linkeddata.center/',
					'telephone'			=> '+39 3356382949',
					'faxNumber'			=> '+39 3356382949',
					'email'				=> array('enrico@fagnoni.com'),
					'geoDescription'	=> array('Via  F. Valsecchi,124-23900 Lecco (LC)'),
					'lat'				=> '1.12345',
					'long'				=> '2.123456',
				),
	    		array(
					'base'				=> 'http://linkeddata.center/botk/resource/',	
					'lang'				=> 'it',
	    			'id'				=> '1234567890',
					'taxID'				=> 'FGNNRC63S06F205A',
					'vatID'				=> '01234567890',
					'legalName'			=> 'TEST SOC SRL',
					'businessName'		=> array('Test  soc srl'),
					'addressCountry'	=> 'IT',
					'addressLocality'	=> 'LECCO',
					'addressRegion'		=> 'LC',
					'streetAddress'		=> 'VIA F.VALSECCHI, 124',
					'postalCode'		=> '23900',
					'page'				=> array('http://linkeddata.center/'),
					'telephone'			=> '3356382949',
					'faxNumber'			=> '3356382949',
					'email'				=> array('ENRICO@FAGNONI.COM'),
					'geoDescription'	=> array('VIA F.VALSECCHI, 124 - 23900 LECCO (LC)'),
					'lat'				=> '1.12345',
					'long'				=> '2.123456',
				),
			),
    		array(
	    		array(
	    			'id'				=> '1234567890',
					'addressCountry'	=> null,
				),
	    		array(
					'base'				=> 'http://linkeddata.center/botk/resource/',	
					'lang'				=> 'it',
	    			'id'				=> '1234567890',
					'addressCountry'	=> 'IT',
	    		),
			),			
		);
   	}

	public function testGetDefaultOptions()
	{	
		$expectedOptions =  array (
			'businessType'			=> array(		
								// additional types  for schema:Place
								'filter'    => FILTER_VALIDATE_REGEXP,
		                        'options' 	=> array('regexp'=>'/^\w+:\w+$/'),
                            	'flags'  	=> FILTER_FORCE_ARRAY,
			                   ),
			'base'				=> array(
									'default'	=> 'http://linkeddata.center/botk/resource/',
									'filter'    => FILTER_SANITIZE_URL,
	                            	'flags'  	=> FILTER_REQUIRE_SCALAR,
				                   ),
			'uri'				=> array(
									'filter'    => FILTER_SANITIZE_URL,
	                            	'flags'  	=> FILTER_REQUIRE_SCALAR,
				                   ),
			'lang'				=> array(
									'default'	=> 'it',		
									'filter'    => FILTER_VALIDATE_REGEXP,
			                        'options' 	=> array('regexp'=>'/^[a-z]{2}$/'),
	                            	'flags'  	=> FILTER_REQUIRE_SCALAR,
				                   ),
			'id'				=> array(		
									'filter'    => FILTER_VALIDATE_REGEXP,
			                        'options' 	=> array('regexp'=>'/^\w+$/'),
	                            	'flags'  	=> FILTER_REQUIRE_SCALAR,
				                   ),
			'taxID'				=> array(	
									'filter'    => FILTER_CALLBACK,
			                        'options' 	=> '\BOTK\Filters::FILTER_SANITIZE_TOKEN',
	                            	'flags'  	=> FILTER_REQUIRE_SCALAR,
				                   ),
			'vatID'				=> array(	// italian rules
									'filter'    => FILTER_VALIDATE_REGEXP,
			                        'options' 	=> array('regexp'=>'/^[0-9]{11}$/'),
	                            	'flags'  	=> FILTER_REQUIRE_SCALAR,
				                   ),
			'legalName'			=> array(
									'filter'    => FILTER_CALLBACK,
			                        'options' 	=> '\BOTK\Filters::FILTER_SANITIZE_ADDRESS',
	                            	'flags'  	=> FILTER_REQUIRE_SCALAR,
				                   ),
			'businessName'		=> array(
									'filter'    => FILTER_DEFAULT,
	                            	'flags'  	=> FILTER_FORCE_ARRAY,
								   ),
			'addressCountry'	=> array(
									'default'	=> 'IT',		
									'filter'    => FILTER_VALIDATE_REGEXP,
			                        'options' 	=> array('regexp'=>'/^[A-Z]{2}$/'),
	                            	'flags'  	=> FILTER_REQUIRE_SCALAR,
				                   ),
			'addressLocality'	=> array(	
									'filter'    => FILTER_CALLBACK,
			                        'options' 	=> '\BOTK\Filters::FILTER_SANITIZE_ADDRESS',
	                            	'flags'  	=> FILTER_REQUIRE_SCALAR,
				                   ),
			'addressRegion'		=> array(	
									'filter'    => FILTER_CALLBACK,
			                        'options' 	=> '\BOTK\Filters::FILTER_SANITIZE_ADDRESS',
	                            	'flags'  	=> FILTER_REQUIRE_SCALAR,
				                   ),
			'streetAddress'		=> array(	
									'filter'    => FILTER_CALLBACK,
			                        'options' 	=> '\BOTK\Filters::FILTER_SANITIZE_ADDRESS',
	                            	'flags'  	=> FILTER_REQUIRE_SCALAR,
				                   ),
			'postalCode'		=> array(	// italian rules
									'filter'    => FILTER_VALIDATE_REGEXP,
			                        'options' 	=> array('regexp'=>'/^[0-9]{5}$/'),
	                            	'flags'  	=> FILTER_REQUIRE_SCALAR,
				                   ),
			'page'				=> array(	
									'filter'    => FILTER_SANITIZE_URL,
	                            	'flags'  	=> FILTER_FORCE_ARRAY,
				                   ),
			'telephone'			=> array(	
									'filter'    => FILTER_CALLBACK,	
			                        'options' 	=> '\BOTK\Filters::FILTER_SANITIZE_TELEPHONE',
	                            	'flags'  	=> FILTER_FORCE_ARRAY,
				                   ),
			'faxNumber'			=> array(	
									'filter'    => FILTER_CALLBACK,
			                        'options' 	=> '\BOTK\Filters::FILTER_SANITIZE_TELEPHONE',
	                            	'flags'  	=> FILTER_FORCE_ARRAY,
				                   ),
			'email'				=> array(	
									'filter'    => FILTER_CALLBACK,
			                        'options' 	=> '\BOTK\Filters::FILTER_SANITIZE_EMAIL',
	                            	'flags'  	=> FILTER_FORCE_ARRAY,
				                   ),
			'geoDescription'	=> array(	
									'filter'    => FILTER_CALLBACK,	
			                        'options' 	=> '\BOTK\Filters::FILTER_SANITIZE_ADDRESS',
	                            	'flags'  	=> FILTER_FORCE_ARRAY,
				                   ),
			'lat'				=> array( // http://www.regexlib.com/REDetails.aspx?regexp_id=2728
									'filter'    => FILTER_VALIDATE_REGEXP,
			                        'options' 	=> array('regexp'=>'/^-?([1-8]?[0-9]\.{1}\d{1,6}$|90\.{1}0{1,6}$)/'),
	                            	'flags'  	=> FILTER_REQUIRE_SCALAR,
				                   ),
			'long'				=> array( // http://stackoverflow.com/questions/3518504/regular-expression-for-matching-latitude-longitude-coordinates
									'filter'    => FILTER_VALIDATE_REGEXP,
			                        'options' 	=> array('regexp'=>'/-?([1-8]?[0-9]\.{1}\d{1,6}$|90\.{1}0{1,6}$)/'),
	                            	'flags'  	=> FILTER_REQUIRE_SCALAR,
				                   ),
		);
		
		$localBusiness = new BOTK\Model\LocalBusiness(array());
		$this->assertEquals($expectedOptions, $localBusiness->getOptions());
	}



	public function testChangeDefaultOptions()
	{
		$localBusiness = new BOTK\Model\LocalBusiness(array(), array (
			'lang'	=> array('default'	=> 'en'),
			'vatID' => array('options' 	=> array('regexp'=>'/^IT[0-9]{11}$/')),
		));
		$options = $localBusiness->getOptions();
		$this->assertEquals(
			array(
				'default'	=> 'en',		
				'filter'    => FILTER_VALIDATE_REGEXP,
	            'flags'  	=> FILTER_REQUIRE_SCALAR,
	            'options' 	=> array('regexp'=>'/^[a-z]{2}$/')
			),
			$options['lang']
		);
		$this->assertEquals(
			array(
				'filter'    => FILTER_VALIDATE_REGEXP,
	            'flags'  	=> FILTER_REQUIRE_SCALAR,
	            'options' 	=> array('regexp'=>'/^IT[0-9]{11}$/')
	        ),
			$options['vatID']
		);
	}


    /**
     * @dataProvider goodRdf
     */	
	public function testRdfGeneration($data, $rdf, $tripleCount)
	{
		$localBusiness = new BOTK\Model\LocalBusiness($data);		
		$this->assertEquals($rdf, $localBusiness->asTurtle());
		$this->assertEquals($tripleCount,  $localBusiness->getTripleCount());
	}
	
	public function goodRdf()
    {
    	return array(
    		array(
    			array(),
    			'',
    			0,
			),
    		array(
    			array(
    				'base'				=> 'urn:',
    				'id'				=> 'abc',
					'vatID'				=> '01234567890',
					'legalName'			=> 'Calenda chiodi snc',
				),
    			'<urn:abc> a schema:Organization;dct:identifier "abc";schema:vatID "01234567890"@it;schema:legalName """CALENDA CHIODI SNC"""@it; . ',
    			4,
			),
			
    		array(
    			array(
    				'uri'				=> 'urn:abc',
					'vatID'				=> '01234567890',
					'legalName'			=> 'Calenda chiodi snc',
				),
    			'<urn:abc> a schema:Organization;schema:vatID "01234567890"@it;schema:legalName """CALENDA CHIODI SNC"""@it; . ',
    			3,
			),
			
    		array(
    			array(
    				'uri'				=> 'urn:abc',
					'lat'				=> '43.23456',
					'long'				=> '35.23444',
				),
    			'<urn:abc> a schema:Organization;schema:location <urn:abc_place>; . <geo:43.23456,35.23444> a schema:GeoCoordinates;wgs:lat 43.23456 ;wgs:long 35.23444 ; . <urn:abc_place> a schema:Place,schema:LocalBusiness;schema:geo <geo:43.23456,35.23444>; . ',
    			7,
			),
		);
	}


    /**
     * @dataProvider structuredAdresses
     */	
	public function testBuildNormalizedAddress($data, $expectedData)
	{
		$localBusiness = new BOTK\Model\LocalBusiness($data);
		$this->assertEquals($expectedData, $localBusiness->buildNormalizedAddress($data));
	}

	
	public function structuredAdresses()
    {
    	return array( 
    		array( 
    			array(
    				'streetAddress'		=> 'Lungolario Luigi Cadorna, 1',
    				'addressLocality'	=> 'Lecco',
    				'addressRegion'		=> 'LC',
    				'addressCountry'	=> 'IT',
    				'postalCode'		=> '23900',	
				),	
				'LUNGOLARIO LUIGI CADORNA, 1, 23900 LECCO (LC) - IT'
			),
    		array( 
    			array(
    				'streetAddress'		=> 'Lungolario Luigi Cadorna, 1',
    				'addressLocality'	=> 'Lecco',
    				'addressCountry'	=> 'IT',	
				),	
				'LUNGOLARIO LUIGI CADORNA, 1, LECCO - IT'
			),
    		array( 
    			array(
    				'streetAddress'		=> 'Lungolario Luigi Cadorna, 1',
    				'addressCountry'	=> 'IT',
    				'postalCode'		=> '23900',	
				),	
				'LUNGOLARIO LUIGI CADORNA, 1, 23900 - IT'
			),
    		array( 
    			array(
    				'addressCountry'	=> 'IT',
    				'postalCode'		=> '23900',	
				),	
				false
			),
		);
   	}


    /**
	 * @expectedException \BOTK\Exceptions\DataModelException
     * @dataProvider badLocalBusiness
	 * 
     */	
    public  function testBadLocalBusiness($data)
	{
		$localBusiness = new BOTK\Model\LocalBusiness($data);
	}

	public function badLocalBusiness()
    {
    	return array( 
			array(array('lang'				=> 'IT')),
			array(array('id'				=> 'invalid id')),
			array(array('vatID'				=> '012345678901')),			//too long
			array(array('addressCountry'	=> 'italy')),					//too long
			array(array('addressCountry'	=> 'it')),						//lowercase
			array(array('postalCode'		=> '234992')),					//toolong
			array(array('email'				=> 'ENRICO')),
			array(array('lat'				=> '90.12345')),				//invalid lat
			array(array('long'				=> '12,123456'))	
		);
   	}	
}

