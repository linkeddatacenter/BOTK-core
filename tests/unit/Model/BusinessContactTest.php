<?php

class BusinessContactTest extends PHPUnit_Framework_TestCase
{	
    /**
     * @dataProvider goodBusinessContact
     */	
    public function testConstructor($data, $expectedData)
    {
    	$localBusiness = BOTK\Model\BusinessContact::fromArray($data);		
    	$this->assertEquals($expectedData, $localBusiness->asArray());
    }
    public function goodBusinessContact()
    {
    	return array( 
    		array(
    			array(),
    			array(
    				'base'				=> 'urn:local:',
    				),
    			),

    		array(
    			array(
					'personType'		=> 'ex:myperson',
					'taxID'				=> 'fgn nrc 63S0 6F205 A',
					'alternateName'		=> 'E.Fagnoni',
					'givenName'			=> 'Enrico',
					'familyName'		=> 'Fagnoni',
					'additionalName'	=> 'ecow',
					'worksFor'			=> 'http://linkeddata.center/',
					'jobTitle'			=> 'ceo',
					'gender'			=> 'M',
					'telephone'			=> '+39 335 63 82 949',
					'email'				=> 'admin@fagnoni.com',
					'spokenLanguage' 	=> 'Italiano',
					'hasOptInOptOutDate'=> '6/11/1963',
					'privacyFlag'		=> 'FALSE',			
				),
 
    			array( 
    				'base'				=> 'urn:local:',
			  		'personType'		=> array('ex:myperson'),
					'taxID'				=> 'FGNNRC63S06F205A',
					'alternateName'		=> 'E.FAGNONI',
					'givenName'			=> 'ENRICO',
					'familyName'		=> 'FAGNONI',
					'additionalName'	=> 'ECOW',
					'worksFor'			=> 'http://linkeddata.center/',
					'jobTitle'			=> array('ceo'),
					'gender'			=> 'http://schema.org/Male',
					'telephone'			=> '3356382949',
					'email'				=> 'ADMIN@FAGNONI.COM',
					'spokenLanguage' 	=> 'it',
					'hasOptInOptOutDate'=> '1963-06-11T00:00:00+00:00',
					'privacyFlag'		=> 'false',			
    				),
    			),
    		);
    }


    public function testGetDefaultOptions()
    {	
    	$expectedOptions =  array (
    		'uri'				=> array(
    			'filter'    => FILTER_CALLBACK,
    			'options' 	=> '\BOTK\Filters::FILTER_VALIDATE_URI',
    			'flags'  	=> FILTER_REQUIRE_SCALAR,
    			),
    		'base'				=> array(
    			'default'	=> 'urn:local:',
    			'filter'    => FILTER_CALLBACK,
    			'options' 	=> '\BOTK\Filters::FILTER_VALIDATE_URI',
    			'flags'  	=> FILTER_REQUIRE_SCALAR,
    			),
    		'id'				=> array(
    			'filter'    => FILTER_CALLBACK,
    			'options' 	=> '\BOTK\Filters::FILTER_SANITIZE_ID',
    			'flags'  	=> FILTER_REQUIRE_SCALAR,
    			),
    		'page'				=> array(	
    			'filter'    => FILTER_CALLBACK,
    			'options' 	=> '\BOTK\Filters::FILTER_SANITIZE_HTTP_URL',
    			'flags'  	=> FILTER_FORCE_ARRAY,
    			),
    		'homepage'			=> array(	
    			'filter'    => FILTER_CALLBACK,
    			'options' 	=> '\BOTK\Filters::FILTER_SANITIZE_HTTP_URL',
    			'flags'  	=> FILTER_FORCE_ARRAY,
    			),
    		'disambiguatingDescription'=> array(	
    			'filter'    => FILTER_DEFAULT,
    			'flags'  	=> FILTER_FORCE_ARRAY,
    			),
		'personType'		=> array(		
			// additional types  as extension of schema:Person
			'filter'    => FILTER_DEFAULT,
			'flags'  	=> FILTER_FORCE_ARRAY,
			),
		'taxID'				=> array(	
			'filter'    => FILTER_CALLBACK,
			'options' 	=> '\BOTK\Filters::FILTER_SANITIZE_TOKEN',
			'flags'  	=> FILTER_REQUIRE_SCALAR,
			),
		'alternateName'		=> array(
			'filter'    => FILTER_CALLBACK,
			'options' 	=> '\BOTK\Filters::FILTER_SANITIZE_PERSON_NAME',
			'flags'  	=> FILTER_FORCE_ARRAY,
			),
		'givenName'				=> array(
			'filter'    => FILTER_CALLBACK,
			'options' 	=> '\BOTK\Filters::FILTER_SANITIZE_PERSON_NAME',
			'flags'  	=> FILTER_REQUIRE_SCALAR,
			),
		'familyName'				=> array(
			'filter'    => FILTER_CALLBACK,
			'options' 	=> '\BOTK\Filters::FILTER_SANITIZE_PERSON_NAME',
			'flags'  	=> FILTER_REQUIRE_SCALAR,
			),
		'additionalName'			=> array(
			'filter'    => FILTER_CALLBACK,
			'options' 	=> '\BOTK\Filters::FILTER_SANITIZE_PERSON_NAME',
			'flags'  	=> FILTER_REQUIRE_SCALAR,
			),
		'jobTitle'	=> array(
			'filter'    => FILTER_DEFAULT,
			'flags'  	=> FILTER_FORCE_ARRAY,
			),
		'honorificPrefix'	=> array(
			'filter'    => FILTER_DEFAULT,
			'flags'  	=> FILTER_FORCE_ARRAY,
			),
		'honorificSuffix'	=> array(
			'filter'    => FILTER_DEFAULT,
			'flags'  	=> FILTER_FORCE_ARRAY,
			),
		'gender'	=> array(	
			'filter'    => FILTER_CALLBACK,
			'options'    => '\BOTK\Filters::FILTER_SANITIZE_GENDER',
			'flags'  	=> FILTER_REQUIRE_SCALAR,
			),
		'worksFor'	=> array(		
			'filter'    => FILTER_CALLBACK,
			'options' 	=> '\BOTK\Filters::FILTER_VALIDATE_URI',
			'flags'  	=> FILTER_REQUIRE_SCALAR,
			),
		'telephone'			=> array(	
			'filter'    => FILTER_CALLBACK,	
			'options' 	=> '\BOTK\Filters::FILTER_SANITIZE_TELEPHONE',
			'flags'  	=> FILTER_FORCE_ARRAY,
			),
		'email'				=> array(	
			'filter'    => FILTER_CALLBACK,
			'options' 	=> '\BOTK\Filters::FILTER_SANITIZE_EMAIL',
			'flags'  	=> FILTER_FORCE_ARRAY,
			),
		'spokenLanguage'			=> array(	
			'filter'    => FILTER_CALLBACK,
			'options' 	=> '\BOTK\Filters::FILTER_SANITIZE_LANGUAGE',
			'flags'  	=> FILTER_FORCE_ARRAY,
			),
		'hasOptInOptOutDate'				=> array(	
			'filter'    => FILTER_CALLBACK,
			'options' 	=> '\BOTK\Filters::FILTER_SANITIZE_DATETIME',
			'flags'  	=> FILTER_REQUIRE_SCALAR,
			),
		'privacyFlag'		=> array(	
			'filter'    => FILTER_CALLBACK,
			'options' 	=> '\BOTK\Filters::FILTER_SANITIZE_BOOLEAN',
			'flags'  	=> FILTER_REQUIRE_SCALAR,
			),
		);
	
		$businessContact = BOTK\Model\BusinessContact::fromArray(array());
		$this->assertEquals($expectedOptions, $businessContact->getOptions());
	}




