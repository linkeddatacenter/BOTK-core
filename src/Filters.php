<?php
namespace BOTK;


/**
 * A collection of custom filters to be used as callback with php data filtering functions
 * 	- to allow empty values  if the data is invalid filter MUST returns null
 * 	- to deny empty values if the data is invalid filter MUST returns false
 */
class Filters {
	
	 const UNITS_REGEXP = '/(hundred|thousand|million|billion)/i';

	
    /**
     * Validate a URI according to RFC 3986 (+errata)
     * (See: http://www.rfc-editor.org/errata_search.php?rfc=3986)
     *
     * @param uri the URI to validate
     * @return the url if is valid, FALSE when invalid
     */
	public static function FILTER_VALIDATE_URI($uri)
	{
		return preg_match('/^(([^:\/?#]+):)?(\/\/([^\/?#]*))?([^?#]*)(\?([^#]*))?(#(.*))?/', $uri)
			?$uri
			:false;
	}

	/**
	 * empty allowed
	 */
	public static function FILTER_SANITIZE_ADDRESS($value)
	{
		$value = preg_replace('/\s*([,;])/', '$1 ', $value);	// a single blanc after comma and semicolon, no space before
		$value = preg_replace('/\-/', ' - ', $value);			// a single blanc after and before dash
		$value = preg_replace('/\s*\.\s*/', '.', $value);		// no blanks before and after dot 
		$value = preg_replace('/,\s,\s/', ', ', $value);		// remove multiple comma
		$value = preg_replace('/;\s;\s/', '; ', $value);		// remove multiple semicolon
		$value = preg_replace('/\-\s\-\s/', '- ', $value);		// remove multiple dash
		$value = preg_replace('/^\s*[\,\;]/', '', $value);		// remove  comma and semicolon at start
		$value = preg_replace('/\\\\+/', '/', $value);			// backslash changed to slash
		$value = preg_replace('/\s+/', ' ', $value);			// no multiple spaces,
		$value = preg_replace('/\s?,?\s?(N|N\.|NUM\.|NUM|NUMERO|NUMBER|#)\s?(\d+)/i', ', $2', $value);		
																// try normalizing civic numbers
		$value = mb_strtoupper($value,'UTF-8');					// move to upper (preserving accents)
		$value = trim($value); 									// no trailing and ending blancs
		
		return $value?:null;									// multibyte uppercase
	}
	
	/**
	 * not empty token
	 */
	public static function FILTER_SANITIZE_TOKEN($value)
	{
		$value = preg_replace('/[^\w]/', '', $value);
		$value = strtoupper($value);
		
		return $value?:false;
	}


