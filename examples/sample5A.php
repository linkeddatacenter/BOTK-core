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
            $data['businessName'][] = $rawdata[6];
            $data['addressCountry'] = 'IT';
            $data['addressLocality'] = $rawdata[14];
            $data['postalCode'] = $rawdata[15];
            $data['addressDescription'] = $rawdata[18];
            $data['streetAddress'] = trim( $rawdata[19].' '.$rawdata[20]); 
            $data['isicV4'] = $rawdata[23];
            $data['annualTurnover'] = $rawdata[30];
            $data['vatID'] = '00'. $rawdata[34];
            $data['telephone'] = $rawdata[41];  
            $data['id'] = $rawdata[43];           
            $data['faxNumber'] = $rawdata[155];
            $data['homepage'] = $rawdata[166];
            $data['naceV2'] = $rawdata[177];
            /*==========================================v6.3.0=====================================*/
            $data['hasITEmployees'] = string1ToRange($rawdata[35]);
            $data['hasNumberOfPCs'] = string1ToRange($rawdata[36]);
            $data['hasITBudget'] = string1ToRange($rawdata[44]);
            $data['hasTablets'] = string1ToRange($rawdata[51]);
            $data['hasWorkstations'] = string1ToRange($rawdata[52]);
            $data['hasStorageBudget'] = string1ToRange($rawdata[60]);
            $data['hasServerBudget'] = string1ToRange($rawdata[61]);
            $data['hasServers'] = string1ToRange($rawdata[67]);
            $data['hasTotDevelopers'] = string1ToRange($rawdata[82]);
            $data['hasDesktop'] = string1ToRange($rawdata[83]);
            $data['hasLaptops'] = string1ToRange($rawdata[84]);
            $data['hasPrinters'] = string1ToRange($rawdata[85]);
            $data['hasMultifunctionPrinters'] = string1ToRange($rawdata[86]);
            $data['hasColorPrinter'] = string1ToRange($rawdata[87]);
            $data['hasInternetUsers'] = string1ToRange($rawdata[88]);
            $data['hasWirelessUsers'] = string1ToRange($rawdata[89]);
            $data['hasNetworkLines'] = string1ToRange($rawdata[90]);
            $data['hasRouters'] = string1ToRange($rawdata[91]);
            $data['hasStorageCapacity'] = string1ToRange($rawdata[92]);
            $data['hasExtensions'] = string1ToRange($rawdata[93]);
            $data['hasTotCallCenterCallers'] = string1ToRange($rawdata[94]);
            $data['hasThinPC'] = string1ToRange($rawdata[95]);
            $data['hasSalesforce'] = string1ToRange($rawdata[180]);
            $data['hasRevenue'] = string1ToRange($rawdata[181]);
            $data['hasCommercialBudget'] = string1ToRange($rawdata[208]);
            $data['hasHardwareBudget'] = string1ToRange($rawdata[209]);
            $data['hasSoftwareBudget'] = string1ToRange($rawdata[210]);
            $data['hasOutsrcingBudget'] = string1ToRange($rawdata[211]);
            $data['hasOtherHardwareBudget'] = string1ToRange($rawdata[212]);
            $data['hasPCBudget'] = string1ToRange($rawdata[213]);
            $data['hasPrinterBudget'] = string1ToRange($rawdata[214]);
            $data['hasTerminalBudget'] = string1ToRange($rawdata[215]);
            $data['hasPeripheralBudget'] = string1ToRange($rawdata[236]);
            $data['hasDesktopPrinters'] = string1ToRange($rawdata[240]);
            $data['hasNetworkPrinters'] = string1ToRange($rawdata[242]);
            $data['hasSmartphoneUsers'] = string1ToRange($rawdata[259]);
            $data['hasEnterpriseSmartphoneUsers'] = string1ToRange($rawdata[260]);
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

            $data['parentOrganization'] = $rawdata[32];

			// TBD
            return $data;
        },
        'rawdataSanitizer' => function( $rawdata){
            return (count($rawdata)==436)?$rawdata:false;
        },
        ),
'fieldDelimiter' => ';',
'skippFirstLine'    => false,
);


/*
    string type 123 to 456
                123 to +
*/
function string1ToRange($string){
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
    function string2ToRange($string){
        $string = str_replace(".", "", $string);
        if(empty($string)) { 
            return $string;
        }

        preg_match('/([0-9]+)/', $string, $matches);
        $maxValue =  empty($matches[1])? (int) $matches[0] : (int) $matches[1];
    return "0 - $maxValue"; //only maxValue printed
}

BOTK\SimpleCsvGateway::factory($options)->run();