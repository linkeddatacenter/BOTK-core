<?php
require_once __DIR__.'/../vendor/autoload.php';

$options = array(
    'factsProfile' => array(
        'model'			=> 'BusinessContact',
        'modelOptions'		=> array(
            'base' => array( 'default'=> 'urn:aberdeen:contact:')
            ),
        'datamapper'	=> function(array $rawdata){
            $data = array();
			$data['givenName'] = $rawdata[2];
			$data['familyName'] = $rawdata[3];
			$data['jobTitle'] = $rawdata[4];
			$data['worksFor'] = $rawdata[32];
			//$data['jobFunction'] = $rawdata[36];
			$data['id'] = $rawdata[42].'_'.$rawdata[37];
			$data['worksFor'] = 'urn:aberdeen:company:'.$rawdata[42];
			$data['honorificPrefix'][] = $rawdata[339];
			$data['honorificPrefix'][] = $rawdata[340];
			$data['additionalName'] = $rawdata[341];
			$data['honorificSuffix'] = $rawdata[342];
			//$data['addressDescription'] = $rawdata[343];
			//$data['streetAddress'] = trim($rawdata[344].' '.$rawdata[345]);
			//$data['addressLocality'] = $rawdata[346];
			//$data['postalCode'] = $rawdata[347];
			$data['telephone'] = $rawdata[348];
			$data['spokenLanguage'] = $rawdata[349];
			$data['hasOptInOptOutDate'] = $rawdata[356];
			$data['privacyFlag'] = $rawdata[364];
            return $data;
        },
        'rawdataSanitizer' => function( $rawdata){
        	return $rawdata;
			//return (count($rawdata)==436)?$rawdata:false;
        },
        ),
    'fieldDelimiter' => ';',
    'skippFirstLine'    => true,
    'bufferSize' 		=> 10000,
    );

BOTK\SimpleCsvGateway::factory($options)->run();