	/**
	 * Normalize an italian telephone number
	 * empty allowed
	 */
	public static function FILTER_SANITIZE_TELEPHONE($value)
	{
		$value = preg_replace('/^[^0-9+]+/', '', $value);  		// remove all from beginning execept numbers and +
		$value = preg_replace('/^\+39/', '', $value);  			// remove +39 prefix		
		$value = preg_replace('/^0039/', '', $value);  			// remove 0039 prefix
		$value = preg_replace('/[\s\(\)]+/', '', $value);  		// remove all blanks and parenthesis
				
		// separate number from extensions (if any)
		if( preg_match('/([0-9]+)(.*)/', $value, $matches)){
			$value = $matches[1];
			$extension = preg_replace('/[^0-9]/', '', $matches[2]);;
			if($extension){
				$value .= " [$extension]";
			}			
		}					

		return $value?:null;		
	}
	
	
	/**
	 * Normalize http url
	 */
	public static function FILTER_SANITIZE_HTTP_URL($value)
	{
		if( !preg_match('/^http/i', $value)){
			$value = 'http://'.$value;
		}
		$value =  filter_var($value, FILTER_VALIDATE_URL);
		
		return $value?:null;	
	}
	
	
	/**
	 * Normalize email
	 */
	public static function FILTER_SANITIZE_EMAIL($value)
	{	
		$value = preg_replace('/^mailto:/i', '', $value);		// remove schema
		$value =  filter_var($value, FILTER_VALIDATE_EMAIL);
		$value = preg_replace('/\s+/', ' ', $value);			// no multiple spaces,
		$value =  strtoupper($value);
		
		return  $value?:null;		
	}
	
	
	/**
	 * not null lowercase id
	 */
	public static function FILTER_SANITIZE_ID($value)
	{
		$value = mb_strtolower($value, 'UTF-8');
		$value = preg_replace('/[^\p{L}0-9]+/u', ' ', $value);
		$value = preg_replace('/\s+/', '_', trim($value));
		
		return $value?:false;
	}
	
	
	/**
	 * latitude and longitude coordinates (only dot as decimal place) trunked to 6 decimals
	 * empty allowed
	 */
	public static function FILTER_SANITIZE_GEO($value)
	{
		// http://www.regexlib.com/REDetails.aspx?regexp_id=2728
		$value = preg_replace('/,/', '.', $value);
		$value = preg_replace('/(.+)\.(\d{1,6}).*/', '$1.$2', $value);
		return preg_match('/^-?([1-8]?[0-9]\.{1}\d{1,6}$|90\.{1}0{1,6}$)/', $value)?$value:null;
	}
	
	
	/**
	 * escape double quotes, backslash and new line
	 * empty allowed
	 */
	public static function FILTER_SANITIZE_TURTLE_STRING($value)
	{
		$value = preg_replace('/\\\\/', '\\\\\\\\', $value);	// escape backslash
		$value = preg_replace('/\r?\n|\r/', '\\n', $value);  // newline 
		$value = preg_replace('/"/', '\\"', $value);		// escape double quote
		
		return $value?:null;
	}

	
	/**
	 * parses a string and find inside one of muplier unit
	 * @return unit multiplier
	 */
	public static function FIND_MULTIPLIER($value)
	{
		if( preg_match(static::UNITS_REGEXP, $value,$matches)) { 
			switch ($matches[1]) {
				case 'hundred':
					$multiplier = 100;
					break;
				case 'thousand':
					$multiplier = 1000;
					break;
				case 'million':
					$multiplier = 1000000;
					break;
				case 'billion':
					$multiplier = 1000000000;
					break;
					
				default:
					$multiplier = 1;
					break;
			}
		} else {
			$multiplier = 1;			
		}
		
		return $multiplier;
	}
		

	/**
	 * Parse a range string an returns mi and max values
	 * 	if max is not specified max= PHP_INT_MAX
	 * 	if min is not specified min= -PHP_INT_MAX
	 * 
	 * @return array | false
	 * 
	 */
	public static function PARSE_QUANTITATIVE_VALUE($value)
	{
		// extract multiplier
		$multiplier = static::FIND_MULTIPLIER($value);
		
		// sutbstitute 'to' with a two dots
		$value = preg_replace('/to/', '..', $value); 
		
		// remove any characther that is not a digit nor + - < >
		$value = preg_replace('/[^+\.\-<>\d]/', '', $value); 
		
		// match  ranges
		if (preg_match('/(-?\d+\.?\d*)(-|\.\.)(-?\d+\.?\d*)/', $value, $matches)){
			//------------------ matches "123-456" or "-12.3-14.5" or "4 to 5" or "from -4 to 5" or "1..10"
			$minValue =  $matches[1]*$multiplier;
			$maxValue =  $matches[3]*$multiplier;
		} elseif (preg_match('/^(-?\d+\.?\d*)$/', $value, $matches)) {
			//----------------- matches s single value decimal or float
			$minValue =  $maxValue = $matches[1]*$multiplier;
		} elseif(preg_match('/^>(-?\d+\.?\d*)/', $value, $matches) || preg_match('/^(-?\d+\.?\d*)\+$/', $value, $matches)) {
			// matches strings like "100 +" or ">100"	
			$minValue =  $matches[1]*$multiplier;
			$maxValue =  PHP_INT_MAX;
		} elseif(preg_match('/^<(-?\d+\.?\d*)-?/', $value, $matches) ) {
			//----------------  matches the strings like  "<100"  and "100 -"
			$minValue =  -PHP_INT_MAX;
			$maxValue =  $matches[1]*$multiplier;
		} else {
			$minValue =  $maxValue = null;			
		}
	
		return (!is_null($minValue) && !is_null($maxValue) && ($minValue<=$maxValue))?array($minValue,$maxValue):false;
	}

