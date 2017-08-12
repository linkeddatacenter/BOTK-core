<?php

class ProductTest extends PHPUnit_Framework_TestCase
{	
    /**
     * @dataProvider goodBusinessContact
     */	
    public function testConstructor($data, $expectedData)
    {
    	$product = BOTK\Model\Product::fromArray($data);		
    	$this->assertEquals($expectedData, $product->asArray());
    }
    public function goodBusinessContact()
    {
    	return array( 
    		array(
    			array(),
    			array(
    				'base'				=> 'urn:local:',
    				),
    			),

    		array(
    			array(
			        'brand' => 'urn:brand:1',
			        'category' => array('a','b'),
			        'color' => 'blue',
			        'depth' => '3..5',
			        'gtin13' => '0123456789',
			        'gtin8' => '01234567',
			        'height' => '10-20',
			        'isAccessoryOrSparePartFor' => 'urn:product:1',
			        'isConsumableFor' => 'urn:product:1',
			        'isRelatedTo' =>'urn:product:1',
			        'isSimilarTo' => 'urn:product:1',
			        'itemCondition' => 'http://schema.org/NewCondition',
			        'manufacturer' => 'urn:be:1',
			        'material' => 'urn:product:1',
			        'mpn' => 'abcd',
			        'productionDate' => '6/11/1963', 
			        'purchaseDate' => '6/11/1963', 
			        'releaseDate' => '6/11/1963', 
			        'review' => 'a review',
			        'sku' => 'sku1',
			        'weight' => '20',
			        'width' => '0.3-0.6',	
				),
 
    			array( 
    				'base' => 'urn:local:',
			        'brand' => 'urn:brand:1',
			        'category' => array('a','b'),
			        'color' => array('blue'),
			        'depth' => '3..5',
			        'gtin13' => '0000123456789',
			        'gtin8' => '01234567',
			        'height' => '10..20',
			        'isAccessoryOrSparePartFor' => 'urn:product:1',
			        'isConsumableFor' => 'urn:product:1',
			        'isRelatedTo' =>'urn:product:1',
			        'isSimilarTo' => 'urn:product:1',
			        'itemCondition' => 'http://schema.org/NewCondition',
			        'manufacturer' => 'urn:be:1',
			        'material' => 'urn:product:1',
			        'mpn' => array('abcd'),
			        'productionDate' => '1963-06-11T00:00:00+00:00', 
			        'purchaseDate' => '1963-06-11T00:00:00+00:00', 
			        'releaseDate' => '1963-06-11T00:00:00+00:00', 
			        'review' => array('a review'),
			        'sku' => array('sku1'),
			        'weight' => '20..20',
			        'width' => '0.3..0.6',		
    				),
    			),
    		);
    }


