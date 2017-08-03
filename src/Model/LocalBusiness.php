<?php
namespace BOTK\Model;


/**
 * An ibrid class that merge the semantic of schema:organization, schema:place and schema:geo, 
 * it is similar to schema:LocalBusiness.
 * Allows the bulk setup of properties
 */
class LocalBusiness extends AbstractModel implements \BOTK\ModelInterface 
{

	protected static $DEFAULT_OPTIONS = array (
		'businessType'		=> array(		
								// additional types  as extension of schema:LocalBusiness
			'filter'    => FILTER_DEFAULT,
			'flags'  	=> FILTER_FORCE_ARRAY,
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
		'businessName'		=> array(
								// a schema:alternateName for schema:PostalAddress
			'filter'    => FILTER_DEFAULT,
			'flags'  	=> FILTER_FORCE_ARRAY,
			),
		'addressDescription'=> array(	//	
			'filter'    => FILTER_CALLBACK,
			'options' 	=> '\BOTK\Filters::FILTER_SANITIZE_ADDRESS',
			'flags'  	=> FILTER_REQUIRE_SCALAR,
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
		'lat'				=> array( 
			'filter'    => FILTER_CALLBACK,
			'options' 	=> '\BOTK\Filters::FILTER_SANITIZE_GEO',
			),
		'long'				=> array( 
			'filter'    => FILTER_CALLBACK,
			'options' 	=> '\BOTK\Filters::FILTER_SANITIZE_GEO',
			),
		'similarStreet'		=> array(	
			'filter'    => FILTER_CALLBACK,
			'options' 	=> '\BOTK\Filters::FILTER_VALIDATE_URI',
			'flags'  	=> FILTER_FORCE_ARRAY,
			),
		'hasMap'			=> array(	
			'filter'    => FILTER_CALLBACK,
			'options' 	=> '\BOTK\Filters::FILTER_SANITIZE_HTTP_URL',
			'flags'  	=> FILTER_FORCE_ARRAY,
			),
		'aggregateRatingValue'	=> array(	
			'filter'    => FILTER_VALIDATE_FLOAT,
			'flags'  	=> FILTER_REQUIRE_SCALAR,
			),
		'openingHours'		   => array(	
			'filter'    => FILTER_DEFAULT,
			'flags'  	=> FILTER_REQUIRE_SCALAR,
			),
		'near'				=> array(	
			'filter'    => FILTER_CALLBACK,
			'options' 	=> '\BOTK\Filters::FILTER_VALIDATE_URI',
			'flags'  	=> FILTER_FORCE_ARRAY,
			),
		'similarName'		=> array(	
			'filter'    => FILTER_CALLBACK,
			'options' 	=> '\BOTK\Filters::FILTER_VALIDATE_URI',
			'flags'  	=> FILTER_FORCE_ARRAY,
			),		
		'numberOfEmployees'	  => array(	
			'filter'    => FILTER_VALIDATE_REGEXP,
			'options' 	=> array('regexp'=>'/^[0-9]+\s*-?\s*[0-9]*$/'),
			'flags'  	=> FILTER_REQUIRE_SCALAR,
			),
		'annualTurnover'	 => array(	
			'filter'    => FILTER_VALIDATE_REGEXP,
			'options' 	=> array('regexp'=>'/^-?[0-9]+\s*-?\s*-?[0-9]*$/'),
			'flags'  	=> FILTER_REQUIRE_SCALAR,
			),
		'ateco2007'			=> array(	
			'filter'    => FILTER_VALIDATE_REGEXP,
			'options' 	=> array('regexp'=>'/^[0-9]{6}$/'),
			'flags'  	=> FILTER_REQUIRE_SCALAR,
			),
		'EBITDA'			=> array(	
			'filter'    => FILTER_VALIDATE_REGEXP,
			'options' 	=> array('regexp'=>'/^-?[0-9]+\s*-?\s*-?[0-9]*$/'),
			'flags'  	=> FILTER_REQUIRE_SCALAR,
			),
		'netProfit'			=> array(	
			'filter'    => FILTER_VALIDATE_REGEXP,
			'options' 	=> array('regexp'=>'/^-?[0-9]+\s*-?\s*-?[0-9]*$/'),
			'flags'  	=> FILTER_REQUIRE_SCALAR,
			)
		);

	/**
	 * Redefine protected constructor to add address description as dynamic property
	 */
	protected function __construct(array $data = array(), array $customOptions = array()) 
	{
		parent::__construct($data, $customOptions);
		$this->addAddressDescription();
	}
	
	
	/**
	 * If not existing, create an address description as a normalized address from following data properties:
	 * 		'addressLocality',
	 * 		'addressRegion',
	 * 		'streetAddress',
	 * 		'postalCode',
	 */
	private function addAddressDescription()
	{	
		extract($this->data);

		if(empty($addressDescription)){
			if( !empty($streetAddress) && ( !empty($addressLocality) || !empty($postalCode))){
				$addressDescription = "$streetAddress ,";
				if(!empty($postalCode)) { $addressDescription.= " $postalCode";}
				if(!empty($addressLocality)) { $addressDescription.= " $addressLocality"; }
				if(!empty($addressRegion)) { $addressDescription.= " ($addressRegion)"; }
			} else {
				$addressDescription = null;
			}
		}
		
		$addressDescription = \BOTK\Filters::FILTER_SANITIZE_ADDRESS($addressDescription);
		
		if(!empty($addressDescription)){
			$this->data['addressDescription']=$addressDescription;
		}
	}
	
	
	public function asTurtleFragment()
	{
		if(is_null($this->rdf)) {
			extract($this->data);

			// create uris
			$organizationUri = $this->getUri();
			$addressUri = $organizationUri.'_address';
			$geoUri = ( !empty($lat) && !empty($long) )?"geo:$lat,$long":null;
			
			$tripleCounter =0;
			$turtleString='';
			
			// define $_ as a macro to write simple rdf
			$_= function($format, $var,$sanitize=true) use(&$turtleString, &$tripleCounter){
				foreach((array)$var as $v){
					if($var){
						$turtleString.= sprintf($format,$sanitize?\BOTK\Filters::FILTER_SANITIZE_TURTLE_STRING($v):$v);
						$tripleCounter++;
					}
				}
			};

	 		// serialize schema:LocalBusiness
			$_('<%s> a schema:LocalBusiness;', $organizationUri);
			!empty($businessType) 		&& $_('a %s;', $businessType);
			!empty($id) 				&& $_('dct:identifier "%s";', $id);
			!empty($vatID) 				&& $_('schema:vatID "%s";', $vatID); 
			!empty($taxtID) 			&& $_('schema:taxtID "%s";', $taxID);
			!empty($legalName)			&& $_('schema:legalName "%s";', $legalName);
			!empty($businessName) 		&& $_('schema:alternateName "%s";', $businessName);
			!empty($telephone) 			&& $_('schema:telephone "%s";', $telephone);
			!empty($faxNumber) 			&& $_('schema:faxNumber "%s";', $faxNumber);
			!empty($openingHours)		&& $_('schema:openingHours "%s";', $openingHours);
			!empty($disambiguatingDescription)&& $_('schema:disambiguatingDescription "%s";', $disambiguatingDescription);
			!empty($ateco2007)&& $_('botk:ateco2007 "%s";', $ateco2007);
			!empty($aggregateRatingValue)&& $_('schema:aggregateRating [a schema:AggregateRating; schema:ratingValue "%s"^^xsd:float];', $aggregateRatingValue);
			!empty($page) 				&& $_('foaf:page <%s>;', $page,false);
			!empty($email) 				&& $_('schema:email "%s";', $email);
			!empty($homepage) 			&& $_('foaf:homepage <%s>;', $homepage,false);
			!empty($geoUri) 			&& $_('schema:geo <%s>;', $geoUri,false);
			!empty($hasMap) 			&& $_('schema:hasMap <%s>;', $hasMap,false);
			$_('schema:address <%s>. ', $addressUri);
			
			// serialize schema:PostalAddress 
			$_('<%s> a schema:PostalAddress;', $addressUri);
			!empty($addressDescription) && $_('schema:description "%s";', $addressDescription);
			!empty($streetAddress) 		&& $_('schema:streetAddress "%s";', $streetAddress);
			!empty($postalCode) 		&& $_('schema:postalCode "%s";', $postalCode);
			!empty($addressLocality) 	&& $_('schema:addressLocality "%s";', $addressLocality);
			!empty($addressRegion) 		&& $_('schema:addressRegion "%s";', $addressRegion);			
			$_('schema:addressCountry "%s". ', $addressCountry);

			// serialize schema:GeoCoordinates
			if( !empty($geoUri)){
				$_('<%s> a schema:GeoCoordinates;', $geoUri); 
				$_('wgs:lat "%s"^^xsd:float;', $lat);
				$_('wgs:long "%s"^^xsd:float . ', $long); 
			}
			
			$statVars = array(
				'numberOfEmployees',
				'annualTurnover',
				'EBITDA',
				'netProfit'
				);
			
			foreach ( $statVars as $statVar){
				if(!empty($this->data[$statVar]) && preg_match('/^(-?[0-9]+)\s*-?\s*(-?[0-9]*)$/', $this->data[$statVar], $matches)){
					$statUri =  $organizationUri.'_'.$statVar;

					$_("<$organizationUri> botk:$statVar <%s> .", $statUri, false);	

					$_('<%s> a schema:QuantitativeValue, botk:EstimatedRange;', $statUri);
					$minValue =  (int) $matches[1];
					$maxValue = empty($matches[2])? $minValue : (int) $matches[2];
					$_('schema:minValue %s ;', $minValue);
					$_('schema:maxValue %s .', $maxValue);
				}		
			}

			$this->rdf = $turtleString;
			$this->tripleCount = $tripleCounter;
		}

		return $this->rdf;
	}

}