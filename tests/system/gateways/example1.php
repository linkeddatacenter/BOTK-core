#!/usr/bin/env php
<?php
require_once __DIR__.'/../../../vendor/autoload.php';

$options = [
    'factsProfile' => [
        'model' => 'SampleSchemaThing',
        'modelOptions' => [
            'base' => [ 'default'=> 'urn:yp:registry:' ]
        ],
        'datamapper'	=> function($rawdata){
            $data = array();
            $data['id'] = $rawdata[0];
            $data['homepage'] =  $rawdata[1];
            $data['alternateName'] = [ $rawdata[2], $rawdata[3]] ;
            return $data;
        },
        'rawdataSanitizer' => function( $rawdata){
            return (count($rawdata)==4)?$rawdata:false;
        },
     ],
    'skippFirstLine'	=> true,
    'fieldDelimiter' => ','
];

BOTK\SimpleCsvGateway::factory($options)->run();