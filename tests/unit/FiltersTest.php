<?php
use PHPUnit\Framework\TestCase;

final class FiltersTest extends TestCase
{

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
    		array( '',				false),  
		);
   	}
	
	
    /**
     * @dataProvider emails
     */		
	public function testEmailFilter($data, $expectedData)
	{
		$this->assertEquals($expectedData, BOTK\Filters::FILTER_SANITIZE_EMAIL($data));
	}
	public function emails()
    {
    	return array( 
    		array( 'abC@Example.com',		'ABC@EXAMPLE.COM'), 
    		array( 'mailto:abC@Example.com', 'ABC@EXAMPLE.COM'),
    		array( 'abc@linkeddata.center',	'ABC@LINKEDDATA.CENTER'),   
    		array( 'invalid email',			null),  
		);
   	}
	
	
    /**
     * @dataProvider ids
     */		
	public function testIdFilter($data, $expectedData)
	{
		$this->assertEquals($expectedData, BOTK\Filters::FILTER_SANITIZE_ID($data));
	}
	public function ids()
    {
    	return array( 
    		array( 'abC@Example.com',		'abc_example_com'), 
    		array( 'Città di Castello',		'città_di_castello'),   
    		array( 'a-b-1-2-ò',				'a_b_1_2_ò'),   
    		array( 'Gigi d\'Alessio',		'gigi_d_alessio'),
    		array( 'al "Mangione"',			'al_mangione'),    		
    		array( '#125:34',				'125_34'), 		
    		array( 'http://example.com/test?abc=3&x=1',	'http_example_com_test_abc_3_x_1'),
    		array( '',						false), 
		);
   	}
	
	
    /**
     * @dataProvider turtle_strings
     */		
	public function testTurtleFilter($data, $expectedData)
	{
		$this->assertEquals($expectedData, BOTK\Filters::FILTER_SANITIZE_TURTLE_STRING($data));
	}
	public function turtle_strings()
    {
    	return array( 
    		array( 'abc',							'abc'), 
    		array( '"la locanda"',					'\"la locanda\"'),
    		array( 'da ""la locanda""',				'da \"\"la locanda\"\"'), 
    		array( 'this is \ backslash',			'this is \\\ backslash'),
    		array( 'this is \\\ doublebackslash',	'this is \\\\\\\ doublebackslash'),
    		array( 'this is \\ backslash',			'this is \\\\ backslash'),
    		array( 'this is \\\\ doublebackslash',	'this is \\\\\\\\ doublebackslash'),
    		array( "newline\nescaped",				'newline\nescaped'),
    		array( '',								null), 
		);
   	}

	
    /**
     * @dataProvider httpUrls
     */		
	public function testHttpUrls($data, $expectedData)
	{
		$this->assertEquals($expectedData, BOTK\Filters::FILTER_SANITIZE_HTTP_URL($data));
	}
	public function httpUrls()
    {
    	return array( 
    		array( 'www.example.com',				'http://www.example.com'),
    		array( 'https://www.example.com/',		'https://www.example.com/'),
    		array( 'https:\\\\www.example.com\\',		null), 	 
    		array( 'https:\\www.example.com\\',		null), 	 
    		array( 'C:\a\b',		null), 	 
    		array( 'prova a caso',		null), 	
		);
   	}
	
}