	/**
	 * this function create a multiplier to convert storage capacity from  (MB|GB|TB|PB) to TB 
	 */
	public static function FIND_STORAGE_MULTIPLIER($unit){
		switch (strtoupper($unit)) {
			case 'MB':
				$multiplier = 0.000001;
				break;
			case 'GB':
				$multiplier = 0.001;
				break;
			case 'TB':
				$multiplier = 1;
				break;
			case 'PB':
				$multiplier = 1000;
				break;
			case 'B':
			default:
				$multiplier = 0.000000001;
				break;
		}
		return 	$multiplier;
	}

	public static function FILTER_SANITIZE_RANGE($value)
	{
		$range=static::PARSE_QUANTITATIVE_VALUE($value);
		return $range?"{$range[0]}..{$range[1]}":null;
	}

	
	/**
	 * Only things M or F allowed
	 * empty allowed
	 */
	public static function FILTER_SANITIZE_GENDER($value)
	{
		if(preg_match('/^\s*m/i',$value)){
			$value = 'http://schema.org/Male';
		} elseif (preg_match('/^\s*f/i',$value)) {
			$value = 'http://schema.org/Female';
		} else  {
			$value = null;
		};
		return $value;
	}
	

	/**
	 * empty allowed multibyte uppercase
	 */
	public static function FILTER_SANITIZE_PERSON_NAME($value)
	{
		$value = preg_replace('/\s+/', ' ', $value);			// no multiple spaces,
		$value = mb_strtoupper($value,'UTF-8');					// move to upper (preserving accents)
		$value = trim($value); 									// no trailing and ending blancs
		
		return $value?:null;									// multibyte uppercase
	}


	public static function FILTER_SANITIZE_BOOLEAN($value)
	{
		$value = trim($value);
		if( preg_match('/^(false|T|0|ko|no)/i', $value)) {
			$value = 'false';
		} elseif( preg_match('/^(true|F|1|ok|yes)/i', $value)) {
			$value = 'true';
		} else {
			$value = null;
		};
		return $value;
	}


	public static function FILTER_SANITIZE_DATETIME($value)
	{
		return trim($value)?date('c',strtotime($value)):null;
	}
	

	// Normalization of language: TBD
	// see https://en.wikipedia.org/wiki/List_of_ISO_639-1_codes
	public static function FILTER_SANITIZE_LANGUAGE($value)
	{
		if( preg_match('/^[it]/i', $value)) $value = 'it';
		return trim($value)?$value:null;
	}
	

	
	/**
	 * this function normalize Storage Capacity in TB
	 */
	public static function SANITIZE_STORAGE_CAPACITY($value){
		if( preg_match('/^\s*(\d+\.?\d*)\s*(MB|GB|TB|PB)?\s*$/', $value, $matches)){		
			// matches single value
			if(empty($matches[2])) {$matches[2]='TB';};
			$value= $matches[1]*static::FIND_STORAGE_MULTIPLIER($matches[2]);
			return "$value..$value";
		} elseif( preg_match('/(\d+\.?\d*)\s*(MB|GB|TB|PB)?\s*to\s*(\d+\.?\d*)\s*(MB|GB|TB|PB)?\s*/', $value, $matches)){	
			// matches a range of values
			list(,$from,$fromUnit,$to, $toUnit) = $matches;
			if(empty($toUnit)) {$toUnit=empty($fromUnit)?'B':$fromUnit;};
			if(empty($fromUnit)) {$fromUnit=$toUnit;}
			return static::FILTER_SANITIZE_RANGE(sprintf('%s..%s',$from*static::FIND_STORAGE_MULTIPLIER($fromUnit),$to*static::FIND_STORAGE_MULTIPLIER($toUnit)));
		} else {
			return null;
		}
	}


	public static function LEFT_PAD_INT_WITH_ZERO($value, $size)
	{
		$intValue = (int) $value;
		$value = str_pad( $intValue, $size, '0', STR_PAD_LEFT );
		return ($intValue>0 && strlen($value)==$size)?$value:null;	
	}


	public static function FILTER_SANITIZE_GTIN8($value)
	{
		return static::LEFT_PAD_INT_WITH_ZERO($value, 8);
	}


	public static function FILTER_SANITIZE_GTIN13($value)
	{
		return static::LEFT_PAD_INT_WITH_ZERO($value, 13);
	}
	
}