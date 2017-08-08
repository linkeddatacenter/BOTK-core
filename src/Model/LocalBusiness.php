<?php
namespace BOTK\Model;


/**
 * An ibrid class that merge the semantic of schema:organization, schema:place and schema:geo, 
 * it is similar to schema:LocalBusiness.
 * Allows the bulk setup of properties
 */
class LocalBusiness extends AbstractModel implements \BOTK\ModelInterface 
{

	protected static $DEFAULT_OPTIONS = array (
		'businessType'		=> array(		
								// additional types  as extension of schema:LocalBusiness
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
								// a schema:alternateName for schema:PostalAddress
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
		'similarName'		=> array(	
			'filter'    => FILTER_CALLBACK,
			'options' 	=> '\BOTK\Filters::FILTER_VALIDATE_URI',
			'flags'  	=> FILTER_FORCE_ARRAY
			),		
		'numberOfEmployees'	  => array(	
			'filter'    => FILTER_VALIDATE_REGEXP,
			'options' 	=> array('regexp'=>'/^[0-9]+\s*-?\s*[0-9]*$/'),
			'flags'  	=> FILTER_REQUIRE_SCALAR
			),
		'annualTurnover'	 => array(	
			'filter'    => FILTER_VALIDATE_REGEXP,
			'options' 	=> array('regexp'=>'/^-?[0-9]+\s*-?\s*-?[0-9]*$/'),
			'flags'  	=> FILTER_REQUIRE_SCALAR
			),
		'ateco2007'			=> array(	
			'filter'    => FILTER_VALIDATE_REGEXP,
			'options' 	=> array('regexp'=>'/^[0-9]{6}$/'),
			'flags'  	=> FILTER_REQUIRE_SCALAR
			),
		'ebitda'			=> array(	
			'filter'    => FILTER_VALIDATE_REGEXP,
			'options' 	=> array('regexp'=>'/^-?[0-9]+\s*-?\s*-?[0-9]*$/'),
			'flags'  	=> FILTER_REQUIRE_SCALAR
			),
		'netProfit'			=> array(	
			'filter'    => FILTER_VALIDATE_REGEXP,
			'options' 	=> array('regexp'=>'/^-?[0-9]+\s*-?\s*-?[0-9]*$/'),
			'flags'  	=> FILTER_REQUIRE_SCALAR
			),
		
		'naceV2'			=> array(	
			'filter'    => FILTER_VALIDATE_REGEXP,
			'options' 	=> array('regexp'=>'/^[0-9]{2}[.]?[0-9]{1,2}$/'),
			'flags'  	=> FILTER_REQUIRE_SCALAR
			),			
		/*==========================================6.3.0==========================================*/
		'isicV4'	=> array(	
			'filter'    => FILTER_VALIDATE_REGEXP,
			'options' 	=> array('regexp'=>'/^[0-9]{4}$/'),
			'flags'  	=> FILTER_REQUIRE_SCALAR
			),
		'hasTotDevelopers'	 => array(	
			'filter'    => FILTER_VALIDATE_REGEXP,
			'options' 	=> array('regexp'=>'/^-?[0-9]+\s*-?\s*-?[0-9]*$/'),
			'flags'  	=> FILTER_REQUIRE_SCALAR
			),
		'parentOrganization'	 => array(	
			'filter'    => FILTER_CALLBACK,
			'options' 	=> '\BOTK\Filters::FILTER_VALIDATE_URI',
			'flags'  	=> FILTER_REQUIRE_SCALAR,
			),
		/*==========================================6.3.0 range==========================================*/
		'hasITEmployees'	 => array(	
			'filter'    => FILTER_VALIDATE_REGEXP,
			'options' 	=> array('regexp'=>'/^-?[0-9]+\s*-?\s*-?[0-9]*$/'),
			'flags'  	=> FILTER_REQUIRE_SCALAR
			),
		'hasNumberOfPCs'	 => array(	
			'filter'    => FILTER_VALIDATE_REGEXP,
			'options' 	=> array('regexp'=>'/^-?[0-9]+\s*-?\s*-?[0-9]*$/'),
			'flags'  	=> FILTER_REQUIRE_SCALAR
			),
		'hasITBudget'	 => array(	
			'filter'    => FILTER_VALIDATE_REGEXP,
			'options' 	=> array('regexp'=>'/^-?[0-9]+\s*-?\s*-?[0-9]*$/'),
			'flags'  	=> FILTER_REQUIRE_SCALAR
			),
		'hasTablets'	 => array(	
			'filter'    => FILTER_VALIDATE_REGEXP,
			'options' 	=> array('regexp'=>'/^-?[0-9]+\s*-?\s*-?[0-9]*$/'),
			'flags'  	=> FILTER_REQUIRE_SCALAR
			),
		'hasWorkstations'	 => array(	
			'filter'    => FILTER_VALIDATE_REGEXP,
			'options' 	=> array('regexp'=>'/^-?[0-9]+\s*-?\s*-?[0-9]*$/'),
			'flags'  	=> FILTER_REQUIRE_SCALAR
			),
		'hasStorageBudget'	 => array(	
			'filter'    => FILTER_VALIDATE_REGEXP,
			'options' 	=> array('regexp'=>'/^-?[0-9]+\s*-?\s*-?[0-9]*$/'),
			'flags'  	=> FILTER_REQUIRE_SCALAR
			),
		'hasServerBudget'	 => array(	
			'filter'    => FILTER_VALIDATE_REGEXP,
			'options' 	=> array('regexp'=>'/^-?[0-9]+\s*-?\s*-?[0-9]*$/'),
			'flags'  	=> FILTER_REQUIRE_SCALAR
			),
		'hasServers'	 => array(	
			'filter'    => FILTER_VALIDATE_REGEXP,
			'options' 	=> array('regexp'=>'/^-?[0-9]+\s*-?\s*-?[0-9]*$/'),
			'flags'  	=> FILTER_REQUIRE_SCALAR
			),
		'hasDesktop'	 => array(	
			'filter'    => FILTER_VALIDATE_REGEXP,
			'options' 	=> array('regexp'=>'/^-?[0-9]+\s*-?\s*-?[0-9]*$/'),
			'flags'  	=> FILTER_REQUIRE_SCALAR
			),
		'hasLaptops'	 => array(	
			'filter'    => FILTER_VALIDATE_REGEXP,
			'options' 	=> array('regexp'=>'/^-?[0-9]+\s*-?\s*-?[0-9]*$/'),
			'flags'  	=> FILTER_REQUIRE_SCALAR
			),
		'hasPrinters'	 => array(	
			'filter'    => FILTER_VALIDATE_REGEXP,
			'options' 	=> array('regexp'=>'/^-?[0-9]+\s*-?\s*-?[0-9]*$/'),
			'flags'  	=> FILTER_REQUIRE_SCALAR
			),
		'hasMultifunctionPrinters'	 => array(	
			'filter'    => FILTER_VALIDATE_REGEXP,
			'options' 	=> array('regexp'=>'/^-?[0-9]+\s*-?\s*-?[0-9]*$/'),
			'flags'  	=> FILTER_REQUIRE_SCALAR
			),
		'hasColorPrinter'	 => array(	
			'filter'    => FILTER_VALIDATE_REGEXP,
			'options' 	=> array('regexp'=>'/^-?[0-9]+\s*-?\s*-?[0-9]*$/'),
			'flags'  	=> FILTER_REQUIRE_SCALAR
			),
		'hasInternetUsers'	 => array(	
			'filter'    => FILTER_VALIDATE_REGEXP,
			'options' 	=> array('regexp'=>'/^-?[0-9]+\s*-?\s*-?[0-9]*$/'),
			'flags'  	=> FILTER_REQUIRE_SCALAR
			),
		'hasWirelessUsers'	 => array(	
			'filter'    => FILTER_VALIDATE_REGEXP,
			'options' 	=> array('regexp'=>'/^-?[0-9]+\s*-?\s*-?[0-9]*$/'),
			'flags'  	=> FILTER_REQUIRE_SCALAR
			),
		'hasNetworkLines'	 => array(	
			'filter'    => FILTER_VALIDATE_REGEXP,
			'options' 	=> array('regexp'=>'/^-?[0-9]+\s*-?\s*-?[0-9]*$/'),
			'flags'  	=> FILTER_REQUIRE_SCALAR
			),
		'hasRouters'	 => array(	
			'filter'    => FILTER_VALIDATE_REGEXP,
			'options' 	=> array('regexp'=>'/^-?[0-9]+\s*-?\s*-?[0-9]*$/'),
			'flags'  	=> FILTER_REQUIRE_SCALAR
			),
		'hasStorageCapacity'	 => array(	
			'filter'    => FILTER_VALIDATE_REGEXP,
			'options' 	=> array('regexp'=>'/^-?[0-9]+\s*-?\s*-?[0-9]*$/'),
			'flags'  	=> FILTER_REQUIRE_SCALAR
			),
		'hasExtensions'	 => array(	
			'filter'    => FILTER_VALIDATE_REGEXP,
			'options' 	=> array('regexp'=>'/^-?[0-9]+\s*-?\s*-?[0-9]*$/'),
			'flags'  	=> FILTER_REQUIRE_SCALAR
			),
		'hasTotCallCenterCallers'	 => array(	
			'filter'    => FILTER_VALIDATE_REGEXP,
			'options' 	=> array('regexp'=>'/^-?[0-9]+\s*-?\s*-?[0-9]*$/'),
			'flags'  	=> FILTER_REQUIRE_SCALAR
			),
		'hasThinPC'	 => array(	
			'filter'    => FILTER_VALIDATE_REGEXP,
			'options' 	=> array('regexp'=>'/^-?[0-9]+\s*-?\s*-?[0-9]*$/'),
			'flags'  	=> FILTER_REQUIRE_SCALAR
			),
		'hasSalesforce'	 => array(	
			'filter'    => FILTER_VALIDATE_REGEXP,
			'options' 	=> array('regexp'=>'/^-?[0-9]+\s*-?\s*-?[0-9]*$/'),
			'flags'  	=> FILTER_REQUIRE_SCALAR
			),
		'hasRevenue'	 => array(	
			'filter'    => FILTER_VALIDATE_REGEXP,
			'options' 	=> array('regexp'=>'/^-?[0-9]+\s*-?\s*-?[0-9]*$/'),
			'flags'  	=> FILTER_REQUIRE_SCALAR
			),
		'hasCommercialBudget'	 => array(	
			'filter'    => FILTER_VALIDATE_REGEXP,
			'options' 	=> array('regexp'=>'/^-?[0-9]+\s*-?\s*-?[0-9]*$/'),
			'flags'  	=> FILTER_REQUIRE_SCALAR
			),
		'hasHardwareBudget'	 => array(	
			'filter'    => FILTER_VALIDATE_REGEXP,
			'options' 	=> array('regexp'=>'/^-?[0-9]+\s*-?\s*-?[0-9]*$/'),
			'flags'  	=> FILTER_REQUIRE_SCALAR
			),
		'hasSoftwareBudget'	 => array(	
			'filter'    => FILTER_VALIDATE_REGEXP,
			'options' 	=> array('regexp'=>'/^-?[0-9]+\s*-?\s*-?[0-9]*$/'),
			'flags'  	=> FILTER_REQUIRE_SCALAR
			),
		'hasOutsrcingBudget'	 => array(	
			'filter'    => FILTER_VALIDATE_REGEXP,
			'options' 	=> array('regexp'=>'/^-?[0-9]+\s*-?\s*-?[0-9]*$/'),
			'flags'  	=> FILTER_REQUIRE_SCALAR
			),
		'hasOtherHardwareBudget'	 => array(	
			'filter'    => FILTER_VALIDATE_REGEXP,
			'options' 	=> array('regexp'=>'/^-?[0-9]+\s*-?\s*-?[0-9]*$/'),
			'flags'  	=> FILTER_REQUIRE_SCALAR
			),
		'hasPCBudget'	 => array(	
			'filter'    => FILTER_VALIDATE_REGEXP,
			'options' 	=> array('regexp'=>'/^-?[0-9]+\s*-?\s*-?[0-9]*$/'),
			'flags'  	=> FILTER_REQUIRE_SCALAR
			),
		'hasPrinterBudget'	 => array(	
			'filter'    => FILTER_VALIDATE_REGEXP,
			'options' 	=> array('regexp'=>'/^-?[0-9]+\s*-?\s*-?[0-9]*$/'),
			'flags'  	=> FILTER_REQUIRE_SCALAR
			),
		'hasTerminalBudget'	 => array(	
			'filter'    => FILTER_VALIDATE_REGEXP,
			'options' 	=> array('regexp'=>'/^-?[0-9]+\s*-?\s*-?[0-9]*$/'),
			'flags'  	=> FILTER_REQUIRE_SCALAR
			),
		'hasPeripheralBudget'	 => array(	
			'filter'    => FILTER_VALIDATE_REGEXP,
			'options' 	=> array('regexp'=>'/^-?[0-9]+\s*-?\s*-?[0-9]*$/'),
			'flags'  	=> FILTER_REQUIRE_SCALAR
			),
		'hasDesktopPrinters'	 => array(	
			'filter'    => FILTER_VALIDATE_REGEXP,
			'options' 	=> array('regexp'=>'/^-?[0-9]+\s*-?\s*-?[0-9]*$/'),
			'flags'  	=> FILTER_REQUIRE_SCALAR
			),
		'hasNetworkPrinters'	 => array(	
			'filter'    => FILTER_VALIDATE_REGEXP,
			'options' 	=> array('regexp'=>'/^-?[0-9]+\s*-?\s*-?[0-9]*$/'),
			'flags'  	=> FILTER_REQUIRE_SCALAR
			),
		'hasSmartphoneUsers'	 => array(	
			'filter'    => FILTER_VALIDATE_REGEXP,
			'options' 	=> array('regexp'=>'/^-?[0-9]+\s*-?\s*-?[0-9]*$/'),
			'flags'  	=> FILTER_REQUIRE_SCALAR
			),
		'hasEnterpriseSmartphoneUsers'	 => array(	
			'filter'    => FILTER_VALIDATE_REGEXP,
			'options' 	=> array('regexp'=>'/^-?[0-9]+\s*-?\s*-?[0-9]*$/'),
			'flags'  	=> FILTER_REQUIRE_SCALAR
			),
		/*=======================================6.3.0 string=======================================*/
		'hasServerManufacturer'	 => array(	
			'filter'    => FILTER_DEFAULT,
			'flags'  	=> FILTER_REQUIRE_SCALAR
			),
		'hasServerVirtualizationManufacturer'	 => array(	
			'filter'    => FILTER_DEFAULT,
			'flags'  	=> FILTER_REQUIRE_SCALAR
			),
		'hasDASManufacturer'	 => array(	
			'filter'    => FILTER_DEFAULT,
			'flags'  	=> FILTER_REQUIRE_SCALAR
			),
		'hasNASManufacturer'	 => array(	
			'filter'    => FILTER_DEFAULT,
			'flags'  	=> FILTER_REQUIRE_SCALAR
			),
		'hasSANManufacturer'	 => array(	
			'filter'    => FILTER_DEFAULT,
			'flags'  	=> FILTER_REQUIRE_SCALAR
			),
		'hasTapeLibraryManufacturer'	 => array(	
			'filter'    => FILTER_DEFAULT,
			'flags'  	=> FILTER_REQUIRE_SCALAR
			),
		'hasStorageVirtualizationManufacturer'	 => array(	
			'filter'    => FILTER_DEFAULT,
			'flags'  	=> FILTER_REQUIRE_SCALAR
			),
		'naics'	 => array(	
			'filter'    => FILTER_DEFAULT,
			'flags'  	=> FILTER_REQUIRE_SCALAR
			),
		'hasNAFCode'	 => array(	
			'filter'    => FILTER_DEFAULT,
			'flags'  	=> FILTER_REQUIRE_SCALAR
			),
		'hasServerSeries'	 => array(	
			'filter'    => FILTER_DEFAULT,
			'flags'  	=> FILTER_REQUIRE_SCALAR
			),
		'hasDesktopManufacturer'	 => array(	
			'filter'    => FILTER_DEFAULT,
			'flags'  	=> FILTER_REQUIRE_SCALAR
			),
		'hasLaptopManufacturer'	 => array(	
			'filter'    => FILTER_DEFAULT,
			'flags'  	=> FILTER_REQUIRE_SCALAR
			),
		'hasDesktopVirtualizationManufacturer'	 => array(	
			'filter'    => FILTER_DEFAULT,
			'flags'  	=> FILTER_REQUIRE_SCALAR
			),
		'hasWorkstationManufacturer'	 => array(	
			'filter'    => FILTER_DEFAULT,
			'flags'  	=> FILTER_REQUIRE_SCALAR
			),
		'hasNetworkPrinterManufacturer'	 => array(	
			'filter'    => FILTER_DEFAULT,
			'flags'  	=> FILTER_REQUIRE_SCALAR
			),
		'hasHighVolumePrinterManufacturer'	 => array(	
			'filter'    => FILTER_DEFAULT,
			'flags'  	=> FILTER_REQUIRE_SCALAR
			),
		'hasCopierManufacturer'	 => array(	
			'filter'    => FILTER_DEFAULT,
			'flags'  	=> FILTER_REQUIRE_SCALAR
			),
		'hasUPSManufacturer'	 => array(	
			'filter'    => FILTER_DEFAULT,
			'flags'  	=> FILTER_REQUIRE_SCALAR
			),
		'hasERPSuiteVendor'	 => array(	
			'filter'    => FILTER_DEFAULT,
			'flags'  	=> FILTER_REQUIRE_SCALAR
			),
		'hasERPSoftwareasaServiceManufacturer'	 => array(	
			'filter'    => FILTER_DEFAULT,
			'flags'  	=> FILTER_REQUIRE_SCALAR
			),
		'hasAppServerSoftwareVendor'	 => array(	
			'filter'    => FILTER_DEFAULT,
			'flags'  	=> FILTER_REQUIRE_SCALAR
			),
		'hasBusIntellSoftwareVendor'	 => array(	
			'filter'    => FILTER_DEFAULT,
			'flags'  	=> FILTER_REQUIRE_SCALAR
			),
		'hasCollaborativeSoftwareVendor'	 => array(	
			'filter'    => FILTER_DEFAULT,
			'flags'  	=> FILTER_REQUIRE_SCALAR
			),
		'hasCRMSoftwareVendor'	 => array(	
			'filter'    => FILTER_DEFAULT,
			'flags'  	=> FILTER_REQUIRE_SCALAR
			),
		'hasCRMSoftwareasaServiceManufacturer'	 => array(	
			'filter'    => FILTER_DEFAULT,
			'flags'  	=> FILTER_REQUIRE_SCALAR
			),
		'hasDocumentMgmtSoftwareVendor'	 => array(	
			'filter'    => FILTER_DEFAULT,
			'flags'  	=> FILTER_REQUIRE_SCALAR
			),
		'hasAppConsolidationSoftwareVendor'	 => array(	
			'filter'    => FILTER_DEFAULT,
			'flags'  	=> FILTER_REQUIRE_SCALAR
			),
		'hasHumanResourceSoftwareVendor'	 => array(	
			'filter'    => FILTER_DEFAULT,
			'flags'  	=> FILTER_REQUIRE_SCALAR
			),
		'hasSupplyChainSoftwareVendor'	 => array(	
			'filter'    => FILTER_DEFAULT,
			'flags'  	=> FILTER_REQUIRE_SCALAR
			),
		'hasWebServiceSoftwareVendor'	 => array(	
			'filter'    => FILTER_DEFAULT,
			'flags'  	=> FILTER_REQUIRE_SCALAR
			),
		'hasDatawarehouseSoftwareVendor'	 => array(	
			'filter'    => FILTER_DEFAULT,
			'flags'  	=> FILTER_REQUIRE_SCALAR
			),
		'hasSaaSVendor'	 => array(	
			'filter'    => FILTER_DEFAULT,
			'flags'  	=> FILTER_REQUIRE_SCALAR
			),
		'hasEmailMessagingVendor'	 => array(	
			'filter'    => FILTER_DEFAULT,
			'flags'  	=> FILTER_REQUIRE_SCALAR
			),
		'hasEmailSaaSManufacturer'	 => array(	
			'filter'    => FILTER_DEFAULT,
			'flags'  	=> FILTER_REQUIRE_SCALAR
			),
		'hasOSVendor'	 => array(	
			'filter'    => FILTER_DEFAULT,
			'flags'  	=> FILTER_REQUIRE_SCALAR
			),
		'hasOSModel'	 => array(	
			'filter'    => FILTER_DEFAULT,
			'flags'  	=> FILTER_REQUIRE_SCALAR
			),
		'hasDBMSVendor'	 => array(	
			'filter'    => FILTER_DEFAULT,
			'flags'  	=> FILTER_REQUIRE_SCALAR
			),
		'hasAcctingVendor'	 => array(	
			'filter'    => FILTER_DEFAULT,
			'flags'  	=> FILTER_REQUIRE_SCALAR
			),
		'hasAntiVirusVendor'	 => array(	
			'filter'    => FILTER_DEFAULT,
			'flags'  	=> FILTER_REQUIRE_SCALAR
			),
		'hasAssetManagementSoftwareVendor'	 => array(	
			'filter'    => FILTER_DEFAULT,
			'flags'  	=> FILTER_REQUIRE_SCALAR
			),
		'hasEnterpriseManagementSoftwareVendor'	 => array(	
			'filter'    => FILTER_DEFAULT,
			'flags'  	=> FILTER_REQUIRE_SCALAR
			),
		'hasIDAccessSoftwareVendor'	 => array(	
			'filter'    => FILTER_DEFAULT,
			'flags'  	=> FILTER_REQUIRE_SCALAR
			),
		'hasStorageManagementSoftwareVendor'	 => array(	
			'filter'    => FILTER_DEFAULT,
			'flags'  	=> FILTER_REQUIRE_SCALAR
			),
		'hasStorageSaaSManufacturer'	 => array(	
			'filter'    => FILTER_DEFAULT,
			'flags'  	=> FILTER_REQUIRE_SCALAR
			),
		'hasEthernetTechnology'	 => array(	
			'filter'    => FILTER_DEFAULT,
			'flags'  	=> FILTER_REQUIRE_SCALAR
			),
		'haseCommerceType'	 => array(	
			'filter'    => FILTER_DEFAULT,
			'flags'  	=> FILTER_REQUIRE_SCALAR
			),
		'hasHostorRemoteStatus'	 => array(	
			'filter'    => FILTER_DEFAULT,
			'flags'  	=> FILTER_REQUIRE_SCALAR
			),
		'hasNetworkLineCarrier'	 => array(	
			'filter'    => FILTER_DEFAULT,
			'flags'  	=> FILTER_REQUIRE_SCALAR
			),
		'hasVideoConfServicesProvider'	 => array(	
			'filter'    => FILTER_DEFAULT,
			'flags'  	=> FILTER_REQUIRE_SCALAR
			),
		'hasUnifiedCommSvcProvider'	 => array(	
			'filter'    => FILTER_DEFAULT,
			'flags'  	=> FILTER_REQUIRE_SCALAR
			),
		'hasRouterManufacturer'	 => array(	
			'filter'    => FILTER_DEFAULT,
			'flags'  	=> FILTER_REQUIRE_SCALAR
			),
		'hasSwitchManufacturer'	 => array(	
			'filter'    => FILTER_DEFAULT,
			'flags'  	=> FILTER_REQUIRE_SCALAR
			),
		'hasVPNManufacturer'	 => array(	
			'filter'    => FILTER_DEFAULT,
			'flags'  	=> FILTER_REQUIRE_SCALAR
			),
		'hasISP'	 => array(	
			'filter'    => FILTER_DEFAULT,
			'flags'  	=> FILTER_REQUIRE_SCALAR
			),
		'hasNetworkServiceProvider'	 => array(	
			'filter'    => FILTER_DEFAULT,
			'flags'  	=> FILTER_REQUIRE_SCALAR
			),
		'hasPhoneSystemManufacturer'	 => array(	
			'filter'    => FILTER_DEFAULT,
			'flags'  	=> FILTER_REQUIRE_SCALAR
			),
		'hasVoIPManufacturer'	 => array(	
			'filter'    => FILTER_DEFAULT,
			'flags'  	=> FILTER_REQUIRE_SCALAR
			),
		'hasVoIPHosting'	 => array(	
			'filter'    => FILTER_DEFAULT,
			'flags'  	=> FILTER_REQUIRE_SCALAR
			),
		'hasLongDistanceCarrier'	 => array(	
			'filter'    => FILTER_DEFAULT,
			'flags'  	=> FILTER_REQUIRE_SCALAR
			),
		'hasWirelessProvider'	 => array(	
			'filter'    => FILTER_DEFAULT,
			'flags'  	=> FILTER_REQUIRE_SCALAR
			),
		'hasPhoneSystemMaintenanceProvider'	 => array(	
			'filter'    => FILTER_DEFAULT,
			'flags'  	=> FILTER_REQUIRE_SCALAR
			),
		'hasSmartphoneManufacturer'	 => array(	
			'filter'    => FILTER_DEFAULT,
			'flags'  	=> FILTER_REQUIRE_SCALAR
			),
		'hasSmartphoneOS'	 => array(	
			'filter'    => FILTER_DEFAULT,
			'flags'  	=> FILTER_REQUIRE_SCALAR
			),
		'hasFYE'	 => array(	
			'filter'    => FILTER_VALIDATE_REGEXP,
			'options' 	=> array('regexp'=>'/^[A-Z]{3}$/'),
			'flags'  	=> FILTER_REQUIRE_SCALAR
			)
		);

	/**
	 * Redefine protected constructor to add address description as dynamic property
	 */
	protected function __construct(array $data = array(), array $customOptions = array()) 
	{
		parent::__construct($data, $customOptions);
		$this->addAddressDescription();
	}
	
	
	/**
	 * If not existing, create an address description as a normalized address from following data properties:
	 * 		'addressLocality',
	 * 		'addressRegion',
	 * 		'streetAddress',
	 * 		'postalCode',
	 */
	private function addAddressDescription()
	{	
		extract($this->data);

		if(empty($addressDescription)){
			if( !empty($streetAddress) && ( !empty($addressLocality) || !empty($postalCode))){
				$addressDescription = "$streetAddress ,";
				if(!empty($postalCode)) { $addressDescription.= " $postalCode";}
				if(!empty($addressLocality)) { $addressDescription.= " $addressLocality"; }
				if(!empty($addressRegion)) { $addressDescription.= " ($addressRegion)"; }
			} else {
				$addressDescription = null;
			}
		}
		
		$addressDescription = \BOTK\Filters::FILTER_SANITIZE_ADDRESS($addressDescription);
		
		if(!empty($addressDescription)){
			$this->data['addressDescription']=$addressDescription;
		}
	}
	
	
	public function asTurtleFragment()
	{
		if(is_null($this->rdf)) {
			extract($this->data);

			//die(print_r($this->data, true));

			// create uris
			$organizationUri = $this->getUri();
			$addressUri = $organizationUri.'_address';
			$geoUri = ( !empty($lat) && !empty($long) )?"geo:$lat,$long":null;
			
			$tripleCounter =0;
			$turtleString='';
			
			// define $_ as a macro to write simple rdf
			$_= function($format, $var,$sanitize=true) use(&$turtleString, &$tripleCounter){
				foreach((array)$var as $v){
					if($var){
						$turtleString.= sprintf($format,$sanitize?\BOTK\Filters::FILTER_SANITIZE_TURTLE_STRING($v):$v);
						$tripleCounter++;
					}
				}
			};

	 		// serialize schema:LocalBusiness
			$_('<%s> a schema:LocalBusiness;', $organizationUri);
			!empty($businessType) 		&& $_('a %s;', $businessType);
			!empty($id) 				&& $_('dct:identifier "%s";', $id);
			!empty($vatID) 				&& $_('schema:vatID "%s";', $vatID); 
			!empty($taxtID) 			&& $_('schema:taxtID "%s";', $taxID);
			!empty($legalName)			&& $_('schema:legalName "%s";', $legalName);
			!empty($businessName) 		&& $_('schema:alternateName "%s";', $businessName);
			!empty($telephone) 			&& $_('schema:telephone "%s";', $telephone);
			!empty($faxNumber) 			&& $_('schema:faxNumber "%s";', $faxNumber);
			!empty($openingHours)		&& $_('schema:openingHours "%s";', $openingHours);
			!empty($disambiguatingDescription)&& $_('schema:disambiguatingDescription "%s";', $disambiguatingDescription);

			!empty($ateco2007)			&& $_('botk:ateco2007 "%s";', $ateco2007);
			!empty($naceV2)				&& $_('botk:naceV2 "%s";', $naceV2);
			!empty($isicV4)				&& $_('botk:isicV4 %s;', $isicV4);
			!empty($parentOrganization)	&& $_('schema:parentOrganization <%s>;', $parentOrganization, false);

			!empty($aggregateRatingValue)&& $_('schema:aggregateRating [a schema:AggregateRating; schema:ratingValue "%s"^^xsd:float];', $aggregateRatingValue);
			!empty($page) 				&& $_('foaf:page <%s>;', $page,false);
			!empty($email) 				&& $_('schema:email "%s";', $email);
			!empty($homepage) 			&& $_('foaf:homepage <%s>;', $homepage,false);
			!empty($geoUri) 			&& $_('schema:geo <%s>;', $geoUri,false);
			!empty($hasMap) 			&& $_('schema:hasMap <%s>;', $hasMap,false);
			$_('schema:address <%s>. ', $addressUri);
			
			// serialize schema:PostalAddress 
			$_('<%s> a schema:PostalAddress;', $addressUri);
			!empty($addressDescription) && $_('schema:description "%s";', $addressDescription);
			!empty($streetAddress) 		&& $_('schema:streetAddress "%s";', $streetAddress);
			!empty($postalCode) 		&& $_('schema:postalCode "%s";', $postalCode);
			!empty($addressLocality) 	&& $_('schema:addressLocality "%s";', $addressLocality);
			!empty($addressRegion) 		&& $_('schema:addressRegion "%s";', $addressRegion);			
			$_('schema:addressCountry "%s". ', $addressCountry);

			// serialize schema:GeoCoordinates
			if( !empty($geoUri)){
				$_('<%s> a schema:GeoCoordinates;', $geoUri); 
				$_('wgs:lat "%s"^^xsd:float;', $lat);
				$_('wgs:long "%s"^^xsd:float . ', $long); 
			}
			
			$statVars = array(
				'numberOfEmployees',
				'annualTurnover',
				'ebitda',
				'netProfit',
				/*=======================================v6.3.0================================*/
				'hasTotDevelopers',
				'itBudget',
				'itStorageBudget',
				'itHardwareBudget',
				'itServerBudget',
				'softwareBudget',				
				'hasITEmployees',
				'hasNumberOfPCs',
				'hasITBudget',
				'hasTablets',
				'hasWorkstations',
				'hasStorageBudget',
				'hasServerBudget',
				'hasServers',
				'hasDesktop',
				'hasLaptops',
				'hasPrinters',
				'hasMultifunctionPrinters',
				'hasColorPrinter',
				'hasInternetUsers',
				'hasWirelessUsers',
				'hasNetworkLines',
				'hasRouters',
				'hasStorageCapacity',
				'hasExtensions',
				'hasTotCallCenterCallers',
				'hasThinPC',
				'hasSalesforce',
				'hasRevenue',
				'hasCommercialBudget',
				'hasHardwareBudget',
				'hasSoftwareBudget',
				'hasOutsrcingBudget',
				'hasOtherHardwareBudget',
				'hasPCBudget',
				'hasPrinterBudget',
				'hasTerminalBudget',
				'hasPeripheralBudget',
				'hasDesktopPrinters',
				'hasNetworkPrinters',
				'hasSmartphoneUsers',
				'hasEnterpriseSmartphoneUsers'				
				);
			
			/*
			accepted format : 123, 123-456, 0-123, 123-+, 123-inf, 123-PHP_INT_MAX
			*/
			foreach ( $statVars as $statVar){
				if(!empty($this->data[$statVar]) && preg_match('/^(-?[0-9]+)\s*-?\s*(inf|[+]|-?[0-9]*)$/', $this->data[$statVar], $matches)){
					$statUri =  $organizationUri.'_'.$statVar;

					$_("<$organizationUri> botk:$statVar <%s> .", $statUri, false);	

					$_('<%s> a schema:QuantitativeValue, botk:EstimatedRange;', $statUri);
					$minValue =  (int) $matches[1];
					$maxValue = empty($matches[2])? $minValue : $matches[2];
					if($maxValue == 'inf' || $maxValue == '+' || $maxValue == PHP_INT_MAX){
						$_('schema:minValue %s .', $minValue);
					}else{
						$_('schema:minValue %s ;', $minValue);
						$_('schema:maxValue %s .', $maxValue);
					}
				}		
			}


			$stringVars= array(
				/*=======================================v6.3.0================================*/
				'hasServerManufacturer',
				'hasServerVirtualizationManufacturer',
				'hasDASManufacturer',
				'hasNASManufacturer',
				'hasSANManufacturer',
				'hasTapeLibraryManufacturer',
				'hasStorageVirtualizationManufacturer',
				'naics',
				'hasNAFCode',
				'hasServerSeries',
				'hasDesktopManufacturer',
				'hasLaptopManufacturer',
				'hasDesktopVirtualizationManufacturer',
				'hasWorkstationManufacturer',
				'hasNetworkPrinterManufacturer',
				'hasHighVolumePrinterManufacturer',
				'hasCopierManufacturer',
				'hasUPSManufacturer',
				'hasERPSuiteVendor',
				'hasERPSoftwareasaServiceManufacturer',
				'hasAppServerSoftwareVendor',
				'hasBusIntellSoftwareVendor',
				'hasCollaborativeSoftwareVendor',
				'hasCRMSoftwareVendor',
				'hasCRMSoftwareasaServiceManufacturer',
				'hasDocumentMgmtSoftwareVendor',
				'hasAppConsolidationSoftwareVendor',
				'hasHumanResourceSoftwareVendor',
				'hasSupplyChainSoftwareVendor',
				'hasWebServiceSoftwareVendor',
				'hasDatawarehouseSoftwareVendor',
				'hasSaaSVendor',
				'hasEmailMessagingVendor',
				'hasEmailSaaSManufacturer',
				'hasOSVendor',
				'hasOSModel',
				'hasDBMSVendor',
				'hasAcctingVendor',
				'hasAntiVirusVendor',
				'hasAssetManagementSoftwareVendor',
				'hasEnterpriseManagementSoftwareVendor',
				'hasIDAccessSoftwareVendor',
				'hasStorageManagementSoftwareVendor',
				'hasStorageSaaSManufacturer',
				'hasEthernetTechnology',
				'haseCommerceType',
				'hasHostorRemoteStatus',
				'hasNetworkLineCarrier',
				'hasVideoConfServicesProvider',
				'hasUnifiedCommSvcProvider',
				'hasRouterManufacturer',
				'hasSwitchManufacturer',
				'hasVPNManufacturer',
				'hasISP',
				'hasNetworkServiceProvider',
				'hasPhoneSystemManufacturer',
				'hasVoIPManufacturer',
				'hasVoIPHosting',
				'hasLongDistanceCarrier',
				'hasWirelessProvider',
				'hasPhoneSystemMaintenanceProvider',
				'hasSmartphoneManufacturer',
				'hasSmartphoneOS',
				'hasFYE'
				);

			foreach ($stringVars as $stringVar) {
				if(!empty($this->data[$stringVar])){
					$_("<$organizationUri> botk:$stringVar \"%s\" .", $this->data[$stringVar]);	
				}
			}

			$this->rdf = $turtleString;
			$this->tripleCount = $tripleCounter;
		}

		return $this->rdf;
	}

}