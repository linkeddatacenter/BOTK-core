<?php
require_once __DIR__.'/../vendor/autoload.php';

$options = array(
    'factsProfile' => array(
        'model'			=> 'LocalBusiness',
        'modelOptions'		=> array(
            'base' => array( 'default'=> 'urn:aida:')
            ),
        'datamapper'	=> function(array $rawdata){
            $data = array();
	
			// TBD
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