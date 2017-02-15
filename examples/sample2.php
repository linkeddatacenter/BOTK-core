<?php
require_once __DIR__.'/../vendor/autoload.php';

$options = array(
	'factsProfile' => array(
		'model'			=> 'LocalBusiness',
		'options'		=> array(
			'base' => array( 'default'=> 'http://salute.gov.it/resource/farmacie#')
		),
		'source'	=> 'http://example.com/',
		'datamapper'	=> function(array $rawdata){
			$data = array();
			$data['id'] = $rawdata[2];
			$data['businessType']= 'schema:botk_farmacie';
			$data['businessName']= $rawdata[4];
			$data['streetAddress'] = $rawdata[5];
			$data['addressLocality'] = $rawdata[6];
			$data['telephone'] = $rawdata[7];
			$data['faxNumber'] = $rawdata[8];
			$data['email'] = $rawdata[9];
			$data['long'] = $rawdata[14];			
			$data['lat'] = $rawdata[13];
			
			return $data;
		},
	),
);

BOTK\SimpleCsvGateway::factory($options)->run();