<?php
namespace BOTK\Model;


/**
 * An ibrid class that merge the semantic of schema:organization, schema:place and schema:geo. 
 * It is similar to schema:LocalBusiness.
 */
class LocalBusiness extends Thing
{

	protected static $DEFAULT_OPTIONS = array (

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
		if(empty($this->data['addressDescription'])){
			if( !empty($this->data['streetAddress']) && ( !empty($this->data['addressLocality']) || !empty($this->data['postalCode']))){
				$this->data['addressDescription'] = "{$this->data['streetAddress']} ,";
				if(!empty($this->data['postalCode'])) { $this->data['addressDescription'].= " {$this->data['postalCode']}";}
				if(!empty($this->data['addressLocality'])) { $this->data['addressDescription'].= " {$this->data['addressLocality']}"; }
				if(!empty($this->data['addressRegion'])) { $this->data['addressDescription'].= " ({$this->data['addressRegion']})"; }
			} 
		}
		if(!empty($this->data['addressDescription'])){
			$this->data['addressDescription'] = \BOTK\Filters::FILTER_SANITIZE_ADDRESS($this->data['addressDescription']);
		}
	}
	
	
	/**
	 * return a structured info to generate rdf code for quantitative values
	 */
	private function getParsedQuantitativeVars()
	{
		static $quantitativeValuesVar = array(
			'numberOfEmployees' => 'schema:numberOfEmployees',
			'annualTurnover' => 'botk:annualTurnover',
			'ebitda' => 'botk:ebitda' ,
			'netProfit' => 'botk:netProfit',
			'hasTotDevelopers' => 'botk:hasTotDevelopers',
			'itBudget' => 'botk:itBudget',
			'itStorageBudget' => 'botk:itStorageBudget',
			'itHardwareBudget' => 'botk:itHardwareBudget',
			'itServerBudget' => 'botk:itServerBudget',
			'softwareBudget' => 'botk:softwareBudget',				
			'hasITEmployees' => 'botk:hasITEmployees',
			'hasNumberOfPCs' => 'botk:hasNumberOfPCs',
			'hasITBudget' => 'botk:hasITBudget',
			'hasTablets' => 'botk:hasTablets',
			'hasWorkstations' => 'botk:hasWorkstations',
			'hasStorageBudget' => 'botk:hasStorageBudget',
			'hasServerBudget' => 'botk:hasServerBudget',
			'hasServers' => 'botk:hasServers',
			'hasDesktop' => 'botk:hasDesktop',
			'hasLaptops' => 'botk:hasLaptops',
			'hasPrinters' => 'botk:hasPrinters',
			'hasMultifunctionPrinters' => 'botk:hasMultifunctionPrinters',
			'hasColorPrinter' => 'botk:hasColorPrinter',
			'hasInternetUsers' => 'botk:hasInternetUsers',
			'hasWirelessUsers' => 'botk:hasWirelessUsers',
			'hasNetworkLines' => 'botk:hasNetworkLines',
			'hasRouters' => 'botk:hasRouters',
			'hasStorageCapacity' => 'botk:hasStorageCapacity',
			'hasExtensions' => 'botk:hasExtensions',
			'hasTotCallCenterCallers' => 'botk:hasTotCallCenterCallers',
			'hasThinPC' => 'botk:hasThinPC',
			'hasSalesforce' => 'botk:hasSalesforce',
			'hasRevenue' => 'botk:hasRevenue',
			'hasCommercialBudget' => 'botk:hasCommercialBudget',
			'hasHardwareBudget' => 'botk:hasHardwareBudget',
			'hasSoftwareBudget' => 'botk:hasSoftwareBudget',
			'hasOutsrcingBudget' => 'botk:hasOutsrcingBudget',
			'hasOtherHardwareBudget' => 'botk:hasOtherHardwareBudget',
			'hasPCBudget' => 'botk:hasPCBudget',
			'hasPrinterBudget' => 'botk:hasPrinterBudget',
			'hasTerminalBudget' => 'botk:hasTerminalBudget',
			'hasPeripheralBudget' => 'botk:hasPeripheralBudget',
			'hasDesktopPrinters' => 'botk:hasDesktopPrinters',
			'hasNetworkPrinters' => 'botk:hasNetworkPrinters',
			'hasSmartphoneUsers' => 'botk:hasSmartphoneUsers',
			'hasEnterpriseSmartphoneUsers' => 'botk:hasSmartphoneUsers',
		);
		
		$parsedVars=array();
		foreach ($quantitativeValuesVar as $statVar => $property){
			if(!empty($this->data[$statVar])&& ($range=\BOTK\Filters::PARSE_QUANTITATIVE_VALUE($this->data[$statVar])) ){
				list($min,$max)=$range;
				if( $min===$max){
					$statUri =  "{$this->data['base']}{$min}";			
					$turtleString = "<$statUri> schema:value $min .";	
					$tripleCounter = 1;					
				} else {
					$statUri =  "{$this->data['base']}{$min}to{$max}";			
					$turtleString = "<$statUri> schema:minValue $min ;schema:maxValue $max .";	
					$tripleCounter = 2;											
				}
				$parsedVars[$statVar] = array($property,$statUri,$turtleString,$tripleCounter);
			}		
		}
		return  $parsedVars;
	}
	
	
	public function asTurtleFragment()
	{
		static $uriVars = array(
			'parentOrganization' => 'schema:parentOrganization',
			'email' => 'schema:email',
			'geoUri' => 'schema:geo',
			'$hasMap' => 'schema:hasMap',
		);
		static $stringVars = array(
			'businessType' => 'a',
			'vatID' => 'schema:vatID',
			'taxtID' => 'schema:taxtID',
			'legalName' => 'schema:legalName',
			'businessName' => 'schema:alternateName',
			'telephone' => 'schema:telephone',
			'faxNumber' => 'schema:faxNumber',
			'openingHours' => 'schema:openingHours ',
			'disambiguatingDescription' => 'schema:disambiguatingDescription',
			'ateco2007' => 'botk:ateco2007',
			'naceV2' => 'botk:naceV2',
			'isicV4' => 'schema:isicV4',
			'hasServerManufacturer' => 'botk:hasServerManufacturer',
			'hasServerVirtualizationManufacturer' => 'botk:hasServerVirtualizationManufacturer',
			'hasDASManufacturer' => 'botk:hasDASManufacturer',
			'hasNASManufacturer' => 'botk:hasNASManufacturer',
			'hasSANManufacturer' => 'botk:hasSANManufacturer',
			'hasTapeLibraryManufacturer' => 'botk:hasTapeLibraryManufacturer',
			'hasStorageVirtualizationManufacturer' => 'botk:hasStorageVirtualizationManufacturer',
			'naics' => 'botk:naics',
			'hasNAFCode' => 'botk:hasNAFCode',
			'hasServerSeries' => 'botk:hasServerSeries',
			'hasDesktopManufacturer' => 'botk:hasDesktopManufacturer',
			'hasLaptopManufacturer' => 'botk:hasLaptopManufacturer',
			'hasDesktopVirtualizationManufacturer' => 'botk:hasDesktopVirtualizationManufacturer',
			'hasWorkstationManufacturer' => 'botk:hasWorkstationManufacturer',
			'hasNetworkPrinterManufacturer' => 'botk:hasNetworkPrinterManufacturer',
			'hasHighVolumePrinterManufacturer' => 'botk:hasHighVolumePrinterManufacturer',
			'hasCopierManufacturer' => 'botk:hasCopierManufacturer',
			'hasUPSManufacturer' => 'botk:hasUPSManufacturer',
			'hasERPSuiteVendor' => 'botk:hasERPSuiteVendor',
			'hasERPSoftwareasaServiceManufacturer' => 'botk:hasERPSoftwareasaServiceManufacturer',
			'hasAppServerSoftwareVendor' => 'botk:hasAppServerSoftwareVendor',
			'hasBusIntellSoftwareVendor' => 'botk:hasBusIntellSoftwareVendor',
			'hasCollaborativeSoftwareVendor' => 'botk:hasCollaborativeSoftwareVendor',
			'hasCRMSoftwareVendor' => 'botk:hasCRMSoftwareVendor',
			'hasCRMSoftwareasaServiceManufacturer' => 'botk:hasCRMSoftwareasaServiceManufacturer',
			'hasDocumentMgmtSoftwareVendor' => 'botk:hasDocumentMgmtSoftwareVendor',
			'hasAppConsolidationSoftwareVendor' => 'botk:hasAppConsolidationSoftwareVendor',
			'hasHumanResourceSoftwareVendor' => 'botk:hasHumanResourceSoftwareVendor',
			'hasSupplyChainSoftwareVendor' => 'botk:hasSupplyChainSoftwareVendor',
			'hasWebServiceSoftwareVendor' => 'botk:hasWebServiceSoftwareVendor',
			'hasDatawarehouseSoftwareVendor' => 'botk:hasDatawarehouseSoftwareVendor',
			'hasSaaSVendor' => 'botk:hasSaaSVendor',
			'hasEmailMessagingVendor' => 'botk:hasEmailMessagingVendor',
			'hasEmailSaaSManufacturer' => 'botk:hasEmailSaaSManufacturer',
			'hasOSVendor' => 'botk:hasOSVendor',
			'hasOSModel' => 'botk:hasOSModel',
			'hasDBMSVendor' => 'botk:hasDBMSVendor',
			'hasAcctingVendor' => 'botk:hasAcctingVendor',
			'hasAntiVirusVendor' => 'botk:hasAntiVirusVendor',
			'hasAssetManagementSoftwareVendor' => 'botk:hasAssetManagementSoftwareVendor',
			'hasEnterpriseManagementSoftwareVendor' => 'botk:hasEnterpriseManagementSoftwareVendor',
			'hasIDAccessSoftwareVendor' => 'botk:hasIDAccessSoftwareVendor',
			'hasStorageManagementSoftwareVendor' => 'botk:hasStorageManagementSoftwareVendor',
			'hasStorageSaaSManufacturer' => 'botk:hasStorageSaaSManufacturer',
			'hasEthernetTechnology' => 'botk:hasEthernetTechnology',
			'haseCommerceType' => 'botk:haseCommerceType',
			'hasHostorRemoteStatus' => 'botk:hasHostorRemoteStatus',
			'hasNetworkLineCarrier' => 'botk:hasNetworkLineCarrier',
			'hasVideoConfServicesProvider' => 'botk:hasVideoConfServicesProvider',
			'hasUnifiedCommSvcProvider' => 'botk:hasUnifiedCommSvcProvider',
			'hasRouterManufacturer' => 'botk:hasRouterManufacturer',
			'hasSwitchManufacturer' => 'botk:hasSwitchManufacturer',
			'hasVPNManufacturer' => 'botk:hasVPNManufacturer',
			'hasISP' => 'botk:hasISP',
			'hasNetworkServiceProvider' => 'botk:hasNetworkServiceProvider',
			'hasPhoneSystemManufacturer' => 'botk:hasPhoneSystemManufacturer',
			'hasVoIPManufacturer' => 'botk:hasVoIPManufacturer',
			'hasVoIPHosting' => 'botk:hasVoIPHosting',
			'hasLongDistanceCarrier' => 'botk:hasLongDistanceCarrier',
			'hasWirelessProvider' => 'botk:hasWirelessProvider',
			'hasPhoneSystemMaintenanceProvider' => 'botk:hasPhoneSystemMaintenanceProvider',
			'hasSmartphoneManufacturer' => 'botk:hasSmartphoneManufacturer',
			'hasSmartphoneOS' => 'botk:hasSmartphoneOS',
			'hasFYE'=> 'botk:hasFYE',
		);
		static $addressVars= array(
			'addressDescription' => 'schema:description',
			'streetAddress' => 'schema:streetAddress',
			'postalCode' => 'schema:postalCode',
			'addressLocality' => 'schema:addressLocality',
			'addressRegion' => 'schema:addressRegion',
			'addressCountry' => 'schema:addressCountry',
		);
		static $dateTimeVars= array(
			'foundingDate' => 'schema:foundingDate',
		);
		
		
		if(is_null($this->rdf)) {
			
			$this->rdf=parent::asTurtleFragment();

			// create uris
			$organizationUri = $this->getUri();
			$addressUri = $organizationUri.'_address';
			$geoUri = ( !empty( $this->data['lat']) && !empty( $this->data['long']) )?"geo:{$this->data['lat']},{$this->data['long']}":null;
			
			
			$parsedQuantitativeVars=$this->getParsedQuantitativeVars();

			// serializes LocalBusiness properties
			$this->addFragment('<%s> a schema:LocalBusiness;', $organizationUri);
			foreach ( $uriVars as $uriVar => $property) {
				if(!empty($this->data[$uriVar])){
					$this->addFragment("$property <%s>;", $this->data[$uriVar],false);	
				}
			}
			foreach ( $parsedQuantitativeVars as $quantitativeVar => $parsedVal) {
				list($property,$statUri,,)=$parsedVal;
				$this->addFragment("$property <%s>;", $statUri,false);
			}
			foreach ($stringVars as $stringVar => $property) {
				if(!empty($this->data[$stringVar])){
					$this->addFragment("$property \"%s\";", $this->data[$stringVar]);	
				}
			}
			foreach ($dateTimeVars as $dateVar => $property) {
				if(!empty($this->data[$dateVar])){
					$this->addFragment("$property \"%s\"^^xsd:dateTime;", $this->data[$dateVar]);	
				}
			}
			$this->addFragment('schema:address <%s>. ', $addressUri);
			
			// serializes postal address properties
			$this->rdf .= "<$addressUri> ";
			foreach( $addressVars as $stringVar=>$property) {
				if(!empty($this->data[$stringVar])){
					$this->addFragment("$property \"%s\";", $this->data[$stringVar]);	
				}
			}
			$this->rdf .= " a schema:PostalAddress.";
			$this->tripleCount++;
			
			// serializes quantitative values
			foreach ( $parsedQuantitativeVars as $quantitativeVar => $parsedVal){
				list(,,$ttl,$tc)=$parsedVal;
				$this->rdf.=$ttl;
				$this->tripleCount +=$tc;
			};	

			// serializes schema:GeoCoordinates
			if( !empty($geoUri)){
				$this->rdf.="<$geoUri> schema:latitude \"{$this->data['lat']}\"^^xsd:float;schema:longitude \"{$this->data['long']}\"^^xsd:float.";
				$this->tripleCount +=2;
			}

		}

		return $this->rdf;
	}

}