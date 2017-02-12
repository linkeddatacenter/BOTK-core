<?php
namespace BOTK\Model;

use BOTK\Exceptions\DataModelException;
use BOTK\ModelInterface;

/**
 * An ibrid class that merge the semantic of schema:organization, schema:place and schema:geo, 
 * it is similar to schema:LocalBusiness.
 * Allows the bulk setup of properties
 */
class LocalBusiness extends AbstractModel implements ModelInterface 
{
		// use uniqid as default generator
	protected $uniqueIdGenerator = null;

	static protected $DEFAULT_OPTIONS = array (
		'base'				=> array(
								'default'	=> 'http://linkeddata.center/botk/resource/',
								'filter'    => FILTER_SANITIZE_URL,
                            	'flags'  	=> FILTER_REQUIRE_SCALAR,
			                   ),
		'uri'				=> array(
								'filter'    => FILTER_SANITIZE_URL,
                            	'flags'  	=> FILTER_REQUIRE_SCALAR,
			                   ),
		'lang'				=> array(
								'default'	=> 'it',		
								'filter'    => FILTER_VALIDATE_REGEXP,
		                        'options' 	=> array('regexp'=>'/^[a-z]{2}$/'),
                            	'flags'  	=> FILTER_REQUIRE_SCALAR,
			                   ),
		'id'				=> array(		
								'filter'    => FILTER_VALIDATE_REGEXP,
		                        'options' 	=> array('regexp'=>'/^[\w]+$/'),
                            	'flags'  	=> FILTER_REQUIRE_SCALAR,
			                   ),
		'taxID'				=> array(	
								'filter'    => FILTER_CALLBACK,
		                        'options' 	=> '\BOTK\Filters::FILTER_SANITIZE_TOKEN',
                            	'flags'  	=> FILTER_REQUIRE_SCALAR,
			                   ),
		'vatID'				=> array(	// italian rules
								'filter'    => FILTER_VALIDATE_REGEXP,
		                        'options' 	=> array('regexp'=>'/^[0-9]{11}$/'),
                            	'flags'  	=> FILTER_REQUIRE_SCALAR,
			                   ),
		'legalName'			=> array(
								'filter'    => FILTER_CALLBACK,
		                        'options' 	=> '\BOTK\Filters::FILTER_SANITIZE_ADDRESS',
                            	'flags'  	=> FILTER_REQUIRE_SCALAR,
			                   ),
		'alternateName'		=> array(
								'filter'    => FILTER_DEFAULT,
                            	'flags'  	=> FILTER_FORCE_ARRAY,
							   ),
		'addressCountry'	=> array(
								'default'	=> 'IT',		
								'filter'    => FILTER_VALIDATE_REGEXP,
		                        'options' 	=> array('regexp'=>'/^[A-Z]{2}$/'),
                            	'flags'  	=> FILTER_REQUIRE_SCALAR,
			                   ),
		'addressLocality'	=> array(	
								'filter'    => FILTER_CALLBACK,
		                        'options' 	=> '\BOTK\Filters::FILTER_SANITIZE_ADDRESS',
                            	'flags'  	=> FILTER_REQUIRE_SCALAR,
			                   ),
		'addressRegion'		=> array(	
								'filter'    => FILTER_CALLBACK,
		                        'options' 	=> '\BOTK\Filters::FILTER_SANITIZE_ADDRESS',
                            	'flags'  	=> FILTER_REQUIRE_SCALAR,
			                   ),
		'streetAddress'		=> array(	
								'filter'    => FILTER_CALLBACK,
		                        'options' 	=> '\BOTK\Filters::FILTER_SANITIZE_ADDRESS',
                            	'flags'  	=> FILTER_REQUIRE_SCALAR,
			                   ),
		'postalCode'		=> array(	// italian rules
								'filter'    => FILTER_VALIDATE_REGEXP,
		                        'options' 	=> array('regexp'=>'/^[0-9]{5}$/'),
                            	'flags'  	=> FILTER_REQUIRE_SCALAR,
			                   ),
		'page'				=> array(	
								'filter'    => FILTER_SANITIZE_URL,
                            	'flags'  	=> FILTER_FORCE_ARRAY,
			                   ),
		'telephone'			=> array(	
								'filter'    => FILTER_CALLBACK,	
		                        'options' 	=> '\BOTK\Filters::FILTER_SANITIZE_TELEPHONE',
                            	'flags'  	=> FILTER_FORCE_ARRAY,
			                   ),
		'faxNumber'			=> array(	
								'filter'    => FILTER_CALLBACK,
		                        'options' 	=> '\BOTK\Filters::FILTER_SANITIZE_TELEPHONE',
                            	'flags'  	=> FILTER_FORCE_ARRAY,
			                   ),
		'email'				=> array(	
								'filter'    => FILTER_CALLBACK,
		                        'options' 	=> '\BOTK\Filters::FILTER_SANITIZE_EMAIL',
                            	'flags'  	=> FILTER_FORCE_ARRAY,
			                   ),
		'geoDescription'	=> array(	
								'filter'    => FILTER_CALLBACK,	
		                        'options' 	=> '\BOTK\Filters::FILTER_SANITIZE_ADDRESS',
                            	'flags'  	=> FILTER_FORCE_ARRAY,
			                   ),
		'lat'				=> array( // http://www.regexlib.com/REDetails.aspx?regexp_id=2728
								'filter'    => FILTER_VALIDATE_REGEXP,
		                        'options' 	=> array('regexp'=>'/^-?([1-8]?[0-9]\.{1}\d{1,6}$|90\.{1}0{1,6}$)/'),
                            	'flags'  	=> FILTER_REQUIRE_SCALAR,
			                   ),
		'long'				=> array( // http://stackoverflow.com/questions/3518504/regular-expression-for-matching-latitude-longitude-coordinates
								'filter'    => FILTER_VALIDATE_REGEXP,
		                        'options' 	=> array('regexp'=>'/-?([1-8]?[0-9]\.{1}\d{1,6}$|90\.{1}0{1,6}$)/'),
                            	'flags'  	=> FILTER_REQUIRE_SCALAR,
			                   ),
	);

	
	/**
	 * Create a normalized address from wollowing datam properties
	 * 		'addressCountry',
	 * 		'addressLocality',
	 * 		'addressRegion',
	 * 		'streetAddress',
	 * 		'postalCode',
	 *  If data is not sufficient to create a good addess, false is returned.
	 */	
	public function buildNormalizedAddress()
	{	
		extract($this->data);
		
		// veryfy that at least a minimum set of information are present
		if(empty($streetAddress) || empty($addressCountry) || (empty($addressLocality) && empty($postalCode))){
			return false;
		}

		$geolabel = "$streetAddress ,";
		if(!empty($postalCode)) { $geolabel.= " $postalCode";}
		if(!empty($addressLocality)) { $geolabel.= " $addressLocality"; }
		if(!empty($addressRegion)) { $geolabel.= " ($addressRegion)"; }
		$geolabel.= " - $addressCountry";
		
		return \BOTK\Filters::FILTER_SANITIZE_ADDRESS($geolabel);
	}
	
	
	public function asTurtle()
	{
		if(is_null($this->rdf)) {
			extract($this->data);

			// create uris
			$organizationUri = $this->getUri();
			$addressUri = $organizationUri.'_address';
			$placeUri = $organizationUri.'_place';
			$geoUri = ( !empty($lat) && !empty($long) )?"geo:$lat,$long":($organizationUri.'_geo'); 
		
			// define the minimum condition to skipp the rdf generation
			$skippAddress = 	empty($alternateName) &&
								empty($addressLocality) &&
								empty($streetAddress) &&
								empty($postalCode) &&
								empty($page) &&
								empty($telephone) &&
								empty($faxNumber) &&
								empty($email)
			;
			$skippGeo = empty($geoDescription) &&
						empty($addressLocality) &&
						empty($streetAddress) &&
						empty($lat) && 
						empty($long) ;
			$skippPlace = $skippGeo && $skippAddress;
			
			$skippOrganization = $skippPlace && empty($id)&& empty($vatID) && empty($taxID) && empty($legalName) ;
			
			// define $_ as a macro to write simple rdf
			$tc =0;
			$rdf='';
			$_= function($format, $var) use(&$lang, &$rdf, &$tc){
				if(!is_array($var)) { $var = (array) $var;}
				foreach((array)$var as $v){$rdf.= sprintf($format,$v,$lang);$tc++;}
			};
				
	 		// serialize schema:Organization
	 		if( !$skippOrganization){
	 			$_('<%s> a schema:Organization;', $organizationUri);
					!empty($id) 				&& $_('dct:identifier "%s";', $id);
					!empty($vatID) 				&& $_('schema:vatID "%s"@%s;', $vatID); 
					!empty($taxtID) 			&& $_('schema:taxtID "%s"@%s;', $taxID);
					!empty($legalName)			&& $_('schema:legalName """%s"""@%s;', $legalName);
					!$skippPlace				&& $_('schema:location <%s>;', $placeUri);
				$rdf.=' . ';
			}	
			
			// serialize schema:PostalAddress 
			if( !$skippAddress){
				$_('<%s> a schema:PostalAddress;', $addressUri);
					!empty($alternateName) 		&& $_('schema:alternateName """%s"""@%s;', $addressCountry);
					!empty($streetAddress) 		&& $_('schema:streetAddress """%s"""@%s;', $streetAddress);
					!empty($postalCode) 		&& $_('schema:postalCode "%s"@%s;', $postalCode);
					!empty($addressLocality) 	&& $_('schema:addressLocality """%s"""@%s;', $addressLocality);
					!empty($addressRegion) 		&& $_('schema:addressRegion """%s"""@%s;', $addressRegion);
					!empty($addressCountry) 	&& $_('schema:addressCountry "%s";', $addressCountry);
					!empty($telephone) 			&& $_('schema:telephone """%s"""@%s;', $telephone);
					!empty($faxNumber) 			&& $_('schema:faxNumber """%s"""@%s;', $faxNumber);
					!empty($page) 				&& $_('schema:page <%s>;', $page);
					!empty($email) 				&& $_('schema:email "%s";', $email);
				$rdf.=' . ';
			}

			// serialize schema:GeoCoordinates
			if( !$skippGeo){

				$geoDescription = $this->buildNormalizedAddress();
		
				$_('<%s> a schema:GeoCoordinates;', $geoUri); 
					!empty($geoLabel) 			&& $_('schema:alternateLabel ""%s""@%s;', $geoLabel);
					!empty($geoDescription) 	&& $_('schema:alternateLabel ""%s""@%s;', $geoDescription);
					!empty($lat) 				&& $_('wgs:lat %s ;', $lat);
					!empty($long) 				&& $_('wgs:long %s ;', $long);
				$rdf.=' . ';
			}

			// serialize schema:Place
			if( !$skippPlace){
				$_('<%s> a schema:LocalBusiness;', $placeUri);
					!$skippAddress 				&& $_('schema:address <%s>;', $addressUri);
					!$skippGeo 					&& $_('schema:geo <%s>;', $geoUri);
				$rdf.=' . ';
			}
	
			$this->rdf = $rdf;
			$this->tripleCount  = $tc;
		}
		
		return $this->rdf;
	}
	
}