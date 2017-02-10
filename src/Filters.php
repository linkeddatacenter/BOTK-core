<?php
namespace BOTK;


/**
 * A collection of filetr to be used as callback with php filters
 */
class Filters {

	/**
	 * uppercase, no trailing and ending blancs, no multiple spaces, no "strange" strings, no blanks after dot., a single blanc after comma
	 */
	static function normalizeAddress($value)
	{
		$value = filter_var($value, FILTER_SANITIZE_STRING); 	// no "strange" strings
		$value = preg_replace('/\s*([,;])/', '$1 ', $value);	// a single blanc after comma and semicolon, no space before
		$value = preg_replace('/\-/', ' - ', $value);			// a single blanc after and before dash
		$value = preg_replace('/\s*\.\s*/', '.', $value);		// no blanks before and after dot 
		$value = preg_replace('/[\s]{1,}/', ' ', $value);		// no multiple spaces,
		$value = preg_replace('/,\s,\s/', ', ', $value);		// remove multiple comma
		$value = preg_replace('/;\s;\s/', '; ', $value);		// remove multiple semicolon
		$value = preg_replace('/\-\s\-\s/', '- ', $value);		// remove multiple dash
		$value = preg_replace('/^\s*[\,\;]/', '', $value);		// remove  comma and semicolon at start
		$value = trim($value); 									// no trailing and ending blancs
		return mb_strtoupper($value,'UTF-8');					// uppercase
	}
	

	static function normalizeToken($value)
	{
		$value = preg_replace('/[^\w]/', '', $value);
		return strtoupper($value);
	}


	/**
	 * Normalize an italian telephone number
	 */
	static function normalizeItTelephone($value)
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

		return $value;		
	}
	
	
	/**
	 * Normalize email
	 */
	static function normalizeEmail($value)
	{
		$value =  	filter_var($value, FILTER_VALIDATE_EMAIL);
		return strtoupper($value);		
	}
	

	/**
	 * Create a normalizzed addres from a propery list.
	 * Property list can contain follwin fields:
	 * 		'addressCountry',
	 * 		'addressLocality',
	 * 		'addressRegion',
	 * 		'streetAddress',
	 * 		'postalCode',
	 */
	static function buildNormalizedAddress(array $properties)
	{	
		extract($properties);
		
		// veryfy that at least a minimum set of information are present
		if(empty($streetAddress) || empty($addressCountry) || (empty($addressLocality) && empty($postalCode))){
			return false;
		}

		$geolabel = "$streetAddress ,";
		if(!empty($postalCode)) { $geolabel.= " $postalCode";}
		if(!empty($addressLocality)) { $geolabel.= " $addressLocality"; }
		if(!empty($addressRegion)) { $geolabel.= " ($addressRegion)"; }
		$geolabel.= " - $addressCountry";
		
		return self::normalizeAddress($geolabel);
	}

	
}