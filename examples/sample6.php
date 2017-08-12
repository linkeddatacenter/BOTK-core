<?php
require_once __DIR__.'/../vendor/autoload.php';

define('NAMESPACE_PREFIX', 'https://data.icecat.biz/export/freeurls/export_urls.txt#');
define('MANUFACTURERS_PREFIX', 'https://data.icecat.biz/export/freeurls/supplier_mapping.xml#');
define('CATEGORIES_PREFIX', 'https://data.icecat.biz/export/freeurls/categories.xml#');
define('SHOP_NAME', 'openICEcat-url'); // change this with your registered icecat account name


$options = array(
    'factsProfile' => array(
        'model'			=> 'Product',
        'modelOptions'		=> array(
            'base' => array( 'default'=> NAMESPACE_PREFIX)
            ),
        'datamapper'	=> function(array $rawdata){
            $data = array();

			$data['id'] = $rawdata[0];
			$data['homepage'] = $rawdata[3].'&shopname='.SHOP_NAME;
			$data['brand'] = MANUFACTURERS_PREFIX.$rawdata[4];
			$data['image'] = $rawdata[5];
			$data['subject'][] = CATEGORIES_PREFIX.$rawdata[8];
			$data['subject'][] = CATEGORIES_PREFIX.$rawdata[9];
			$data['mpn'][] = $rawdata[10];
			$data['gtin13'] = explode(';',$rawdata[11]);
			if($rawdata[12]!=$rawdata[10]) $data['mpn'][] = $rawdata[12];
			
            return $data;
        },
        'rawdataSanitizer' => function( $rawdata){
			return (count($rawdata)==18)?$rawdata:false;
        },
        ),
    'fieldDelimiter' => "\t", //tab
    'skippFirstLine'    => true,
    'bufferSize' 		=> 10000,
    );

BOTK\SimpleCsvGateway::factory($options)->run();