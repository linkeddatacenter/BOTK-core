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
            $data['businessName'] = $rawdata[6];
            $data['addressLocality'] = $rawdata[14];
            $data['postalCode'] = $rawdata[15];
            $data['streetAddress'] = $rawdata[19];
            $data['id'] = $rawdata[32];
            $data['telephone'] = $rawdata[41];  
            $data['itBudget'] = string1ToRange($rawdata[53]);  
            $data['itStorageBudget'] = string1ToRange($rawdata[54]);  
            $data['itHardwareBudget'] = string1ToRange($rawdata[55]);  
            $data['softwareBudget'] = string1ToRange($rawdata[56]);  
            $data['itServerBudget'] = string1ToRange($rawdata[57]);
            $data['faxNumber'] = $rawdata[155];
            $data['homepage'] = $rawdata[166];
            $data['naceV2'] = $rawdata[177];
            $data['hasITEmployees'] = $rawdata[35];
            /*==========================================v6.3.0=====================================*/
            $data['hasITEmployees'] = string1ToRange($rawdata[35]);
            $data['hasNumberOfPCs'] = string1ToRange($rawdata[36]);
            $data['hasITBudget'] = string1ToRange($rawdata[44]);
            $data['hasTablets'] = string1ToRange($rawdata[51]);
            $data['hasWorkstations'] = string1ToRange($rawdata[52]);
            $data['hasStorageBudget'] = string1ToRange($rawdata[60]);
            $data['hasServerBudget'] = string1ToRange($rawdata[61]);
            $data['hasServers'] = string1ToRange($rawdata[67]);
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
            $data['hasEnterpriseSmartphoneUsers'] = string2ToRange($rawdata[260]);

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

    preg_match('/([0-9]+)[^+0-9]*([+]|[0-9]+)?/', $string, $matches); //sitemare il [+]| droppa tutti i 123 +
    $minValue =  empty($matches[1])? (int) $matches[0] : (int) $matches[1];
    $maxValue;
    if(empty($matches[2])){
        $maxValue = $minValue;
    }else{
        $maxValue = $matches[2];
        if($matches[2] == '+'){
            $maxValue = PHP_INT_MAX; //forse sistemare la costante 
        } 
    }


    $range = ($minValue < $maxValue) ? "$minValue-$maxValue" : "$maxValue-$minValue";   

    return $range;
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