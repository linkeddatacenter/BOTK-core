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

	static protected $DEFAULT_OPTIONS = array (
		'base'				=> array(
								'default'	=> 'http://linkeddata.center/botk/resource/',
								'filter'    => FILTER_SANITIZE_URL,
			                   ),
		'uri'				=> array(
								'filter'    => FILTER_SANITIZE_URL,
			                   ),
		'lang'				=> array(
								'default'	=> 'it',		
								'filter'    => FILTER_VALIDATE_REGEXP,
		                        'options' 	=> array('regexp'=>'/^[a-z]{2}$/')
			                   ),
		'id'				=> array(		
								'filter'    => FILTER_VALIDATE_REGEXP,
		                        'options' 	=> array('regexp'=>'/^[\w]+$/')
			                   ),
		'taxID'				=> array(	
								'filter'    => FILTER_CALLBACK,
		                        'options' 	=> '\BOTK\Filters::normalizeToken'
			                   ),
		'vatID'				=> array(	// italian rules
								'filter'    => FILTER_VALIDATE_REGEXP,
		                        'options' 	=> array('regexp'=>'/^[0-9]{11}$/')
			                   ),
		'legalName'			=> array(
								'filter'    => FILTER_CALLBACK,
		                        'options' 	=> '\BOTK\Filters::normalizeAddress'
			                   ),
		'alternateName'		=> array(),
		'addressCountry'	=> array(
								'default'	=> 'IT',		
								'filter'    => FILTER_VALIDATE_REGEXP,
		                        'options' 	=> array('regexp'=>'/^[A-Z]{2}$/')
			                   ),
		'addressLocality'	=> array(	
								'filter'    => FILTER_CALLBACK,
		                        'options' 	=> '\BOTK\Filters::normalizeAddress'
			                   ),
		'addressRegion'		=> array(	
								'filter'    => FILTER_CALLBACK,
		                        'options' 	=> '\BOTK\Filters::normalizeAddress'
			                   ),
		'streetAddress'		=> array(	
								'filter'    => FILTER_CALLBACK,
		                        'options' 	=> '\BOTK\Filters::normalizeAddress'
			                   ),
		'postalCode'		=> array(	// italian rules
								'filter'    => FILTER_VALIDATE_REGEXP,
		                        'options' 	=> array('regexp'=>'/^[0-9]{5}$/')
			                   ),
		'page'				=> array(	
								'filter'    => FILTER_SANITIZE_URL
			                   ),
		'telephone'			=> array(	
								'filter'    => FILTER_CALLBACK,
		                        'options' 	=> '\BOTK\Filters::normalizeItTelephone'
			                   ),
		'faxNumber'			=> array(	
								'filter'    => FILTER_CALLBACK,
		                        'options' 	=> '\BOTK\Filters::normalizeItTelephone'
			                   ),
		'email'				=> array(	
								'filter'    => FILTER_CALLBACK,
		                        'options' 	=> '\BOTK\Filters::normalizeEmail'
			                   ),
		'geoDescription'	=> array(	
								'filter'    => FILTER_CALLBACK,
		                        'options' 	=> '\BOTK\Filters::normalizeAddress'
			                   ),
		'lat'				=> array( // http://www.regexlib.com/REDetails.aspx?regexp_id=2728
								'filter'    => FILTER_VALIDATE_REGEXP,
		                        'options' 	=> array('regexp'=>'/^-?([1-8]?[0-9]\.{1}\d{1,6}$|90\.{1}0{1,6}$)/')
			                   ),
		'long'				=> array( // http://stackoverflow.com/questions/3518504/regular-expression-for-matching-latitude-longitude-coordinates
								'filter'    => FILTER_VALIDATE_REGEXP,
		                        'options' 	=> array('regexp'=>'/-?([1-8]?[0-9]\.{1}\d{1,6}$|90\.{1}0{1,6}$)/')
			                   ),
	);
	
	
	public function asTurtle()
	{
		if(is_null($this->rdf)) {
			$tc =0;
			$rdf='';
			extract($this->data);

			// create uris
			$organizationUri = empty($uri)?($base. (empty($id)?uniqid():$id)):$uri;
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

	 		// serialize schema:Organization
	 		if( !$skippOrganization){
				$rdf.= sprintf( '<%s> a schema:Organization;', $organizationUri); $tc++;
				if(!empty($id)) { $rdf.= sprintf( 'dct:identifier "%s";', $id); $tc++; }
				if(!empty($vatID)) { $rdf.= sprintf( 'schema:vatID "%s"@%s;', $vatID, $lang); $tc++; }
				if(!empty($taxtID)) { $rdf.= sprintf( 'schema:taxtID "%s"@%s;', $taxID, $lang); $tc++; }
				if(!empty($legalName)) { $rdf.= sprintf( 'schema:legalName """%s"""@%s;', $legalName, $lang); $tc++; }
				if( !$skippPlace) { $rdf.= sprintf( 'schema:location <%s>;', $placeUri); $tc++; }
				$rdf.= " . ";
			}	
			
			// serialize schema:PostalAddress 
			if( !$skippAddress){
				$rdf.= sprintf( '<%s> a schema:PostalAddress;', $addressUri); $tc++;
				if(!empty($alternateName)) { $rdf.= sprintf( 'schema:alternateName """%s"""@%;', $addressCountry,$lang); $tc++; }
				if(!empty($streetAddress)) { $rdf.= sprintf( 'schema:streetAddress """%s"""@%s;', $streetAddress, $lang); $tc++; }
				if(!empty($postalCode)) { $rdf.= sprintf( 'schema:postalCode "%s"@%s;', $postalCode, $lang); $tc++; }
				if(!empty($addressLocality)) { $rdf.= sprintf( 'schema:addressLocality """%s"""@%s;', $addressLocality, $lang); $tc++; }
				if(!empty($addressRegion)) { $rdf.= sprintf( 'schema:addressRegion """%s"""@%s;', $addressRegion, $lang); $tc++; }
				if(!empty($addressCountry)) { $rdf.= sprintf( 'schema:addressCountry "%s";', $addressCountry); $tc++; }
				if(!empty($telephone)) { $rdf.= sprintf( 'schema:telephone """%s"""@%s;', $telephone, $lang); $tc++; }
				if(!empty($faxNumber)) { $rdf.= sprintf( 'schema:faxNumber """%s"""@%s;', $faxNumber, $lang); $tc++; }
				if(!empty($page)) { $rdf.= sprintf( 'schema:page <%s>;', $page); $tc++; }
				if(!empty($email)) { $rdf.= sprintf( 'schema:email "%s";', $email); $tc++; }
				$rdf.= " . ";
			}

			// serialize schema:GeoCoordinates
			if( !$skippGeo){
				$geoLabel = \BOTK\Filters::buildNormalizedAddress($this->data);
				$rdf.= sprintf( '<%s> a schema:GeoCoordinates;', $geoUri); $tc++;
				if(!empty($geoLabel)) { $rdf.= sprintf( 'schema:alternateLabel ""%s""@%s;', $geoLabel, $lang); $tc++; }
				if(!empty($geoDescription)) { $rdf.= sprintf( 'schema:alternateLabel ""%s""@%s;', $geoDescription, $lang); $tc++; }
				if(!empty($lat)) { $rdf.= sprintf( 'wgs:lat %s ;', $lat); $tc++; }
				if(!empty($long)) { $rdf.= sprintf( 'wgs:long %s ;', $long); $tc++; }
				$rdf.= " . ";	
			}

			// serialize schema:Place
			if( !$skippPlace){
				$rdf.= sprintf( '<%s> a schema:LocalBusiness;', $placeUri); $tc++;
				if(!$skippAddress) { $rdf.= sprintf( 'schema:address <%s>;', $addressUri); $tc++; }
				if(!$skippGeo) { $rdf.= sprintf( 'schema:geo <%s>;', $geoUri); $tc++; }
				$rdf.= " . ";
			}
	
			$this->rdf = $rdf;
			$this->tripleCount  = $tc;
		}
		
		return $this->rdf;
	}
	
}