<?php

class FiltersTest extends PHPUnit_Framework_TestCase
{	
    /**
     * @dataProvider telephones
     */	
	public function testItTelephoneFilter($data, $expectedData)
	{
		$this->assertEquals($expectedData, BOTK\Filters::FILTER_SANITIZE_TELEPHONE($data));
	}
	
	public function telephones()
    {
    	return array( 
    		array( '+39 0341 2333991',		'03412333991'), 
    		array( '  +39 0341 2333991 ',		'03412333991'),   
    		array( '03412333991',		'03412333991'),  
    		array( '003903412333991',		'03412333991'), 
    		array( '00390341 23 33991',		'03412333991'), 
    		array( '+39 0341 2333991',		'03412333991'), 
    		array( '+39 03412333991',		'03412333991'), 
    		array( '+39 0341 23 33 991',		'03412333991'),
    		array( '+39 (0341) 2333991',		'03412333991'),
    		array( '+39 (0341) 2333991 ext. 1234',		'03412333991 [1234]'),
    		array( '+39 (0341) 2333991  {1234]',		'03412333991 [1234]'),		
    		array( ' apdas 0341 2333991sfsd12sdfsf 34',		'03412333991 [1234]'),
    		array( ' apdassdfs',''),
		);
   	}
	
	public function testEmailFilter()
	{
		$this->assertEquals('ABC@EXAMPLE.COM', BOTK\Filters::FILTER_SANITIZE_EMAIL('abC@Example.com'));
		$this->assertTrue(empty(BOTK\Filters::FILTER_SANITIZE_EMAIL('invalid email')));
	}

    /**
     * @dataProvider tokens
     */	
	public function testTokenFilter($data, $expectedData)
	{
		$this->assertEquals($expectedData, BOTK\Filters::FILTER_SANITIZE_TOKEN($data));
	}
	
	public function tokens()
    {
    	return array( 
    		array( 'abc d e #1',	'ABCDE1'), 
    		array( 'abcde1',		'ABCDE1'), 
    		array( 'ABCDE1',		'ABCDE1'), 
    		array( ' ABC_DE-1 ',	'ABC_DE1'), 
    		array( ' ABC_DE:1 ',	'ABC_DE1'), 
		);
   	}

    /**
     * @dataProvider adresses
     */	
	public function testAddressFilter($data, $expectedData)
	{
		$this->assertEquals($expectedData, BOTK\Filters::FILTER_SANITIZE_ADDRESS($data));
	}
	
	public function adresses()
    {
    	return array( 
    		array( 'Lungolario Luigi Cadorna, 1, 23900 Lecco LC, Italy',	'LUNGOLARIO LUIGI CADORNA, 1, 23900 LECCO LC, ITALY'),
    		array( 'Lungolario Luigi Cadorna 1-23900 Lecco LC - Italy',	'LUNGOLARIO LUIGI CADORNA 1 - 23900 LECCO LC - ITALY'),
    		array( 'Lungolario Luigi Cadorna 1 Lecco LC - -Italy',	'LUNGOLARIO LUIGI CADORNA 1 LECCO LC - ITALY'),
    		array( ',,test;;',	'TEST;'), 
		);
   	}
	
	
    /**
     * @dataProvider structuredAdresses
     */	
	public function testBuildNormalizedAddress($data, $expectedData)
	{
		$this->assertEquals($expectedData, BOTK\Filters::buildNormalizedAddress($data));
	}
	
	public function structuredAdresses()
    {
    	return array( 
    		array( 
    			array(
    				'streetAddress'		=> 'Lungolario Luigi Cadorna, 1',
    				'addressLocality'	=> 'Lecco',
    				'addressRegion'		=> 'LC',
    				'addressCountry'	=> 'IT',
    				'postalCode'		=> '23900',	
				),	
				'LUNGOLARIO LUIGI CADORNA, 1, 23900 LECCO (LC) - IT'
			),
    		array( 
    			array(
    				'streetAddress'		=> 'Lungolario Luigi Cadorna, 1',
    				'addressLocality'	=> 'Lecco',
    				'addressCountry'	=> 'IT',	
				),	
				'LUNGOLARIO LUIGI CADORNA, 1, LECCO - IT'
			),
    		array( 
    			array(
    				'streetAddress'		=> 'Lungolario Luigi Cadorna, 1',
    				'addressCountry'	=> 'IT',
    				'postalCode'		=> '23900',	
				),	
				'LUNGOLARIO LUIGI CADORNA, 1, 23900 - IT'
			),
    		array( 
    			array(
    				'addressCountry'	=> 'IT',
    				'postalCode'		=> '23900',	
				),	
				false
			),
		);
   	}
}