    /**
     * @dataProvider goodRdf
     */	
    public function testRdfGeneration($data, $rdf, $tripleCount)
    {
    	$businessContact = BOTK\Model\BusinessContact::fromArray($data);		
    	$this->assertEquals($rdf, $businessContact->asTurtleFragment());
    	$this->assertEquals($tripleCount, $businessContact->getTripleCount());
    }
    public function goodRdf()
    {
    	return array(
    		array( 
    			array(
					'base'				=> 'urn:test:',
					'id'				=> 'a',
				),
    			'<urn:test:a> dct:identifier "a";a schema:Person .',
    			2,
    		),
    		array(
    			array(
    				'uri'				=>'urn:test:b',
    				'taxID'				=> '01234567890',
    				'alternateName'		=> 'Enrico Fagnoni',
    			),
    			'<urn:test:b> schema:taxID "01234567890";schema:alternateName "ENRICO FAGNONI";a schema:Person .',
    			3,
    		),
    		array(
    			array(
    				'uri'				=>'urn:test:b',
    				'familyName'		=> 'Fagnoni',
    			),
    			'<urn:test:b> schema:familyName "FAGNONI";schema:alternateName "FAGNONI";a schema:Person .',
    			3,
    		),
			

    		array(
    			array(
    				'uri'				=> 'urn:test:b',
					'personType'		=> 'botk:Person',
					'taxID'				=> '1234',
					'givenName'			=> ' Given',
					'familyName'		=> ' family ',
					'additionalName'	=> 'additional',
					'jobTitle'	        => array('dr.','ing.','grand.uff.','lup.mannar.'),
					'gender'	        => 'm',
					'telephone'			=> '1234567',
					'email'				=> 'a@b.c',
					'worksFor'		    => 'http:/a.c/',
					'spokenLanguage'	=> 'Italiano',
					'hasOptInOptOutDate'=> '10/10/2003',
					'privacyFlag'		=> 1
    				),
    				'<urn:test:b> a botk:Person;schema:taxID "1234";schema:givenName "GIVEN";schema:familyName "FAMILY";schema:additionalName "ADDITIONAL";schema:alternateName "GIVEN ADDITIONAL FAMILY";schema:telephone "1234567";schema:jobTitle "dr.";schema:jobTitle "ing.";schema:jobTitle "grand.uff.";schema:jobTitle "lup.mannar.";schema:email "A@B.C";schema:gender "http://schema.org/Male";schema:worksFor <http:/a.c/> ;botk:spokenLanguage "it";botk:hasOptInOptOutDate "2003-10-10T00:00:00+00:00";botk:privacyFlag true ;a schema:Person .',
    				18,
    			),

    		);
    }



  
    public  function testBadLocalBusiness()
    {
    	$badData = array(
    		'gender'				=>	'uomo', // must start with m or f
			);
    	$businessContact = BOTK\Model\BusinessContact::fromArray($badData);
    	$this->assertEquals(array_keys($badData), $businessContact->getDroppedFields());
    }

}

