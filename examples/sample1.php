<?php
require_once __DIR__.'/../vendor/autoload.php';

$options = array(
	'factsProfile' => array(
		'model'			=> 'LocalBusiness',
		'options'		=> array(
			'base' => array( 'default'=> 'urn:sample1:')
		),
		'datamapper'	=> function($rawdata){
			$data = array();
			$data['id'] = $rawdata[0];
			$data['businessType'] = 'schema:'.\BOTK\Filters::FILTER_SANITIZE_ID($rawdata[16]);
			$data['businessName'][] = $rawdata[1];
			$data['businessName'][] = $rawdata[2];
			$data['vatID'] = $rawdata[3];
			$data['email'] = $rawdata[4];
			$data['addressLocality'] = $rawdata[5];
			$data['postalCode'] = $rawdata[7];
			$data['addressRegion'] = $rawdata[8];
			$data['streetAddress'] = $rawdata[9] . ' ' . $rawdata[10] . ', ' . $rawdata[11];
			$data['long'] = $rawdata[14];			
			$data['lat'] = $rawdata[15];
			
			return $data;
		},
		'rawDataValidationFilter' => function( $rawdata){
			return !empty($rawdata['16']);
		},	
	),
	'skippFirstLine'	=> false,
	'fieldDelimiter' => '|'
);

BOTK\SimpleCsvGateway::factory($options)->run();
