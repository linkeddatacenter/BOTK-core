<?php
require_once __DIR__.'/../vendor/autoload.php';

define('NAMESPACE_PREFIX', 'urn:aberdeen:');


/**
 * this function invalidate duplicated site records
 */
function newSite($siteId){
	static $knownSites=array();
	return isset($knownSites[$siteId])?false:($knownSites[$siteId]=true);
}


$options = array(
    'factsProfile' => array(
        'model'			=> 'LocalBusiness',
        'modelOptions'		=> array(
            'base' => array( 'default'=> NAMESPACE_PREFIX)
            ),
        'datamapper'	=> function(array $rawdata){
            $data = array();
			$data['uri'] = NAMESPACE_PREFIX . 'S'.$rawdata[42];
            $data['numberOfEmployees'] = $rawdata[1];
            $data['businessName'][] = $rawdata[0];
            if( $rawdata[0]!= $rawdata[6]) {$data['businessName'][] = $rawdata[6];}
            $data['addressCountry'] = 'IT';
            $data['addressLocality'] = $rawdata[14];
            $data['postalCode'] = str_pad($rawdata[15],5,'0',STR_PAD_LEFT);
            $data['addressDescription'] = $rawdata[18];
            $data['streetAddress'] = trim( $rawdata[19].' '.$rawdata[20]); 
            $data['isicV4'] = $rawdata[23];
            $data['annualTurnover'] = $rawdata[30];
            $data['vatID'] = str_pad($rawdata[33],11,'0',STR_PAD_LEFT); // EF: padded to 11 nubmers
            $data['telephone'] = $rawdata[40];  
            $data['id'] = $rawdata[42];           
            $data['faxNumber'] = $rawdata[154];
            $data['homepage'] = $rawdata[165];
            $data['naceV2'] = $rawdata[176];
            /*==========================================v6.3.0=====================================*/
            $data['hasITEmployees'] = $rawdata[34];
            $data['hasNumberOfPCs'] = $rawdata[35];
            $data['hasITBudget'] = $rawdata[43];
            $data['hasTablets'] = $rawdata[50];
            $data['hasWorkstations'] = $rawdata[51];
            $data['hasStorageBudget'] = $rawdata[59];
            $data['hasServerBudget'] = $rawdata[60];
            $data['hasServers'] = $rawdata[66];
            $data['hasTotDevelopers'] = $rawdata[81];
            $data['hasDesktop'] = $rawdata[82];
            $data['hasLaptops'] = $rawdata[83];
            $data['hasPrinters'] = $rawdata[84];
            $data['hasMultifunctionPrinters'] = $rawdata[85];
            $data['hasColorPrinter'] = $rawdata[86];
            $data['hasInternetUsers'] = $rawdata[87];
            $data['hasWirelessUsers'] = $rawdata[88];
            $data['hasNetworkLines'] = $rawdata[89];
            $data['hasRouters'] = $rawdata[90];
            $data['hasStorageCapacity'] = $rawdata[91];
            $data['hasExtensions'] = $rawdata[92];
            $data['hasTotCallCenterCallers'] = $rawdata[93];
            $data['hasThinPC'] = $rawdata[94];
            $data['hasSalesforce'] = $rawdata[179];
            $data['hasRevenue'] = $rawdata[180];
            $data['hasCommercialBudget'] = $rawdata[207];
            $data['hasHardwareBudget'] = $rawdata[208];
            $data['hasSoftwareBudget'] = $rawdata[209];
            $data['hasOutsrcingBudget'] = $rawdata[210];
            $data['hasOtherHardwareBudget'] = $rawdata[211];
            $data['hasPCBudget'] = $rawdata[212];
            $data['hasPrinterBudget'] = $rawdata[213];
            $data['hasTerminalBudget'] = $rawdata[214];
            $data['hasPeripheralBudget'] = $rawdata[235];
            $data['hasDesktopPrinters'] = $rawdata[239];
            $data['hasNetworkPrinters'] = $rawdata[241];
            $data['hasSmartphoneUsers'] = $rawdata[258];
            $data['hasEnterpriseSmartphoneUsers'] = $rawdata[259];
            $data['hasServerManufacturer'] = $rawdata[67];
            $data['hasServerVirtualizationManufacturer'] = $rawdata[68];
            $data['hasDASManufacturer'] = $rawdata[69];
            $data['hasNASManufacturer'] = $rawdata[70];
            $data['hasSANManufacturer'] = $rawdata[71];
            $data['hasTapeLibraryManufacturer'] = $rawdata[72];
            $data['hasStorageVirtualizationManufacturer'] = $rawdata[73];
            $data['naics'] = $rawdata[168];
            $data['hasFYE'] = $rawdata[191];
            $data['hasNAFCode'] = $rawdata[199];
            $data['hasServerSeries'] = $rawdata[378];
            $data['hasDesktopManufacturer'] = $rawdata[379];
            $data['hasLaptopManufacturer'] = $rawdata[380];
            $data['hasDesktopVirtualizationManufacturer'] = $rawdata[381];
            $data['hasWorkstationManufacturer'] = $rawdata[382];
            $data['hasNetworkPrinterManufacturer'] = $rawdata[383];
            $data['hasHighVolumePrinterManufacturer'] = $rawdata[384];
            $data['hasCopierManufacturer'] = $rawdata[385];
            $data['hasUPSManufacturer'] = $rawdata[386];
            $data['hasERPSuiteVendor'] = $rawdata[387];
            $data['hasERPSoftwareasaServiceManufacturer'] = $rawdata[388];
            $data['hasAppServerSoftwareVendor'] = $rawdata[389];
            $data['hasBusIntellSoftwareVendor'] = $rawdata[390];
            $data['hasCollaborativeSoftwareVendor'] = $rawdata[391];
            $data['hasCRMSoftwareVendor'] = $rawdata[392];
            $data['hasCRMSoftwareasaServiceManufacturer'] = $rawdata[393];
            $data['hasDocumentMgmtSoftwareVendor'] = $rawdata[394];
            $data['hasAppConsolidationSoftwareVendor'] = $rawdata[395];
            $data['hasHumanResourceSoftwareVendor'] = $rawdata[396];
            $data['hasSupplyChainSoftwareVendor'] = $rawdata[397];
            $data['hasWebServiceSoftwareVendor'] = $rawdata[398];
            $data['hasDatawarehouseSoftwareVendor'] = $rawdata[399];
            $data['hasSaaSVendor'] = $rawdata[400];
            $data['hasEmailMessagingVendor'] = $rawdata[401];
            $data['hasEmailSaaSManufacturer'] = $rawdata[402];
            $data['hasOSVendor'] = $rawdata[403];
            $data['hasOSModel'] = $rawdata[404];
            $data['hasDBMSVendor'] = $rawdata[405];
            $data['hasAcctingVendor'] = $rawdata[406];
            $data['hasAntiVirusVendor'] = $rawdata[408];
            $data['hasAssetManagementSoftwareVendor'] = $rawdata[409];
            $data['hasEnterpriseManagementSoftwareVendor'] = $rawdata[410];
            $data['hasIDAccessSoftwareVendor'] = $rawdata[411];
            $data['hasStorageManagementSoftwareVendor'] = $rawdata[412];
            $data['hasStorageSaaSManufacturer'] = $rawdata[413];
            $data['hasEthernetTechnology'] = $rawdata[414];
            $data['haseCommerceType'] = $rawdata[416];
            $data['hasHostorRemoteStatus'] = $rawdata[417];
            $data['hasNetworkLineCarrier'] = $rawdata[418];
            $data['hasVideoConfServicesProvider'] = $rawdata[419];
            $data['hasUnifiedCommSvcProvider'] = $rawdata[420];
            $data['hasRouterManufacturer'] = $rawdata[421];
            $data['hasSwitchManufacturer'] = $rawdata[422];
            $data['hasVPNManufacturer'] = $rawdata[423];
            $data['hasISP'] = $rawdata[424];
            $data['hasNetworkServiceProvider'] = $rawdata[425];
            $data['hasPhoneSystemManufacturer'] = $rawdata[427];
            $data['hasVoIPManufacturer'] = $rawdata[428];
            $data['hasVoIPHosting'] = $rawdata[429];
            $data['hasLongDistanceCarrier'] = $rawdata[430];
            $data['hasWirelessProvider'] = $rawdata[431];
            $data['hasPhoneSystemMaintenanceProvider'] = $rawdata[432];
            $data['hasSmartphoneManufacturer'] = $rawdata[433];
            $data['hasSmartphoneOS'] = $rawdata[434];

            if(!empty($rawdata[32])) {
            	 $data['parentOrganization'] = NAMESPACE_PREFIX.$rawdata[32];
			} 

            return $data;
        },
        'rawdataSanitizer' => function( $rawdata){
            return ((count($rawdata)==435) && newSite($rawdata[42]))?$rawdata:false;
        },
        ),
	'fieldDelimiter' => ';',
	'skippFirstLine'    => false,
	'bufferSize'        => 10000
);


    BOTK\SimpleCsvGateway::factory($options)->run();