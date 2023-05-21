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
	public static function tokens()
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
	public static function emails()
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
	public static function ids()
    {
    	return array( 
    		array( 'abC@Example.com',		'abc-example-com'), 
    		array( 'Città di Castello',		'città-di-castello'),   
    		array( 'a-b-1-2-ò',				'a-b-1-2-ò'),   
    		array( 'Gigi d\'Alessio',		'gigi-d-alessio'),
    		array( 'al "Mangione"',			'al-mangione'),    		
    		array( '#125:34',				'125-34'), 		
    		array( 'http://example.com/test?abc=3&x=1',	'http-example-com-test-abc-3-x-1'),
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
	public static function turtle_strings()
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
	public static function httpUrls()
    {
    	return array( 
    		array( 'www.example.com',				'http://www.example.com'),
    	    array( 'https://www.example.com/',		'https://www.example.com/'),
    	    array( 'http://www.example.com/',		'http://www.example.com/'),
    		array( 'https:\\\\www.example.com\\',		null), 	 
    		array( 'https:\\www.example.com\\',		null), 	 
    		array( 'C:\a\b',		null), 	 
    		array( 'prova a caso',		null), 	
		);
   	}
   	
   	
   	
   	/**
   	 * @dataProvider httpUris
   	 */
   	public function testHttpUris($data, $expectedData)
   	{
   	    $this->assertEquals($expectedData, BOTK\Filters::FILTER_VALIDATE_URI($data));
   	}
   	public static function httpUris()
   	{
   	    return array(
   	        array( 'http://www.example.com',				'http://www.example.com'),
   	        array( 'https://www.example.com/',		'https://www.example.com/'),
   	        array( 'ftp://www.example.com/',		'ftp://www.example.com/'),
   	        array( 'urn:a:b',		'urn:a:b'),
   	        array( 'prova a caso',	null),
   	    );
   	}
   	
   	
   	
   	/**
   	 * @dataProvider GGMMYYYYdates
   	 */
   	public function testGGMMYYYYDates($data, $expectedData)
   	{
   	    $this->assertEquals($expectedData, BOTK\Filters::FILTER_SANITIZE_GGMMYYYY_DATE($data));
   	}
   	public static function GGMMYYYYdates()
   	{
   	    return array(
   	        array( '20/09/2020', '2020-09-20T00:00:00+00:00'),
   	        array( '20.09.2020', '2020-09-20T00:00:00+00:00'),
   	        array( '20-09-2020', '2020-09-20T00:00:00+00:00'),
   	        array( '20-9-2020', '2020-09-20T00:00:00+00:00'),
   	        array( '16-11-1983', '1983-11-16T00:00:00+00:00'),
   	        array( '16-11-1953', '1953-11-16T00:00:00+00:00'),
   	        array( '09-29-2020', false),
   	        array( '09-20-2020', false),
   	        array( '11-16-1983', false),
   	        array( '11-16-1953', false),
   	    );
   	}
   	
   	
   	
   	/**
   	 * @dataProvider MMGGYYYYdates
   	 */
   	public function testMMGGYYYYDates($data, $expectedData)
   	{
   	    $this->assertEquals($expectedData, BOTK\Filters::FILTER_SANITIZE_MMGGYYYY_DATE($data));
   	}
   	public static function MMGGYYYYdates()
   	{
   	    return array(
   	        array( '09/29/2020', '2020-09-29T00:00:00+00:00'),
   	        array( '09.29.2020', '2020-09-29T00:00:00+00:00'),
   	        array( '09-29-2020', '2020-09-29T00:00:00+00:00'),
   	        array( '09-20-2020', '2020-09-20T00:00:00+00:00'),
   	        array( '11-16-1983', '1983-11-16T00:00:00+00:00'),
   	        array( '11-16-1953', '1953-11-16T00:00:00+00:00'),
   	        array( '20-09-2020', false),
   	        array( '20-9-2020', false),
   	        array( '16-11-1983', false),
   	        array( '16-11-1953', false),
   	    );
   	}
   	
   	
   	
   	/**
   	 * @dataProvider booleans
   	 */
   	public function testBooleanss($data, $expectedData)
   	{
   	    $this->assertEquals($expectedData, BOTK\Filters::FILTER_SANITIZE_BOOLEAN($data));
   	}
   	public static function booleans()
   	{
   	    return array(
   	        array( 'true', 'true'),
   	        array( 'TRUE', 'true'),
   	        array( 'ok', 'true'),
   	        array( '1', 'true'),
   	        array( 'T', 'true'),
   	        array( 'yes', 'true'),
   	        
   	        array( 'false', 'false'),
   	        array( 'KO', 'false'),
   	        array( '0', 'false'),
   	        array( 'f', 'false'),
   	        array( 'no', 'false'),
   	        
   	        array( 'unknown', false),
   	    );
   	}
	
}
