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
			!empty($page) 				&& $_('schema:page <%s>;', $page,false);
			!empty($email) 				&& $_('schema:email "%s";', $email);
			!empty($homepage) 			&& $_('foaf:homepage <%s>;', $homepage,false);
			!empty($geoUri) 			&& $_('schema:geo <%s>;', $geoUri);
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

			$this->rdf = $turtleString;
			$this->tripleCount = $tripleCounter;
		}
		
		return $this->rdf;
	}
	
}