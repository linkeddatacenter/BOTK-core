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
            $data['postalCode'] = str_pad($rawdata[15],5,'0',STR_PAD_LEFT);
            $data['addressDescription'] = $rawdata[18];
            $data['streetAddress'] = trim( $rawdata[19].' '.$rawdata[20]); 
            $data['isicV4'] = $rawdata[23];
            $data['annualTurnover'] = $rawdata[30];
            $data['vatID'] = str_pad($rawdata[33],11,'0',STR_PAD_LEFT); // EF: padded to 11 nubmers
            $data['telephone'] = $rawdata[41];  
            $data['id'] = $rawdata[42];           
            $data['faxNumber'] = $rawdata[155];
            $data['homepage'] = $rawdata[166];
            $data['naceV2'] = $rawdata[176];
            /*==========================================v6.3.0=====================================*/
            $data['hasITEmployees'] = stringToRange($rawdata[34]);
            $data['hasNumberOfPCs'] = stringToRange($rawdata[35]);
            $data['hasITBudget'] = stringToRange($rawdata[43]);
            $data['hasTablets'] = stringToRange($rawdata[50]);
            $data['hasWorkstations'] = stringToRange($rawdata[51]);
            $data['hasStorageBudget'] = stringToRange($rawdata[59]);
            $data['hasServerBudget'] = stringToRange($rawdata[60]);
            $data['hasServers'] = stringToRange($rawdata[66]);
            $data['hasTotDevelopers'] = stringToRange($rawdata[81]);
            $data['hasDesktop'] = stringToRange($rawdata[82]);
            $data['hasLaptops'] = stringToRange($rawdata[83]);
            $data['hasPrinters'] = stringToRange($rawdata[84]);
            $data['hasMultifunctionPrinters'] = stringToRange($rawdata[85]);
            $data['hasColorPrinter'] = stringToRange($rawdata[86]);
            $data['hasInternetUsers'] = stringToRange($rawdata[87]);
            $data['hasWirelessUsers'] = stringToRange($rawdata[88]);
            $data['hasNetworkLines'] = stringToRange($rawdata[89]);
            $data['hasRouters'] = stringToRange($rawdata[90]);
            $data['hasStorageCapacity'] = stringToRange($rawdata[91]);
            $data['hasExtensions'] = stringToRange($rawdata[92]);
            $data['hasTotCallCenterCallers'] = stringToRange($rawdata[93]);
            $data['hasThinPC'] = stringToRange($rawdata[94]);
            $data['hasSalesforce'] = stringToRange($rawdata[179]);
            $data['hasRevenue'] = stringToRange($rawdata[180]);
            $data['hasCommercialBudget'] = stringToRange($rawdata[207]);
            $data['hasHardwareBudget'] = stringToRange($rawdata[208]);
            $data['hasSoftwareBudget'] = stringToRange($rawdata[209]);
            $data['hasOutsrcingBudget'] = stringToRange($rawdata[210]);
            $data['hasOtherHardwareBudget'] = stringToRange($rawdata[211]);
            $data['hasPCBudget'] = stringToRange($rawdata[212]);
            $data['hasPrinterBudget'] = stringToRange($rawdata[213]);
            $data['hasTerminalBudget'] = stringToRange($rawdata[214]);
            $data['hasPeripheralBudget'] = stringToRange($rawdata[235]);
            $data['hasDesktopPrinters'] = stringToRange($rawdata[239]);
            $data['hasNetworkPrinters'] = stringToRange($rawdata[241]);
            $data['hasSmartphoneUsers'] = stringToRange($rawdata[258]);
            $data['hasEnterpriseSmartphoneUsers'] = stringToRange($rawdata[259]);
            /*============================================6.3.0 string=============================================*/
            $data['id'] = $rawdata[42];
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

            if(!empty($rawdata[32])) { $data['parentOrganization'] = 'urn:aberdeen:company:'.$rawdata[32];} //EF: modified

			// TBD
            return $data;
        },
        'rawdataSanitizer' => function( $rawdata){
            return (count($rawdata)==435)?$rawdata:false;
        },
        ),
'fieldDelimiter' => ';',
'skippFirstLine'    => false,
'bufferSize'        => 100000
);



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