    public function testGetDefaultOptions()
    {	
    	$expectedOptions =  array (
		'uri'				=> array(
								'filter'    => FILTER_CALLBACK,
		                        'options' 	=> '\BOTK\Filters::FILTER_VALIDATE_URI',
                            	'flags'  	=> FILTER_REQUIRE_SCALAR,
			                   ),
		'base'				=> array(
								'default'	=> 'urn:local:',
								'filter'    => FILTER_CALLBACK,
		                        'options' 	=> '\BOTK\Filters::FILTER_VALIDATE_URI',
                            	'flags'  	=> FILTER_REQUIRE_SCALAR,
			                   ),
		'id'				=> array(
								'filter'    => FILTER_CALLBACK,
		                        'options' 	=> '\BOTK\Filters::FILTER_SANITIZE_ID',
                            	'flags'  	=> FILTER_REQUIRE_SCALAR,
			                   ),
		'page'				=> array(	
								'filter'    => FILTER_CALLBACK,
		                        'options' 	=> '\BOTK\Filters::FILTER_SANITIZE_HTTP_URL',
                            	'flags'  	=> FILTER_FORCE_ARRAY,
			                   ),
		'homepage'			=> array(	
								'filter'    => FILTER_CALLBACK,
		                        'options' 	=> '\BOTK\Filters::FILTER_SANITIZE_HTTP_URL',
                            	'flags'  	=> FILTER_FORCE_ARRAY,
			                   ),
		'disambiguatingDescription'=> array(	
								'filter'    => FILTER_DEFAULT,
                            	'flags'  	=> FILTER_FORCE_ARRAY,
			                   ),
		'subject'			=> array(	
								'filter'    => FILTER_CALLBACK,
		                        'options' 	=> '\BOTK\Filters::FILTER_VALIDATE_URI',
                            	'flags'  	=> FILTER_FORCE_ARRAY,
			                   ),
		'image'			=> array(	
								'filter'    => FILTER_CALLBACK,
		                        'options' 	=> '\BOTK\Filters::FILTER_SANITIZE_HTTP_URL',
                            	'flags'  	=> FILTER_FORCE_ARRAY,
			                   ),
		'sameas'			=> array(	
								'filter'    => FILTER_CALLBACK,
		                        'options' 	=> '\BOTK\Filters::FILTER_VALIDATE_URI',
                            	'flags'  	=> FILTER_FORCE_ARRAY,
			                   ),
		'name'				=> array(		
								'filter'    => FILTER_DEFAULT,
                            	'flags'  	=> FILTER_FORCE_ARRAY,
			                   ),
		'alternateName'		=> array(		
								'filter'    => FILTER_DEFAULT,
                            	'flags'  	=> FILTER_FORCE_ARRAY,
			                   ),
		'description'		=> array(		
								'filter'    => FILTER_DEFAULT,
                            	'flags'  	=> FILTER_FORCE_ARRAY,
			                   ),
		'similarName'		=> array(	
			'filter'    => FILTER_CALLBACK,
			'options' 	=> '\BOTK\Filters::FILTER_VALIDATE_URI',
			'flags'  	=> FILTER_FORCE_ARRAY
			),
			
		//----------------------------------------------------------------------------
		
        'brand' => array(	
			'filter'    => FILTER_CALLBACK,
			'options' 	=> '\BOTK\Filters::FILTER_VALIDATE_URI',
			),
        'category' => array(
			'filter'    => FILTER_DEFAULT,
			'flags'  	=> FILTER_FORCE_ARRAY,
			),
        'color' => array(
			'filter'    => FILTER_DEFAULT,
			'flags'  	=> FILTER_FORCE_ARRAY,
			),
        'depth' => array(
			'filter'    => FILTER_CALLBACK,
			'options' 	=> '\BOTK\Filters::FILTER_SANITIZE_RANGE',
			'flags'  	=> FILTER_REQUIRE_SCALAR
			),
        'gtin13' => array(
			'filter'    => FILTER_CALLBACK,
			'options' 	=> '\BOTK\Filters::FILTER_SANITIZE_GTIN13',
			'flags'  	=> FILTER_FORCE_ARRAY,
			),
        'gtin8' => array(
			'filter'    => FILTER_CALLBACK,
			'options' 	=> '\BOTK\Filters::FILTER_SANITIZE_GTIN8',
			'flags'  	=> FILTER_FORCE_ARRAY,
			),
        'height' => array(
			'filter'    => FILTER_CALLBACK,
			'options' 	=> '\BOTK\Filters::FILTER_SANITIZE_RANGE',
			'flags'  	=> FILTER_FORCE_ARRAY,
			),
        'isAccessoryOrSparePartFor' => array(	
			'filter'    => FILTER_CALLBACK,
			'options' 	=> '\BOTK\Filters::FILTER_VALIDATE_URI',
			'flags'  	=> FILTER_FORCE_ARRAY,
			),
        'isConsumableFor' => array(	
			'filter'    => FILTER_CALLBACK,
			'options' 	=> '\BOTK\Filters::FILTER_VALIDATE_URI',
			'flags'  	=> FILTER_FORCE_ARRAY,
			),
        'isRelatedTo' => array(	
			'filter'    => FILTER_CALLBACK,
			'options' 	=> '\BOTK\Filters::FILTER_VALIDATE_URI',
			'flags'  	=> FILTER_FORCE_ARRAY,
			),
        'isSimilarTo' => array(	
			'filter'    => FILTER_CALLBACK,
			'options' 	=> '\BOTK\Filters::FILTER_VALIDATE_URI',
			'flags'  	=> FILTER_FORCE_ARRAY,
			),
        'itemCondition' => array(
			'filter'    => FILTER_CALLBACK,
			'options' 	=> '\BOTK\Filters::FILTER_VALIDATE_URI',
			'flags'  	=> FILTER_REQUIRE_SCALAR
			),
        'manufacturer' => array(	
			'filter'    => FILTER_CALLBACK,
			'options' 	=> '\BOTK\Filters::FILTER_VALIDATE_URI',
			'flags'  	=> FILTER_REQUIRE_SCALAR,
			),
        'material' => array(	
			'filter'    => FILTER_CALLBACK,
			'options' 	=> '\BOTK\Filters::FILTER_VALIDATE_URI',
			'flags'  	=> FILTER_REQUIRE_SCALAR,
			),
        'mpn' => array(
			'filter'    => FILTER_DEFAULT,
			'flags'  	=> FILTER_FORCE_ARRAY,
			),
        'productionDate' => array(
			'filter'    => FILTER_CALLBACK,
			'options' 	=> '\BOTK\Filters::FILTER_SANITIZE_DATETIME',
			'flags'  	=> FILTER_REQUIRE_SCALAR,
			), 
        'purchaseDate' => array(
			'filter'    => FILTER_CALLBACK,
			'options' 	=> '\BOTK\Filters::FILTER_SANITIZE_DATETIME',
			'flags'  	=> FILTER_REQUIRE_SCALAR,
			), 
        'releaseDate' => array(
			'filter'    => FILTER_CALLBACK,
			'options' 	=> '\BOTK\Filters::FILTER_SANITIZE_DATETIME',
			'flags'  	=> FILTER_REQUIRE_SCALAR,
			), 
        'review' => array(
			'filter'    => FILTER_DEFAULT,
			'flags'  	=> FILTER_FORCE_ARRAY,
			),
        'sku' => array(
			'filter'    => FILTER_DEFAULT,
			'flags'  	=> FILTER_FORCE_ARRAY,
			),
        'weight' => array(
			'filter'    => FILTER_CALLBACK,
			'options' 	=> '\BOTK\Filters::FILTER_SANITIZE_RANGE',
			'flags'  	=> FILTER_REQUIRE_SCALAR
			),
        'width' => array(
			'filter'    => FILTER_CALLBACK,
			'options' 	=> '\BOTK\Filters::FILTER_SANITIZE_RANGE',
			'flags'  	=> FILTER_REQUIRE_SCALAR
			),
		);
	
		$product = BOTK\Model\Product::fromArray(array());
		$this->assertEquals($expectedOptions, $product->getOptions());
	}




