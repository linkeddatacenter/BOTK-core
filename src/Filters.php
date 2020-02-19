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
	 * not empty token
	 */
	public static function FILTER_SANITIZE_TOKEN($value)
	{
		$value = preg_replace('/[^\w]/', '', $value);
		$value = strtoupper($value);
		
		return $value?:false;
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
		$value = \mb_strtolower($value, 'UTF-8');
		$value = preg_replace('/[^\p{L}0-9]+/u', ' ', $value);
		$value = preg_replace('/\s+/', '_', trim($value));
		
		return $value?:false;
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


	public static function LEFT_PAD_INT_WITH_ZERO($value, $size)
	{
		$intValue = (int) $value;
		$value = str_pad( $intValue, $size, '0', STR_PAD_LEFT );
		return ($intValue>0 && strlen($value)==$size)?$value:null;	
	}

}