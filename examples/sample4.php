<?php
require_once __DIR__.'/../vendor/autoload.php';

$options = array(
    'factsProfile' => array(
        'model'			=> 'LocalBusiness',
        'modelOptions'		=> array(
            'base' => array( 'default'=> 'urn:bvd:aida:')
            ),
        'datamapper'	=> function(array $rawdata){
            $data = array();
            $data['businessName'] = $rawdata[0];
            $data['id'] = $rawdata[1];
            $data['taxID'] = $rawdata[1];
            $data['streetAddress'] = $rawdata[3]?($rawdata[3] . ', '. $rawdata[4]):$rawdata[4];
            $data['postalCode'] = $rawdata[5];
            $data['addressLocality'] = $rawdata[6];
            $data['addressRegion'] = $rawdata[7];
            $data['telephone'] = $rawdata[9];  
            $data['annualTurnover'] = str_replace(".", "", $rawdata[12]);
            $data['numberOfEmployees'] = str_replace(".", "", $rawdata[14]);
            $data['ateco2007'] = $rawdata[17];
            $data['EBITDA'] = str_replace(".", "", $rawdata[22]);
            $data['netProfit'] = str_replace(".", "", $rawdata[25]);
            
            return $data;
        },
        'rawdataSanitizer' => function( $rawdata){
    // salta le farmacie non attive
            return (count($rawdata)==37)?$rawdata:false;
        },
        ),
    'fieldDelimiter' => ';',
    'skippFirstLine'    => false,
    );

BOTK\SimpleCsvGateway::factory($options)->run();