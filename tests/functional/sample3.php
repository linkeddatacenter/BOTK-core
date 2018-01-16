<?php
require_once __DIR__.'/../../vendor/autoload.php';

$options = array(
	'factsProfile' => array(
		'model'			=> 'LocalBusiness',
		'modelOptions'		=> array(
			'base' => array( 'default'=> 'http://salute.gov.it/resource/farmacie#')
		),
		'datamapper'	=> function(array $rawdata){
			$data = array();
			$data['businessType']= 'schema:botk_farmacie';
			$data['id'] = $rawdata[0];
			$data['streetAddress'] = $rawdata[2];
			$data['businessName'] = 'FARMACIA '.$rawdata[3];
			$data['vatID'] = $rawdata[4];
			$data['postalCode'] = $rawdata[5];
			$data['addressLocality'] = $rawdata[7];
			$data['addressRegion'] = $rawdata[10];
			$data['lat'] = $rawdata[18];
			$data['long'] = $rawdata[19];			
			
			return $data;
		},
		'rawdataSanitizer' => function( $rawdata){
			// salta le farmacie non attive
			return ((count($rawdata)==21) && ($rawdata[15]=='-'))?$rawdata:false;
		},	
	),
	'fieldDelimiter' => ';'
);

BOTK\SimpleCsvGateway::factory($options)->run();
