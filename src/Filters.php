<?php
namespace BOTK;


/**
 * A collection of filetr to be used as callback with php filters
 */
class Filters {

	/**
	 * uppercase, no trailing and ending blancs, no multiple spaces, no "strange" strings, no blanks after dot., a single blanc after comma
	 */
	static function FILTER_SANITIZE_ADDRESS($value)
	{
		//$value = filter_var($value, FILTER_SANITIZE_STRING); 	// no "strange" strings
		$value = preg_replace('/\s*([,;])/', '$1 ', $value);	// a single blanc after comma and semicolon, no space before
		$value = preg_replace('/\-/', ' - ', $value);			// a single blanc after and before dash
		$value = preg_replace('/\s*\.\s*/', '.', $value);		// no blanks before and after dot 
		$value = preg_replace('/[\s]{1,}/', ' ', $value);		// no multiple spaces,
		$value = preg_replace('/,\s,\s/', ', ', $value);		// remove multiple comma
		$value = preg_replace('/;\s;\s/', '; ', $value);		// remove multiple semicolon
		$value = preg_replace('/\-\s\-\s/', '- ', $value);		// remove multiple dash
		$value = preg_replace('/^\s*[\,\;]/', '', $value);		// remove  comma and semicolon at start
		$value = trim($value); 									// no trailing and ending blancs
		
		return $value?mb_strtoupper($value,'UTF-8'):false;		// uppercase
	}
	

	static function FILTER_SANITIZE_TOKEN($value)
	{
		$value = preg_replace('/[^\w]/', '', $value);
		return $value?strtoupper($value):false;
	}


	/**
	 * Normalize an italian telephone number
	 */
	static function FILTER_SANITIZE_TELEPHONE($value)
	{
		$value = preg_replace('/^[^0-9\+]+/', '', $value);  	// remove all from beginning execept numbers and +
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

		return $value?:false;		
	}
	
	
	/**
	 * Normalize email
	 */
	static function FILTER_SANITIZE_EMAIL($value)
	{
		$value =  filter_var($value, FILTER_VALIDATE_EMAIL);
		return  $value?strtoupper($value):false;		
	}
	
	
	static function FILTER_SANITIZE_ID($value)
	{
		$value = strtolower($value);
		$value = preg_replace('/[^a-z0-9]+/', ' ', $value);
		$value = preg_replace('/[^a-z0-9]+/', ' ', $value);
		$value = preg_replace('/[\s]{1,}/', '_', trim($value));
		return $value?:false;
	}
	
	
	static function FILTER_SANITIZE_LAT_LONG($value)
	{
		// http://www.regexlib.com/REDetails.aspx?regexp_id=2728
		$value = preg_replace('/,/', '.', $value);
		$value = sprintf('%01.6f',floatval($value));
		return preg_match('/^-?([1-8]?[0-9]\.{1}\d{1,6}$|90\.{1}0{1,6}$)/', $value)?$value:false;
	}
	
}