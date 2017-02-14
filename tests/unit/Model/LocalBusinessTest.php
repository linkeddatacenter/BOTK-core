<?php

class LocalBusinessTest extends PHPUnit_Framework_TestCase
{	
    /**
     * @dataProvider goodLocalBusiness
     */	
	public function testConstructor($data, $expectedData)
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
					'addressCountry'	=> 'IT',
				),
			),
			
    		array(
	    		array(
					'base'				=> 'urn:a:',
					'addressCountry'	=> 'US',
				),
	    		array(
					'base'				=> 'urn:a:',
					'addressCountry'	=> 'US',
				),
			),
			
    		array(
	    		array(
	    			'id'				=> '1234567890',
					'taxID'				=> 'fgn nrc 63S0 6F205 A',
					'vatID'				=> '01234567890',
					'legalName'			=> 'Example srl',
					'businessName'		=> 'Example',
					'businessType'		=> 'schema:MedicalOrganization',
					'addressCountry'	=> 'IT',
					'addressLocality'	=> 'LECCO',
					'addressRegion'		=> 'LC',
					'streetAddress'		=> 'Via  Fausto Valsecchi,124',
					'postalCode'		=> '23900',
					'page'				=> 'http://linkeddata.center/',
					'telephone'			=> '+39 3356382949',
					'faxNumber'			=> '+39 335 63 82 949',
					'email'				=> array('admin@fagnoni.com'),
					'mailbox'			=> 'info@example.com',
					'addressDescription'=> 'Via  F. Valsecchi,124-23900 Lecco (LC)',
					'lat'				=> '1.12345',
					'long'				=> '2.123456',
				),
	    		array(
					'base'				=> 'http://linkeddata.center/botk/resource/',
	    			'id'				=> '1234567890',
					'businessType'		=> array('schema:MedicalOrganization'),
					'taxID'				=> 'FGNNRC63S06F205A',
					'vatID'				=> '01234567890',
					'legalName'			=> 'EXAMPLE SRL',
					'businessName'		=> array('Example'),
					'addressCountry'	=> 'IT',
					'addressLocality'	=> 'LECCO',
					'addressRegion'		=> 'LC',
					'streetAddress'		=> 'VIA FAUSTO VALSECCHI, 124',
					'postalCode'		=> '23900',
					'page'				=> array('http://linkeddata.center/'),
					'telephone'			=> '3356382949',
					'faxNumber'			=> '3356382949',
					'email'				=> array('ADMIN@FAGNONI.COM'),
					'mailbox'			=> 'INFO@EXAMPLE.COM',
					'addressDescription'=> 'VIA F.VALSECCHI, 124 - 23900 LECCO (LC)',
					'lat'				=> '1.12345',
					'long'				=> '2.123456',
				),
			),
		);
   	}


	public function testGetDefaultOptions()
	{	
		$expectedOptions =  array (
			'uri'				=> array(
									'filter'    => FILTER_SANITIZE_URL,
	                            	'flags'  	=> FILTER_REQUIRE_SCALAR,
				                   ),
			'base'				=> array(
									'default'	=> 'http://linkeddata.center/botk/resource/',
									'filter'    => FILTER_SANITIZE_URL,
	                            	'flags'  	=> FILTER_REQUIRE_SCALAR,
				                   ),
			'id'				=> array(		
									'filter'    => FILTER_CALLBACK,
			                        'options' 	=> '\BOTK\Filters::FILTER_SANITIZE_ID',
	                            	'flags'  	=> FILTER_REQUIRE_SCALAR,
				                   ),
			'page'				=> array(	
									'filter'    => FILTER_SANITIZE_URL,
	                            	'flags'  	=> FILTER_FORCE_ARRAY,
				                   ),
			'homepage'			=> array(	
									'filter'    => FILTER_SANITIZE_URL,
	                            	'flags'  	=> FILTER_FORCE_ARRAY,
				                   ),
			'mailbox'			=> array(	
									'filter'    => FILTER_CALLBACK,
			                        'options' 	=> '\BOTK\Filters::FILTER_SANITIZE_EMAIL',
	                            	'flags'  	=> FILTER_FORCE_ARRAY,
				                   ),
			'businessType'		=> array(		
									// additional types  as extension of schema:LocalBusiness
									'filter'    => FILTER_DEFAULT,
	                            	'flags'  	=> FILTER_FORCE_ARRAY,
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
									// a schema:alternateName for schema:PostalAddress
									'filter'    => FILTER_DEFAULT,
	                            	'flags'  	=> FILTER_FORCE_ARRAY,
								   ),
			'addressDescription'=> array(	//	
									'filter'    => FILTER_CALLBACK,
			                        'options' 	=> '\BOTK\Filters::FILTER_SANITIZE_ADDRESS',
	                            	'flags'  	=> FILTER_REQUIRE_SCALAR,
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
			'lat'				=> array( 
									'filter'    => FILTER_CALLBACK,
			                        'options' 	=> '\BOTK\Filters::FILTER_SANITIZE_GEO',
				                   ),
			'long'				=> array( 
									'filter'    => FILTER_CALLBACK,
			                        'options' 	=> '\BOTK\Filters::FILTER_SANITIZE_GEO',
				                   ),
		);
		
		$localBusiness = new BOTK\Model\LocalBusiness(array());
		$this->assertEquals($expectedOptions, $localBusiness->getOptions());
	}




    /**
     * @dataProvider goodRdf
     */	
	public function testRdfGeneration($data, $rdf, $tripleCount)
	{
		$localBusiness = new BOTK\Model\LocalBusiness($data);		
		$this->assertEquals($rdf, $localBusiness->asTurtle());
		$this->assertEquals($tripleCount, $localBusiness->getTripleCount());
	}
	public function goodRdf()
    {
    	return array(
    		array( 
    			array('uri'=>'urn:test:a'),
    			'<urn:test:a> a schema:LocalBusiness;schema:address <urn:test:a_address>. <urn:test:a_address> a schema:PostalAddress;schema:addressCountry "IT". ',
    			4,
			),
    		array(
    			array(
    				'base'				=> 'urn:test:',
    				'id'				=> 'b',
					'vatID'				=> '01234567890',
					'legalName'			=> 'Calenda chiodi snc',
				),
    			'<urn:test:b> a schema:LocalBusiness;dct:identifier "b";schema:vatID "01234567890";schema:legalName "CALENDA CHIODI SNC";schema:address <urn:test:b_address>. <urn:test:b_address> a schema:PostalAddress;schema:addressCountry "IT". ',
    			7,
			),
			
    		array(
    			array(
	    			'id'				=> '1234567890',
					'taxID'				=> 'fgn nrc 63S0 6F205 A',
					'vatID'				=> '01234567890',
					'legalName'			=> 'Example srl',
					'businessName'		=> 'Example',
					'businessType'		=> 'schema:MedicalOrganization',
					'addressCountry'	=> 'IT',
					'addressLocality'	=> 'LECCO',
					'addressRegion'		=> 'LC',
					'streetAddress'		=> 'Via  Fausto Valsecchi,124',
					'postalCode'		=> '23900',
					'page'				=> 'http://linkeddata.center/',
					'telephone'			=> '+39 3356382949',
					'faxNumber'			=> '+39 335 63 82 949',
					'email'				=> array('admin@fagnoni.com'),
					'mailbox'			=> 'info@example.com',
					'addressDescription'=> 'Via  F. Valsecchi,124-23900 Lecco (LC)',
					'lat'				=> '1.12345',
					'long'				=> '2.123456',
				),
    			'<http://linkeddata.center/botk/resource/1234567890> a schema:LocalBusiness;a schema:MedicalOrganization;dct:identifier "1234567890";schema:vatID "01234567890";schema:legalName "EXAMPLE SRL";schema:alternateName "Example";schema:telephone "3356382949";schema:faxNumber "3356382949";schema:page <http://linkeddata.center/>;schema:email "ADMIN@FAGNONI.COM";foaf:mailbox <mailto:INFO@EXAMPLE.COM>;schema:geo <geo:1.12345,2.123456>;schema:address <http://linkeddata.center/botk/resource/1234567890_address>. <http://linkeddata.center/botk/resource/1234567890_address> a schema:PostalAddress;schema:description "VIA F.VALSECCHI, 124 - 23900 LECCO (LC)";schema:streetAddress "VIA FAUSTO VALSECCHI, 124";schema:postalCode "23900";schema:addressLocality "LECCO";schema:addressRegion "LC";schema:addressCountry "IT". <geo:1.12345,2.123456> a schema:GeoCoordinates;wgs:lat "1.12345"^^xsd:float;wgs:long "2.123456"^^xsd:float . ',
    			23,
			),
		);
	}



    /**
     * @dataProvider structuredAdresses
     */	
	public function testBuildNormalizedAddress($rawdata, $expectedData)
	{
		$localBusiness = new BOTK\Model\LocalBusiness($rawdata);
		$data=$localBusiness->asArray();
		$this->assertEquals($expectedData, $data['addressDescription']);
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
				'LUNGOLARIO LUIGI CADORNA, 1, 23900 LECCO (LC)'
			),
    		array( 
    			array(
    				'streetAddress'		=> 'Lungolario Luigi Cadorna, 1',
    				'addressLocality'	=> 'Lecco',
    				'addressCountry'	=> 'IT',	
				),	
				'LUNGOLARIO LUIGI CADORNA, 1, LECCO'
			),
    		array( 
    			array(
    				'streetAddress'		=> 'Lungolario Luigi Cadorna, 1',
    				'addressCountry'	=> 'IT',
    				'postalCode'		=> '23900',	
				),	
				'LUNGOLARIO LUIGI CADORNA, 1, 23900'
			),
    		array( 
    			array(
    				'addressDescription'	=> 'test address',
				),	
				'TEST ADDRESS'
			),
		);
   	}


    public  function testBadLocalBusiness()
	{
		$badData = array(
			'vatID'				=>	'not a vat',
			'addressCountry'	=>  'ITALY', 	// should be a two character ISO country code
			'postalCode'		=>  '234992',	// too long
			'email'				=>  'not an e mail',
		);
		$localBusiness = new BOTK\Model\LocalBusiness($badData);
		$this->assertEquals(array_keys($badData), $localBusiness->getDroppedFields());
	}

}

