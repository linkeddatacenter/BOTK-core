    <?php

class LocalBusinessTest extends PHPUnit_Framework_TestCase
{	
    /**
     * @dataProvider goodLocalBusiness
     */	
    public function testConstructor($data, $expectedData)
    {
    	$localBusiness = BOTK\Model\LocalBusiness::fromArray($data);		
    	$this->assertEquals($expectedData, $localBusiness->asArray());
    }
    public function goodLocalBusiness()
    {
    	return array( 
    		array(
    			array(),
    			array(
    				'base'				=> 'urn:local:',
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
    				'page'				=> 'linkeddata.center',
    				'telephone'			=> '+39 3356382949',
    				'faxNumber'			=> '+39 335 63 82 949',
    				'email'				=> array('mailto:admin@fagnoni.com'),
    				'addressDescription'=> 'Via  F. Valsecchi,124-23900 Lecco (LC)',
    				'lat'				=> '1.12345',
    				'long'				=> '2.123456',
                    'hasTotDevelopers'  => '1254'
    				),
    			array(
    				'base'				=> 'urn:local:',
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
    				'page'				=> 'http://linkeddata.center',
    				'telephone'			=> '3356382949',
    				'faxNumber'			=> '3356382949',
    				'email'				=> array('ADMIN@FAGNONI.COM'),
    				'addressDescription'=> 'VIA F.VALSECCHI, 124 - 23900 LECCO (LC)',
    				'lat'				=> '1.12345',
    				'long'				=> '2.123456',
                    'hasTotDevelopers'  => '1254..1254'
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
		'subject'			=> array(	
								'filter'    => FILTER_CALLBACK,
		                        'options' 	=> '\BOTK\Filters::FILTER_VALIDATE_URI',
                            	'flags'  	=> FILTER_FORCE_ARRAY,
			                   ),
		'image'			=> array(	
								'filter'    => FILTER_CALLBACK,
		                        'options' 	=> '\BOTK\Filters::FILTER_SANITIZE_HTTP_URL',
                            	'flags'  	=> FILTER_FORCE_ARRAY,
			                   ),
		'sameAs'			=> array(	
								'filter'    => FILTER_CALLBACK,
		                        'options' 	=> '\BOTK\Filters::FILTER_VALIDATE_URI',
                            	'flags'  	=> FILTER_FORCE_ARRAY,
			                   ),
		'name'				=> array(		
								'filter'    => FILTER_DEFAULT,
                            	'flags'  	=> FILTER_FORCE_ARRAY,
			                   ),
		'alternateName'		=> array(		
								'filter'    => FILTER_DEFAULT,
                            	'flags'  	=> FILTER_FORCE_ARRAY,
			                   ),
		'description'		=> array(		
								'filter'    => FILTER_DEFAULT,
                            	'flags'  	=> FILTER_FORCE_ARRAY,
			                   ),
		'similarName'		=> array(	
			'filter'    => FILTER_CALLBACK,
			'options' 	=> '\BOTK\Filters::FILTER_VALIDATE_URI',
			'flags'  	=> FILTER_FORCE_ARRAY
			),	
	
		//**************************************************************************
		'businessType'		=> array(		
			'filter'    => FILTER_DEFAULT,
			'flags'  	=> FILTER_FORCE_ARRAY
			),
		'taxID'				=> array(	
			'filter'    => FILTER_CALLBACK,
			'options' 	=> '\BOTK\Filters::FILTER_SANITIZE_TOKEN',
			'flags'  	=> FILTER_REQUIRE_SCALAR
			),
		'vatID'				=> array(	// italian rules
			'filter'    => FILTER_VALIDATE_REGEXP,
			'options' 	=> array('regexp'=>'/^[0-9]{11}$/'),
			'flags'  	=> FILTER_REQUIRE_SCALAR
			),
		'legalName'			=> array(
			'filter'    => FILTER_CALLBACK,
			'options' 	=> '\BOTK\Filters::FILTER_SANITIZE_ADDRESS',
			'flags'  	=> FILTER_REQUIRE_SCALAR
			),
		'businessName'		=> array(
			'filter'    => FILTER_DEFAULT,
			'flags'  	=> FILTER_FORCE_ARRAY
			),
		'addressDescription'=> array(	//	
			'filter'    => FILTER_CALLBACK,
			'options' 	=> '\BOTK\Filters::FILTER_SANITIZE_ADDRESS',
			'flags'  	=> FILTER_REQUIRE_SCALAR
			),
		'addressCountry'	=> array(
			'default'	=> 'IT',		
			'filter'    => FILTER_VALIDATE_REGEXP,
			'options' 	=> array('regexp'=>'/^[A-Z]{2}$/'),
			'flags'  	=> FILTER_REQUIRE_SCALAR
			),
		'addressLocality'	=> array(	
			'filter'    => FILTER_CALLBACK,
			'options' 	=> '\BOTK\Filters::FILTER_SANITIZE_ADDRESS',
			'flags'  	=> FILTER_REQUIRE_SCALAR
			),
		'addressRegion'		=> array(	
			'filter'    => FILTER_CALLBACK,
			'options' 	=> '\BOTK\Filters::FILTER_SANITIZE_ADDRESS',
			'flags'  	=> FILTER_REQUIRE_SCALAR
			),
		'streetAddress'		=> array(	
			'filter'    => FILTER_CALLBACK,
			'options' 	=> '\BOTK\Filters::FILTER_SANITIZE_ADDRESS',
			'flags'  	=> FILTER_REQUIRE_SCALAR
			),
		'postalCode'		=> array(	// italian rules
			'filter'    => FILTER_VALIDATE_REGEXP,
			'options' 	=> array('regexp'=>'/^[0-9]{5}$/'),
			'flags'  	=> FILTER_REQUIRE_SCALAR
		  ),
	    'addressFrazione'   => array(
	        'filter'    => FILTER_CALLBACK,
	        'options'   => '\BOTK\Filters::FILTER_SANITIZE_ADDRESS',
	        'flags'     => FILTER_REQUIRE_SCALAR
	       ),
	    'addressRipartizioneIstat'      => array(
	        'filter'    => FILTER_CALLBACK,
	        'options'   => '\BOTK\Filters::FILTER_SANITIZE_ADDRESS',
	        'flags'     => FILTER_REQUIRE_SCALAR
	       ),
	    'addressRegioneIstat'      => array(
	        'filter'    => FILTER_CALLBACK,
	        'options'   => '\BOTK\Filters::FILTER_SANITIZE_ADDRESS',
	        'flags'     => FILTER_REQUIRE_SCALAR
	       ),
		'telephone'			=> array(	
			'filter'    => FILTER_CALLBACK,	
			'options' 	=> '\BOTK\Filters::FILTER_SANITIZE_TELEPHONE',
			'flags'  	=> FILTER_FORCE_ARRAY
			),
		'faxNumber'			=> array(	
			'filter'    => FILTER_CALLBACK,
			'options' 	=> '\BOTK\Filters::FILTER_SANITIZE_TELEPHONE',
			'flags'  	=> FILTER_FORCE_ARRAY
			),
		'email'				=> array(	
			'filter'    => FILTER_CALLBACK,
			'options' 	=> '\BOTK\Filters::FILTER_SANITIZE_EMAIL',
			'flags'  	=> FILTER_FORCE_ARRAY
			),
		'lat'				=> array( 
			'filter'    => FILTER_CALLBACK,
			'options' 	=> '\BOTK\Filters::FILTER_SANITIZE_GEO'
			),
		'long'				=> array( 
			'filter'    => FILTER_CALLBACK,
			'options' 	=> '\BOTK\Filters::FILTER_SANITIZE_GEO'
			),
		'similarStreet'		=> array(	
			'filter'    => FILTER_CALLBACK,
			'options' 	=> '\BOTK\Filters::FILTER_VALIDATE_URI',
			'flags'  	=> FILTER_FORCE_ARRAY
			),
		'hasMap'			=> array(	
			'filter'    => FILTER_CALLBACK,
			'options' 	=> '\BOTK\Filters::FILTER_SANITIZE_HTTP_URL',
			'flags'  	=> FILTER_FORCE_ARRAY
			),
		'aggregateRatingValue'	=> array(	
			'filter'    => FILTER_VALIDATE_FLOAT,
			'flags'  	=> FILTER_REQUIRE_SCALAR
			),
		'openingHours'		   => array(	
			'filter'    => FILTER_DEFAULT,
			'flags'  	=> FILTER_REQUIRE_SCALAR
			),
		'near'				=> array(	
			'filter'    => FILTER_CALLBACK,
			'options' 	=> '\BOTK\Filters::FILTER_VALIDATE_URI',
			'flags'  	=> FILTER_FORCE_ARRAY
			),	
		'numberOfEmployees'	  => array(	
			'filter'    => FILTER_CALLBACK,
			'options' 	=> '\BOTK\Filters::FILTER_SANITIZE_RANGE',
			'flags'  	=> FILTER_REQUIRE_SCALAR
			),
		'annualTurnover'	 => array(	
			'filter'    => FILTER_CALLBACK,
			'options' 	=> '\BOTK\Filters::FILTER_SANITIZE_RANGE',
			'flags'  	=> FILTER_REQUIRE_SCALAR
			),
		'ateco2007'			=> array(	
			'filter'    => FILTER_VALIDATE_REGEXP,
			'options' 	=> array('regexp'=>'/^[0-9]{6}$/'),
			'flags'  	=> FILTER_REQUIRE_SCALAR
			),
		'ebitda'			=> array(	
			'filter'    => FILTER_CALLBACK,
			'options' 	=> '\BOTK\Filters::FILTER_SANITIZE_RANGE',
			'flags'  	=> FILTER_REQUIRE_SCALAR
			),
		'netProfit'			=> array(	
			'filter'    => FILTER_CALLBACK,
			'options' 	=> '\BOTK\Filters::FILTER_SANITIZE_RANGE',
			'flags'  	=> FILTER_REQUIRE_SCALAR
			),
		'naceV2'			=> array(	
			'filter'    => FILTER_VALIDATE_REGEXP,
			'options' 	=> array('regexp'=>'/^[0-9]{2}[.]?[0-9]{1,2}$/'),
			'flags'  	=> FILTER_FORCE_ARRAY
			),			
		'isicV4'	=> array(	
			'filter'    => FILTER_VALIDATE_REGEXP,
			'options' 	=> array('regexp'=>'/^[0-9]{4}$/'),
			'flags'  	=> FILTER_FORCE_ARRAY
			),
		'hasTotDevelopers'	 => array(	
			'filter'    => FILTER_CALLBACK,
			'options' 	=> '\BOTK\Filters::FILTER_SANITIZE_RANGE',
			'flags'  	=> FILTER_REQUIRE_SCALAR
			),
		'parentOrganization'	 => array(	
			'filter'    => FILTER_CALLBACK,
			'options' 	=> '\BOTK\Filters::FILTER_VALIDATE_URI',
			'flags'  	=> FILTER_FORCE_ARRAY,
			),
		'hasITEmployees'	 => array(	
			'filter'    => FILTER_CALLBACK,
			'options' 	=> '\BOTK\Filters::FILTER_SANITIZE_RANGE',
			'flags'  	=> FILTER_REQUIRE_SCALAR
			),
		'hasNumberOfPCs'	 => array(	
			'filter'    => FILTER_CALLBACK,
			'options' 	=> '\BOTK\Filters::FILTER_SANITIZE_RANGE',
			'flags'  	=> FILTER_REQUIRE_SCALAR
			),
		'hasITBudget'	 => array(	
			'filter'    => FILTER_CALLBACK,
			'options' 	=> '\BOTK\Filters::FILTER_SANITIZE_RANGE',
			'flags'  	=> FILTER_REQUIRE_SCALAR
			),
		'hasTablets'	 => array(	
			'filter'    => FILTER_CALLBACK,
			'options' 	=> '\BOTK\Filters::FILTER_SANITIZE_RANGE',
			'flags'  	=> FILTER_REQUIRE_SCALAR
			),
		'hasWorkstations'	 => array(	
			'filter'    => FILTER_CALLBACK,
			'options' 	=> '\BOTK\Filters::FILTER_SANITIZE_RANGE',
			'flags'  	=> FILTER_REQUIRE_SCALAR
			),
		'hasStorageBudget'	 => array(	
			'filter'    => FILTER_CALLBACK,
			'options' 	=> '\BOTK\Filters::FILTER_SANITIZE_RANGE',
			'flags'  	=> FILTER_REQUIRE_SCALAR
			),
		'hasServerBudget'	 => array(	
			'filter'    => FILTER_CALLBACK,
			'options' 	=> '\BOTK\Filters::FILTER_SANITIZE_RANGE',
			'flags'  	=> FILTER_REQUIRE_SCALAR
			),
		'hasServers'	 => array(	
			'filter'    => FILTER_CALLBACK,
			'options' 	=> '\BOTK\Filters::FILTER_SANITIZE_RANGE',
			'flags'  	=> FILTER_REQUIRE_SCALAR
			),
		'hasDesktop'	 => array(	
			'filter'    => FILTER_CALLBACK,
			'options' 	=> '\BOTK\Filters::FILTER_SANITIZE_RANGE',
			'flags'  	=> FILTER_REQUIRE_SCALAR
			),
		'hasLaptops'	 => array(	
			'filter'    => FILTER_CALLBACK,
			'options' 	=> '\BOTK\Filters::FILTER_SANITIZE_RANGE',
			'flags'  	=> FILTER_REQUIRE_SCALAR
			),
		'hasPrinters'	 => array(	
			'filter'    => FILTER_CALLBACK,
			'options' 	=> '\BOTK\Filters::FILTER_SANITIZE_RANGE',
			'flags'  	=> FILTER_REQUIRE_SCALAR
			),
		'hasMultifunctionPrinters'	 => array(	
			'filter'    => FILTER_CALLBACK,
			'options' 	=> '\BOTK\Filters::FILTER_SANITIZE_RANGE',
			'flags'  	=> FILTER_REQUIRE_SCALAR
			),
		'hasColorPrinter'	 => array(	
			'filter'    => FILTER_CALLBACK,
			'options' 	=> '\BOTK\Filters::FILTER_SANITIZE_RANGE',
			'flags'  	=> FILTER_REQUIRE_SCALAR
			),
		'hasInternetUsers'	 => array(	
			'filter'    => FILTER_CALLBACK,
			'options' 	=> '\BOTK\Filters::FILTER_SANITIZE_RANGE',
			'flags'  	=> FILTER_REQUIRE_SCALAR
			),
		'hasWirelessUsers'	 => array(	
			'filter'    => FILTER_CALLBACK,
			'options' 	=> '\BOTK\Filters::FILTER_SANITIZE_RANGE',
			'flags'  	=> FILTER_REQUIRE_SCALAR
			),
		'hasNetworkLines'	 => array(	
			'filter'    => FILTER_CALLBACK,
			'options' 	=> '\BOTK\Filters::FILTER_SANITIZE_RANGE',
			'flags'  	=> FILTER_REQUIRE_SCALAR
			),
		'hasRouters'	 => array(	
			'filter'    => FILTER_CALLBACK,
			'options' 	=> '\BOTK\Filters::FILTER_SANITIZE_RANGE',
			'flags'  	=> FILTER_REQUIRE_SCALAR
			),
		'hasStorageCapacity'	 => array(	
			'filter'    => FILTER_CALLBACK,
			'options' 	=> '\BOTK\Filters::SANITIZE_STORAGE_CAPACITY',
			'flags'  	=> FILTER_REQUIRE_SCALAR
			),
		'hasExtensions'	 => array(	
			'filter'    => FILTER_CALLBACK,
			'options' 	=> '\BOTK\Filters::FILTER_SANITIZE_RANGE',
			'flags'  	=> FILTER_REQUIRE_SCALAR
			),
		'hasTotCallCenterCallers'	 => array(	
			'filter'    => FILTER_CALLBACK,
			'options' 	=> '\BOTK\Filters::FILTER_SANITIZE_RANGE',
			'flags'  	=> FILTER_REQUIRE_SCALAR
			),
		'hasThinPC'	 => array(	
			'filter'    => FILTER_CALLBACK,
			'options' 	=> '\BOTK\Filters::FILTER_SANITIZE_RANGE',
			'flags'  	=> FILTER_REQUIRE_SCALAR
			),
		'hasSalesforce'	 => array(	
			'filter'    => FILTER_CALLBACK,
			'options' 	=> '\BOTK\Filters::FILTER_SANITIZE_RANGE',
			'flags'  	=> FILTER_REQUIRE_SCALAR
			),
		'hasRevenue'	 => array(	
			'filter'    => FILTER_CALLBACK,
			'options' 	=> '\BOTK\Filters::FILTER_SANITIZE_RANGE',
			'flags'  	=> FILTER_REQUIRE_SCALAR
			),
		'hasCommercialBudget'	 => array(	
			'filter'    => FILTER_CALLBACK,
			'options' 	=> '\BOTK\Filters::FILTER_SANITIZE_RANGE',
			'flags'  	=> FILTER_REQUIRE_SCALAR
			),
		'hasHardwareBudget'	 => array(	
			'filter'    => FILTER_CALLBACK,
			'options' 	=> '\BOTK\Filters::FILTER_SANITIZE_RANGE',
			'flags'  	=> FILTER_REQUIRE_SCALAR
			),
		'hasSoftwareBudget'	 => array(	
			'filter'    => FILTER_CALLBACK,
			'options' 	=> '\BOTK\Filters::FILTER_SANITIZE_RANGE',
			'flags'  	=> FILTER_REQUIRE_SCALAR
			),
		'hasOutsrcingBudget'	 => array(	
			'filter'    => FILTER_CALLBACK,
			'options' 	=> '\BOTK\Filters::FILTER_SANITIZE_RANGE',
			'flags'  	=> FILTER_REQUIRE_SCALAR
			),
		'hasOtherHardwareBudget'	 => array(	
			'filter'    => FILTER_CALLBACK,
			'options' 	=> '\BOTK\Filters::FILTER_SANITIZE_RANGE',
			'flags'  	=> FILTER_REQUIRE_SCALAR
			),
		'hasPCBudget'	 => array(	
			'filter'    => FILTER_CALLBACK,
			'options' 	=> '\BOTK\Filters::FILTER_SANITIZE_RANGE',
			'flags'  	=> FILTER_REQUIRE_SCALAR
			),
		'hasPrinterBudget'	 => array(	
			'filter'    => FILTER_CALLBACK,
			'options' 	=> '\BOTK\Filters::FILTER_SANITIZE_RANGE',
			'flags'  	=> FILTER_REQUIRE_SCALAR
			),
		'hasTerminalBudget'	 => array(	
			'filter'    => FILTER_CALLBACK,
			'options' 	=> '\BOTK\Filters::FILTER_SANITIZE_RANGE',
			'flags'  	=> FILTER_REQUIRE_SCALAR
			),
		'hasPeripheralBudget'	 => array(	
			'filter'    => FILTER_CALLBACK,
			'options' 	=> '\BOTK\Filters::FILTER_SANITIZE_RANGE',
			'flags'  	=> FILTER_REQUIRE_SCALAR
			),
		'hasDesktopPrinters'	 => array(	
			'filter'    => FILTER_CALLBACK,
			'options' 	=> '\BOTK\Filters::FILTER_SANITIZE_RANGE',
			'flags'  	=> FILTER_REQUIRE_SCALAR
			),
		'hasNetworkPrinters'	 => array(	
			'filter'    => FILTER_CALLBACK,
			'options' 	=> '\BOTK\Filters::FILTER_SANITIZE_RANGE',
			'flags'  	=> FILTER_REQUIRE_SCALAR
			),
		'hasSmartphoneUsers'	 => array(	
			'filter'    => FILTER_CALLBACK,
			'options' 	=> '\BOTK\Filters::FILTER_SANITIZE_RANGE',
			'flags'  	=> FILTER_REQUIRE_SCALAR
			),
		'hasEnterpriseSmartphoneUsers'	 => array(	
			'filter'    => FILTER_CALLBACK,
			'options' 	=> '\BOTK\Filters::FILTER_SANITIZE_RANGE',
			'flags'  	=> FILTER_REQUIRE_SCALAR
			),
		'hasServerManufacturer'	 => array(	
			'filter'    => FILTER_DEFAULT,
			'flags'  	=> FILTER_FORCE_ARRAY
			),
		'hasServerVirtualizationManufacturer'	 => array(	
			'filter'    => FILTER_DEFAULT,
			'flags'  	=> FILTER_FORCE_ARRAY
			),
		'hasDASManufacturer'	 => array(	
			'filter'    => FILTER_DEFAULT,
			'flags'  	=> FILTER_FORCE_ARRAY
			),
		'hasNASManufacturer'	 => array(	
			'filter'    => FILTER_DEFAULT,
			'flags'  	=> FILTER_FORCE_ARRAY
			),
		'hasSANManufacturer'	 => array(	
			'filter'    => FILTER_DEFAULT,
			'flags'  	=> FILTER_FORCE_ARRAY
			),
		'hasTapeLibraryManufacturer'	 => array(	
			'filter'    => FILTER_DEFAULT,
			'flags'  	=> FILTER_FORCE_ARRAY
			),
		'hasStorageVirtualizationManufacturer'	 => array(	
			'filter'    => FILTER_DEFAULT,
			'flags'  	=> FILTER_FORCE_ARRAY
			),
		'naics'	 => array(	
			'filter'    => FILTER_DEFAULT,
			'flags'  	=> FILTER_FORCE_ARRAY
			),
		'hasNAFCode'	 => array(	
			'filter'    => FILTER_DEFAULT,
			'flags'  	=> FILTER_FORCE_ARRAY
			),
		'hasServerSeries'	 => array(	
			'filter'    => FILTER_DEFAULT,
			'flags'  	=> FILTER_FORCE_ARRAY
			),
		'hasDesktopManufacturer'	 => array(	
			'filter'    => FILTER_DEFAULT,
			'flags'  	=> FILTER_FORCE_ARRAY
			),
		'hasLaptopManufacturer'	 => array(	
			'filter'    => FILTER_DEFAULT,
			'flags'  	=> FILTER_FORCE_ARRAY
			),
		'hasDesktopVirtualizationManufacturer'	 => array(	
			'filter'    => FILTER_DEFAULT,
			'flags'  	=> FILTER_FORCE_ARRAY
			),
		'hasWorkstationManufacturer'	 => array(	
			'filter'    => FILTER_DEFAULT,
			'flags'  	=> FILTER_FORCE_ARRAY
			),
		'hasNetworkPrinterManufacturer'	 => array(	
			'filter'    => FILTER_DEFAULT,
			'flags'  	=> FILTER_FORCE_ARRAY
			),
		'hasHighVolumePrinterManufacturer'	 => array(	
			'filter'    => FILTER_DEFAULT,
			'flags'  	=> FILTER_FORCE_ARRAY
			),
		'hasCopierManufacturer'	 => array(	
			'filter'    => FILTER_DEFAULT,
			'flags'  	=> FILTER_FORCE_ARRAY
			),
		'hasUPSManufacturer'	 => array(	
			'filter'    => FILTER_DEFAULT,
			'flags'  	=> FILTER_FORCE_ARRAY
			),
		'hasERPSuiteVendor'	 => array(	
			'filter'    => FILTER_DEFAULT,
			'flags'  	=> FILTER_FORCE_ARRAY
			),
		'hasERPSoftwareasaServiceManufacturer'	 => array(	
			'filter'    => FILTER_DEFAULT,
			'flags'  	=> FILTER_FORCE_ARRAY
			),
		'hasAppServerSoftwareVendor'	 => array(	
			'filter'    => FILTER_DEFAULT,
			'flags'  	=> FILTER_FORCE_ARRAY
			),
		'hasBusIntellSoftwareVendor'	 => array(	
			'filter'    => FILTER_DEFAULT,
			'flags'  	=> FILTER_FORCE_ARRAY
			),
		'hasCollaborativeSoftwareVendor'	 => array(	
			'filter'    => FILTER_DEFAULT,
			'flags'  	=> FILTER_FORCE_ARRAY
			),
		'hasCRMSoftwareVendor'	 => array(	
			'filter'    => FILTER_DEFAULT,
			'flags'  	=> FILTER_FORCE_ARRAY
			),
		'hasCRMSoftwareasaServiceManufacturer'	 => array(	
			'filter'    => FILTER_DEFAULT,
			'flags'  	=> FILTER_FORCE_ARRAY
			),
		'hasDocumentMgmtSoftwareVendor'	 => array(	
			'filter'    => FILTER_DEFAULT,
			'flags'  	=> FILTER_FORCE_ARRAY
			),
		'hasAppConsolidationSoftwareVendor'	 => array(	
			'filter'    => FILTER_DEFAULT,
			'flags'  	=> FILTER_FORCE_ARRAY
			),
		'hasHumanResourceSoftwareVendor'	 => array(	
			'filter'    => FILTER_DEFAULT,
			'flags'  	=> FILTER_FORCE_ARRAY
			),
		'hasSupplyChainSoftwareVendor'	 => array(	
			'filter'    => FILTER_DEFAULT,
			'flags'  	=> FILTER_FORCE_ARRAY
			),
		'hasWebServiceSoftwareVendor'	 => array(	
			'filter'    => FILTER_DEFAULT,
			'flags'  	=> FILTER_FORCE_ARRAY
			),
		'hasDatawarehouseSoftwareVendor'	 => array(	
			'filter'    => FILTER_DEFAULT,
			'flags'  	=> FILTER_FORCE_ARRAY
			),
		'hasSaaSVendor'	 => array(	
			'filter'    => FILTER_DEFAULT,
			'flags'  	=> FILTER_FORCE_ARRAY
			),
		'hasEmailMessagingVendor'	 => array(	
			'filter'    => FILTER_DEFAULT,
			'flags'  	=> FILTER_FORCE_ARRAY
			),
		'hasEmailSaaSManufacturer'	 => array(	
			'filter'    => FILTER_DEFAULT,
			'flags'  	=> FILTER_FORCE_ARRAY
			),
		'hasOSVendor'	 => array(	
			'filter'    => FILTER_DEFAULT,
			'flags'  	=> FILTER_FORCE_ARRAY
			),
		'hasOSModel'	 => array(	
			'filter'    => FILTER_DEFAULT,
			'flags'  	=> FILTER_FORCE_ARRAY
			),
		'hasDBMSVendor'	 => array(	
			'filter'    => FILTER_DEFAULT,
			'flags'  	=> FILTER_FORCE_ARRAY
			),
		'hasAcctingVendor'	 => array(	
			'filter'    => FILTER_DEFAULT,
			'flags'  	=> FILTER_FORCE_ARRAY
			),
		'hasAntiVirusVendor'	 => array(	
			'filter'    => FILTER_DEFAULT,
			'flags'  	=> FILTER_FORCE_ARRAY
			),
		'hasAssetManagementSoftwareVendor'	 => array(	
			'filter'    => FILTER_DEFAULT,
			'flags'  	=> FILTER_FORCE_ARRAY
			),
		'hasEnterpriseManagementSoftwareVendor'	 => array(	
			'filter'    => FILTER_DEFAULT,
			'flags'  	=> FILTER_FORCE_ARRAY
			),
		'hasIDAccessSoftwareVendor'	 => array(	
			'filter'    => FILTER_DEFAULT,
			'flags'  	=> FILTER_FORCE_ARRAY
			),
		'hasStorageManagementSoftwareVendor'	 => array(	
			'filter'    => FILTER_DEFAULT,
			'flags'  	=> FILTER_FORCE_ARRAY
			),
		'hasStorageSaaSManufacturer'	 => array(	
			'filter'    => FILTER_DEFAULT,
			'flags'  	=> FILTER_FORCE_ARRAY
			),
		'hasEthernetTechnology'	 => array(	
			'filter'    => FILTER_DEFAULT,
			'flags'  	=> FILTER_FORCE_ARRAY
			),
		'haseCommerceType'	 => array(	
			'filter'    => FILTER_DEFAULT,
			'flags'  	=> FILTER_FORCE_ARRAY
			),
		'hasHostorRemoteStatus'	 => array(	
			'filter'    => FILTER_DEFAULT,
			'flags'  	=> FILTER_FORCE_ARRAY
			),
		'hasNetworkLineCarrier'	 => array(	
			'filter'    => FILTER_DEFAULT,
			'flags'  	=> FILTER_FORCE_ARRAY
			),
		'hasVideoConfServicesProvider'	 => array(	
			'filter'    => FILTER_DEFAULT,
			'flags'  	=> FILTER_FORCE_ARRAY
			),
		'hasUnifiedCommSvcProvider'	 => array(	
			'filter'    => FILTER_DEFAULT,
			'flags'  	=> FILTER_FORCE_ARRAY
			),
		'hasRouterManufacturer'	 => array(	
			'filter'    => FILTER_DEFAULT,
			'flags'  	=> FILTER_FORCE_ARRAY
			),
		'hasSwitchManufacturer'	 => array(	
			'filter'    => FILTER_DEFAULT,
			'flags'  	=> FILTER_FORCE_ARRAY
			),
		'hasVPNManufacturer'	 => array(	
			'filter'    => FILTER_DEFAULT,
			'flags'  	=> FILTER_FORCE_ARRAY
			),
		'hasISP'	 => array(	
			'filter'    => FILTER_DEFAULT,
			'flags'  	=> FILTER_FORCE_ARRAY
			),
		'hasNetworkServiceProvider'	 => array(	
			'filter'    => FILTER_DEFAULT,
			'flags'  	=> FILTER_FORCE_ARRAY
			),
		'hasPhoneSystemManufacturer'	 => array(	
			'filter'    => FILTER_DEFAULT,
			'flags'  	=> FILTER_FORCE_ARRAY
			),
		'hasVoIPManufacturer'	 => array(	
			'filter'    => FILTER_DEFAULT,
			'flags'  	=> FILTER_FORCE_ARRAY
			),
		'hasVoIPHosting'	 => array(	
			'filter'    => FILTER_DEFAULT,
			'flags'  	=> FILTER_FORCE_ARRAY
			),
		'hasLongDistanceCarrier'	 => array(	
			'filter'    => FILTER_DEFAULT,
			'flags'  	=> FILTER_FORCE_ARRAY
			),
		'hasWirelessProvider'	 => array(	
			'filter'    => FILTER_DEFAULT,
			'flags'  	=> FILTER_FORCE_ARRAY
			),
		'hasPhoneSystemMaintenanceProvider'	 => array(	
			'filter'    => FILTER_DEFAULT,
			'flags'  	=> FILTER_FORCE_ARRAY
			),
		'hasSmartphoneManufacturer'	 => array(	
			'filter'    => FILTER_DEFAULT,
			'flags'  	=> FILTER_FORCE_ARRAY
			),
		'hasSmartphoneOS'	 => array(	
			'filter'    => FILTER_DEFAULT,
			'flags'  	=> FILTER_FORCE_ARRAY
			),
		'hasFYE'	 => array(	
			'filter'    => FILTER_VALIDATE_REGEXP,
			'options' 	=> array('regexp'=>'/^[A-Z]{3}$/'),
			'flags'  	=> FILTER_REQUIRE_SCALAR
			),
		'foundingDate'	 => array(
			'filter'    => FILTER_CALLBACK,
			'options' 	=> '\BOTK\Filters::FILTER_SANITIZE_DATETIME',
			'flags'  	=> FILTER_REQUIRE_SCALAR,
			),		
		);

	$localBusiness = BOTK\Model\LocalBusiness::fromArray(array());
	$this->assertEquals($expectedOptions, $localBusiness->getOptions());
}




    /**
     * @dataProvider goodRdf
     */	
    public function testRdfGeneration($data, $rdf, $tripleCount)
    {
    	$localBusiness = BOTK\Model\LocalBusiness::fromArray($data);		
    	$this->assertEquals($rdf, $localBusiness->asTurtleFragment());
    	$this->assertEquals($tripleCount, $localBusiness->getTripleCount());
    }
    public function goodRdf()
    {
    	return array(
    		array( 
    			array('uri'=>'urn:test:a'),
    			'<urn:test:a> a schema:LocalBusiness;schema:address <urn:test:a_address>. <urn:test:a_address> schema:addressCountry "IT"; a schema:PostalAddress.',
    			4,
    			),
    		array(
    			array(
    				'base'				=> 'urn:test:',
    				'id'				=> 'b',
    				'vatID'				=> '01234567890',
    				'legalName'			=> 'Calenda chiodi snc',
                    'hasTablets'        => '123',
                    'naics'             => '456'
    				),
    			'<urn:test:b> dct:identifier "b".<urn:test:b> a schema:LocalBusiness;botk:hasTablets <urn:test:123>;schema:vatID "01234567890";schema:legalName "CALENDA CHIODI SNC";botk:naics "456";schema:address <urn:test:b_address>. <urn:test:b_address> schema:addressCountry "IT"; a schema:PostalAddress.<urn:test:123> schema:value 123 .',
    			10,
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
    			'<urn:local:1234567890> foaf:page <http://linkeddata.center/>;dct:identifier "1234567890".<urn:local:1234567890> a schema:LocalBusiness;schema:email "ADMIN@FAGNONI.COM";a "schema:MedicalOrganization";schema:vatID "01234567890";schema:taxID "FGNNRC63S06F205A";schema:legalName "EXAMPLE SRL";schema:alternateName "Example";schema:telephone "3356382949";schema:faxNumber "3356382949";schema:address <urn:local:1234567890_address>. <urn:local:1234567890_address> schema:description "VIA F.VALSECCHI, 124 - 23900 LECCO (LC)";schema:streetAddress "VIA FAUSTO VALSECCHI, 124";schema:postalCode "23900";schema:addressLocality "LECCO";schema:addressRegion "LC";schema:addressCountry "IT"; a schema:PostalAddress.<geo:1.12345,2.123456> schema:latitude "1.12345"^^xsd:float;schema:longitude "2.123456"^^xsd:float.',
    			21,
    		),
    	    
    	    array(
    	        array(
    	            'id'				         => '1234567890',
    	            'addressCountry'	         => 'IT',
    	            'addressFrazione'	         => 'frazione',
    	            'addressRegioneIstat'        => 'lombardia',
    	            'addressRipartizioneIstat'	 => 'nord',
    	        ),
    	        '<urn:local:1234567890> dct:identifier "1234567890".<urn:local:1234567890> a schema:LocalBusiness;schema:address <urn:local:1234567890_address>. <urn:local:1234567890_address> schema:addressCountry "IT";botk:addressRegioneIstat "LOMBARDIA";botk:addressRipartizioneIstat "NORD";botk:addressFrazione "FRAZIONE"; a schema:PostalAddress.',
    	        8,
    	    ),
		);
    }



    /**
     * @dataProvider structuredAdresses
     */	
    public function testBuildNormalizedAddress($rawdata, $expectedData)
    {
    	$localBusiness = BOTK\Model\LocalBusiness::fromArray($rawdata);
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
			'numberOfEmployees'	=>  'not a number',
			);
    	$localBusiness = BOTK\Model\LocalBusiness::fromArray($badData);
    	$this->assertEquals(array_keys($badData), $localBusiness->getDroppedFields());
    }

}