    /**
     * @dataProvider goodRdf
     */	
    public function testRdfGeneration($data, $rdf, $tripleCount)
    {
    	$product = BOTK\Model\Product::fromArray($data);		
    	$this->assertEquals($rdf, $product->asTurtleFragment());
    	$this->assertEquals($tripleCount, $product->getTripleCount());
    }
    public function goodRdf()
    {
    	return array(
    		array( 
    			array(
					'base'				=> 'urn:test:',
					'id'				=> 'a',
				),
    			'<urn:test:a> dct:identifier "a".<urn:test:a> a schema:Product.',
    			2,
    		),
    		array(
    			array(
    				'uri'=>'urn:test:b',
			        'brand' => 'urn:brand:1',
			        'category' => array('a','b'),
			        'color' => 'blue',
			        'depth' => '3..5',
			        'gtin13' => '0123456789',
			        'gtin8' => '01234567',
			        'height' => '10-20',
			        'isAccessoryOrSparePartFor' => 'urn:product:1',
			        'isConsumableFor' => 'urn:product:1',
			        'isRelatedTo' =>'urn:product:1',
			        'isSimilarTo' => 'urn:product:1',
			        'itemCondition' => 'http://schema.org/NewCondition',
			        'manufacturer' => 'urn:be:1',
			        'material' => 'urn:product:1',
			        'mpn' => 'abcd',
			        'productionDate' => '6/11/1963', 
			        'purchaseDate' => '6/11/1963', 
			        'releaseDate' => '6/11/1963', 
			        'review' => 'a review',
			        'sku' => 'sku1',
			        'weight' => '20',
			        'width' => '0.3-0.6',	
    			),
    			'<urn:test:b> schema:brand <urn:brand:1>;schema:isAccessoryOrSparePartFor <urn:product:1>;schema:isConsumableFor <urn:product:1>;schema:isRelatedTo <urn:product:1>;schema:isSimilarTo <urn:product:1>;schema:itemCondition <http://schema.org/NewCondition>;schema:manufacturer <urn:be:1>;schema:material <urn:product:1>;schema:category "a";schema:category "b";schema:color "blue";schema:gtin13 "0000123456789";schema:gtin8 "01234567";schema:mpn "abcd";schema:review "a review";schema:sku "sku1";schema:productionDate "1963-06-11T00:00:00+00:00"^^xsd:dateTime;schema:purchaseDate "1963-06-11T00:00:00+00:00"^^xsd:dateTime;schema:releaseDate "1963-06-11T00:00:00+00:00"^^xsd:dateTime;a schema:Product.<urn:test:b> schema:depth <urn:local:3to5>.<urn:local:3to5> schema:minValue 3 ;schema:maxValue 5 .<urn:test:b> schema:height <urn:local:10to20>.<urn:local:10to20> schema:minValue 10 ;schema:maxValue 20 .<urn:test:b> schema:weight <urn:local:20>.<urn:local:20> schema:value 20 .<urn:test:b> schema:width <urn:local:0.3to0.6>.<urn:local:0.3to0.6> schema:minValue 0.3 ;schema:maxValue 0.6 .',
    			35,
    		),
    	);
    }



  
    public  function testBadProduct()
    {
    	$badData = array(
    		'gtin8'				=>	'0123456789', // too long
			);
    	$product = BOTK\Model\Product::fromArray($badData);
    	$this->assertEquals(array_keys($badData), $product->getDroppedFields());
    }

}

