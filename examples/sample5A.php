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
            $data['itBudget'] = stringToRange($rawdata[53]);  
            $data['itStorageBudget'] = stringToRange($rawdata[54]);  
            $data['itHardwareBudget'] = stringToRange($rawdata[55]);  
            $data['softwareBudget'] = stringToRange($rawdata[56]);  
            $data['itServerBudget'] = stringToRange($rawdata[57]);
            $data['faxNumber'] = $rawdata[155];
            $data['homepage'] = $rawdata[166];
            $data['naceV2'] = $rawdata[177];
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


function stringToRange($string){
    $string = str_replace(".", "", $string);
    preg_match('/([0-9]+)[^0-9]*([0-9]+)?/', $string, $matches);
    $minValue =  (int) $matches[1];
    $maxValue = empty($matches[2])? $minValue : (int) $matches[2];
    $range = ($minValue < $maxValue) ? "$minValue-$maxValue" : "$maxValue-$minValue";
    return $range;
}

BOTK\SimpleCsvGateway::factory($options)->run();