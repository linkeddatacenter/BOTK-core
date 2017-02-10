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
					'alternateName'		=> 'Test  soc srl',
					'addressCountry'	=> 'IT',
					'addressLocality'	=> 'LECCO',
					'addressRegion'		=> 'LC',
					'streetAddress'		=> 'Via  F. Valsecchi,124',
					'postalCode'		=> '23900',
					'page'				=> 'http://linkeddata.center/',
					'telephone'			=> '+39 3356382949',
					'faxNumber'			=> '0341 255188 ',
					'email'				=> 'enrico@fagnoni.com',
					'geoDescription'	=> 'Via  F. Valsecchi,124-23900 Lecco (LC)',
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
					'alternateName'		=> 'Test  soc srl',
					'addressCountry'	=> 'IT',
					'addressLocality'	=> 'LECCO',
					'addressRegion'		=> 'LC',
					'streetAddress'		=> 'VIA F.VALSECCHI, 124',
					'postalCode'		=> '23900',
					'page'				=> 'http://linkeddata.center/',
					'telephone'			=> '3356382949',
					'faxNumber'			=> '0341255188',
					'email'				=> 'ENRICO@FAGNONI.COM',
					'geoDescription'	=> 'VIA F.VALSECCHI, 124 - 23900 LECCO (LC)',
					'lat'				=> '1.12345',
					'long'				=> '2.123456',
				),
			),
    		array(
	    		array(
	    			'id'				=> '1234567890',
					'taxID'				=> '',
					'vatID'				=> '',
					'legalName'			=> null,
					'alternateName'		=> '',
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
		$options = array (
			'base'				=> array(),
			'lang'				=> array(
									'default'	=> 'en',		
									'filter'    => FILTER_VALIDATE_REGEXP,
			                        'options' 	=> array('regexp'=>'/^[a-z]{2}$/')
				                   ),
		);
		
		$expectedOptions =array (
			'base'				=> array(),
			'uri'				=> array(
									'filter'    => FILTER_SANITIZE_URL,
				                   ),
			'lang'				=> array(
									'default'	=> 'en',		
									'filter'    => FILTER_VALIDATE_REGEXP,
			                        'options' 	=> array('regexp'=>'/^[a-z]{2}$/')
				                   ),
			'id'				=> array(		
									'filter'    => FILTER_VALIDATE_REGEXP,
			                        'options' 	=> array('regexp'=>'/^[\w]+$/')
				                   ),
			'taxID'				=> array(	
									'filter'    => FILTER_CALLBACK,
			                        'options' 	=> '\BOTK\Filters::normalizeToken'
				                   ),
			'vatID'				=> array(	// italian rules
									'filter'    => FILTER_VALIDATE_REGEXP,
			                        'options' 	=> array('regexp'=>'/^[0-9]{11}$/')
				                   ),
			'legalName'			=> array(
									'filter'    => FILTER_CALLBACK,
			                        'options' 	=> '\BOTK\Filters::normalizeAddress'
				                   ),
			'alternateName'		=> array(),
			'addressCountry'	=> array(
									'default'	=> 'IT',		
									'filter'    => FILTER_VALIDATE_REGEXP,
			                        'options' 	=> array('regexp'=>'/^[A-Z]{2}$/')
				                   ),
			'addressLocality'	=> array(	
									'filter'    => FILTER_CALLBACK,
			                        'options' 	=> '\BOTK\Filters::normalizeAddress'
				                   ),
			'addressRegion'		=> array(	
									'filter'    => FILTER_CALLBACK,
			                        'options' 	=> '\BOTK\Filters::normalizeAddress'
				                   ),
			'streetAddress'		=> array(	
									'filter'    => FILTER_CALLBACK,
			                        'options' 	=> '\BOTK\Filters::normalizeAddress'
				                   ),
			'postalCode'		=> array(	// italian rules
									'filter'    => FILTER_VALIDATE_REGEXP,
			                        'options' 	=> array('regexp'=>'/^[0-9]{5}$/')
				                   ),
			'page'				=> array(	
									'filter'    => FILTER_SANITIZE_URL
				                   ),
			'telephone'			=> array(	
									'filter'    => FILTER_CALLBACK,
			                        'options' 	=> '\BOTK\Filters::normalizeItTelephone'
				                   ),
			'faxNumber'			=> array(	
									'filter'    => FILTER_CALLBACK,
			                        'options' 	=> '\BOTK\Filters::normalizeItTelephone'
				                   ),
			'email'				=> array(	
									'filter'    => FILTER_CALLBACK,
			                        'options' 	=> '\BOTK\Filters::normalizeEmail'
				                   ),
			'geoDescription'	=> array(	
									'filter'    => FILTER_CALLBACK,
			                        'options' 	=> '\BOTK\Filters::normalizeAddress'
				                   ),
			'lat'				=> array( // http://www.regexlib.com/REDetails.aspx?regexp_id=2728
									'filter'    => FILTER_VALIDATE_REGEXP,
			                        'options' 	=> array('regexp'=>'/^-?([1-8]?[0-9]\.{1}\d{1,6}$|90\.{1}0{1,6}$)/')
				                   ),
			'long'				=> array( // http://stackoverflow.com/questions/3518504/regular-expression-for-matching-latitude-longitude-coordinates
									'filter'    => FILTER_VALIDATE_REGEXP,
			                        'options' 	=> array('regexp'=>'/-?([1-8]?[0-9]\.{1}\d{1,6}$|90\.{1}0{1,6}$)/')
				                   ),
		);
		
		$localBusiness = new BOTK\Model\LocalBusiness(array(),$options);
		$this->assertEquals($expectedOptions, $localBusiness->getOptions());
	}


    /**
     * @dataProvider goodRdf
     */	
	public function testRdfGeneration($data, $rdf, $tripleCount)
	{
		$localBusiness = new BOTK\Model\LocalBusiness($data);		
		$this->assertEquals($rdf, (string) $localBusiness);
		$this->assertEquals($localBusiness->asTurtle(), (string) $localBusiness, "equivalence with __tostring");
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
    			'<urn:abc> a schema:Organization;schema:location <urn:abc_place>; . <geo:43.23456,35.23444> a schema:GeoCoordinates;wgs:lat 43.23456 ;wgs:long 35.23444 ; . <urn:abc_place> a schema:LocalBusiness;schema:geo <geo:43.23456,35.23444>; . ',
    			7,
			),
		);
	}
}

