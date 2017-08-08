<?php
namespace BOTK;


/**
 * A collection of custom filters to be used as callback with php data filtering functions
 * 	- to allow empty values  if the data is invalid filter MUST returns null
 * 	- to deny empty values if the data is invalid filter MUST returns false
 */
class Filters {
	
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
			$value = 'http://schema.org/False';
		} elseif( preg_match('/^(true|F|1|ok|yes)/i', $value)) {
			$value = 'http://schema.org/True';
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
	
}