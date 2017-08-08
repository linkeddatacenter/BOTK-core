<?php
require_once __DIR__.'/../vendor/autoload.php';

$options = array(
    'factsProfile' => array(
        'model'			=> 'LocalBusiness',
        'modelOptions'		=> array(
            'base' => array( 'default'=> 'urn:aberdeen:company:')
            ),
        'datamapper'	=> function(array $rawdata){
            $data = array();
            $data['numberOfEmployees'] = str_replace(".", "", $rawdata[1]);
            $data['businessName'][] = $rawdata[0];
            if( $rawdata[0]!= $rawdata[6]) {$data['businessName'][] = $rawdata[6];}
            $data['addressCountry'] = 'IT';
            $data['addressLocality'] = $rawdata[14];
            $data['postalCode'] = $rawdata[15];
            $data['addressDescription'] = $rawdata[18];
            $data['streetAddress'] = trim( $rawdata[19].' '.$rawdata[20]); 
            $data['isicV4'] = $rawdata[23];
            $data['annualTurnover'] = $rawdata[30];
            $data['vatID'] = str_pad($rawdata[33],11,'0',STR_PAD_LEFT);
            $data['telephone'] = $rawdata[41];  
            $data['id'] = $rawdata[43];           
            $data['faxNumber'] = $rawdata[155];
            $data['homepage'] = $rawdata[166];
            $data['naceV2'] = $rawdata[177];
            /*==========================================v6.3.0=====================================*/
            $data['hasITEmployees'] = stringToRange($rawdata[35]);
            $data['hasNumberOfPCs'] = stringToRange($rawdata[36]);
            $data['hasITBudget'] = stringToRange($rawdata[44]);
            $data['hasTablets'] = stringToRange($rawdata[51]);
            $data['hasWorkstations'] = stringToRange($rawdata[52]);
            $data['hasStorageBudget'] = stringToRange($rawdata[60]);
            $data['hasServerBudget'] = stringToRange($rawdata[61]);
            $data['hasServers'] = stringToRange($rawdata[67]);
            $data['hasTotDevelopers'] = stringToRange($rawdata[82]);
            $data['hasDesktop'] = stringToRange($rawdata[83]);
            $data['hasLaptops'] = stringToRange($rawdata[84]);
            $data['hasPrinters'] = stringToRange($rawdata[85]);
            $data['hasMultifunctionPrinters'] = stringToRange($rawdata[86]);
            $data['hasColorPrinter'] = stringToRange($rawdata[87]);
            $data['hasInternetUsers'] = stringToRange($rawdata[88]);
            $data['hasWirelessUsers'] = stringToRange($rawdata[89]);
            $data['hasNetworkLines'] = stringToRange($rawdata[90]);
            $data['hasRouters'] = stringToRange($rawdata[91]);
            $data['hasStorageCapacity'] = stringToRange($rawdata[92]);
            $data['hasExtensions'] = stringToRange($rawdata[93]);
            $data['hasTotCallCenterCallers'] = stringToRange($rawdata[94]);
            $data['hasThinPC'] = stringToRange($rawdata[95]);
            $data['hasSalesforce'] = stringToRange($rawdata[180]);
            $data['hasRevenue'] = stringToRange($rawdata[181]);
            $data['hasCommercialBudget'] = stringToRange($rawdata[208]);
            $data['hasHardwareBudget'] = stringToRange($rawdata[209]);
            $data['hasSoftwareBudget'] = stringToRange($rawdata[210]);
            $data['hasOutsrcingBudget'] = stringToRange($rawdata[211]);
            $data['hasOtherHardwareBudget'] = stringToRange($rawdata[212]);
            $data['hasPCBudget'] = stringToRange($rawdata[213]);
            $data['hasPrinterBudget'] = stringToRange($rawdata[214]);
            $data['hasTerminalBudget'] = stringToRange($rawdata[215]);
            $data['hasPeripheralBudget'] = stringToRange($rawdata[236]);
            $data['hasDesktopPrinters'] = stringToRange($rawdata[240]);
            $data['hasNetworkPrinters'] = stringToRange($rawdata[242]);
            $data['hasSmartphoneUsers'] = stringToRange($rawdata[259]);
            $data['hasEnterpriseSmartphoneUsers'] = stringToRange($rawdata[260]);
            /*============================================6.3.0 string=============================================*/
            $data['hasServerManufacturer'] = $rawdata[68];
            $data['hasServerVirtualizationManufacturer'] = $rawdata[69];
            $data['hasDASManufacturer'] = $rawdata[70];
            $data['hasNASManufacturer'] = $rawdata[71];
            $data['hasSANManufacturer'] = $rawdata[72];
            $data['hasTapeLibraryManufacturer'] = $rawdata[73];
            $data['hasStorageVirtualizationManufacturer'] = $rawdata[74];
            $data['naics'] = $rawdata[169];
            $data['hasFYE'] = $rawdata[192];
            $data['hasNAFCode'] = $rawdata[200];
            $data['hasServerSeries'] = $rawdata[379];
            $data['hasDesktopManufacturer'] = $rawdata[380];
            $data['hasLaptopManufacturer'] = $rawdata[381];
            $data['hasDesktopVirtualizationManufacturer'] = $rawdata[382];
            $data['hasWorkstationManufacturer'] = $rawdata[383];
            $data['hasNetworkPrinterManufacturer'] = $rawdata[384];
            $data['hasHighVolumePrinterManufacturer'] = $rawdata[385];
            $data['hasCopierManufacturer'] = $rawdata[386];
            $data['hasUPSManufacturer'] = $rawdata[387];
            $data['hasERPSuiteVendor'] = $rawdata[388];
            $data['hasERPSoftwareasaServiceManufacturer'] = $rawdata[389];
            $data['hasAppServerSoftwareVendor'] = $rawdata[390];
            $data['hasBusIntellSoftwareVendor'] = $rawdata[391];
            $data['hasCollaborativeSoftwareVendor'] = $rawdata[392];
            $data['hasCRMSoftwareVendor'] = $rawdata[393];
            $data['hasCRMSoftwareasaServiceManufacturer'] = $rawdata[394];
            $data['hasDocumentMgmtSoftwareVendor'] = $rawdata[395];
            $data['hasAppConsolidationSoftwareVendor'] = $rawdata[396];
            $data['hasHumanResourceSoftwareVendor'] = $rawdata[397];
            $data['hasSupplyChainSoftwareVendor'] = $rawdata[398];
            $data['hasWebServiceSoftwareVendor'] = $rawdata[399];
            $data['hasDatawarehouseSoftwareVendor'] = $rawdata[400];
            $data['hasSaaSVendor'] = $rawdata[401];
            $data['hasEmailMessagingVendor'] = $rawdata[402];
            $data['hasEmailSaaSManufacturer'] = $rawdata[403];
            $data['hasOSVendor'] = $rawdata[404];
            $data['hasOSModel'] = $rawdata[405];
            $data['hasDBMSVendor'] = $rawdata[406];
            $data['hasAcctingVendor'] = $rawdata[407];
            $data['hasAntiVirusVendor'] = $rawdata[409];
            $data['hasAssetManagementSoftwareVendor'] = $rawdata[410];
            $data['hasEnterpriseManagementSoftwareVendor'] = $rawdata[411];
            $data['hasIDAccessSoftwareVendor'] = $rawdata[412];
            $data['hasStorageManagementSoftwareVendor'] = $rawdata[413];
            $data['hasStorageSaaSManufacturer'] = $rawdata[414];
            $data['hasEthernetTechnology'] = $rawdata[415];
            $data['haseCommerceType'] = $rawdata[417];
            $data['hasHostorRemoteStatus'] = $rawdata[418];
            $data['hasNetworkLineCarrier'] = $rawdata[419];
            $data['hasVideoConfServicesProvider'] = $rawdata[420];
            $data['hasUnifiedCommSvcProvider'] = $rawdata[421];
            $data['hasRouterManufacturer'] = $rawdata[422];
            $data['hasSwitchManufacturer'] = $rawdata[423];
            $data['hasVPNManufacturer'] = $rawdata[424];
            $data['hasISP'] = $rawdata[425];
            $data['hasNetworkServiceProvider'] = $rawdata[426];
            $data['hasPhoneSystemManufacturer'] = $rawdata[428];
            $data['hasVoIPManufacturer'] = $rawdata[429];
            $data['hasVoIPHosting'] = $rawdata[430];
            $data['hasLongDistanceCarrier'] = $rawdata[431];
            $data['hasWirelessProvider'] = $rawdata[432];
            $data['hasPhoneSystemMaintenanceProvider'] = $rawdata[433];
            $data['hasSmartphoneManufacturer'] = $rawdata[434];
            $data['hasSmartphoneOS'] = $rawdata[435];

            if(!empty($rawdata[32])) { $data['parentOrganization'] = 'urn:aberdeen:company:'.$rawdata[32];}

			// TBD
            return $data;
        },
        'rawdataSanitizer' => function( $rawdata){
            return (count($rawdata)==435)?$rawdata:false;
        },
        ),
'fieldDelimiter' => ';',
'skippFirstLine'    => false,
);


/*
    string type 123 to 456
                123 to +
*/
function stringToRange($string){
    $string = str_replace(".", "", $string);
    if(empty($string)){ 
        return $string;
    }

    preg_match('/([<]|[0-9]+)[^+0-9]*([+]|[0-9]+)?/', $string, $matches); //
    if(!empty($matches[1]) && is_numeric($matches[1])){
        $minValue =  empty($matches[1])? (int) $matches[0] : (int) $matches[1];
        $maxValue;
        if(empty($matches[2])){
            $maxValue = $minValue;
        }else{
            $maxValue = $matches[2];
            if($matches[2] == '+'){
                $maxValue = PHP_INT_MAX; //accept inf or +
            } 
        }
        $range = ($minValue < $maxValue) ? "$minValue-$maxValue" : "$maxValue-$minValue";   
        return $range;
    }else{
        if(!empty($matches[1]) && $matches[1]=='<'){
            $maxValue = $matches[2];
            return "0 - $maxValue";
        }
    }
}


/*
    sting type : '<250'
*/


BOTK\SimpleCsvGateway::factory($options)->run();