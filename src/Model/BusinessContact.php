<?php
namespace BOTK\Model;


/**
 * An ibrid class that merge the semantic of schema:organization, schema:place and schema:geo, 
 * it is similar to schema:LocalBusiness.
 * Allows the bulk setup of properties
 */
class BusinessContact extends Thing 
{

	protected static $DEFAULT_OPTIONS = array (
		'taxID'				=> array(	
			'filter'    => FILTER_CALLBACK,
			'options' 	=> '\BOTK\Filters::FILTER_SANITIZE_TOKEN',
			'flags'  	=> FILTER_REQUIRE_SCALAR,
			),
		'alternateName'		=> array(
			'filter'    => FILTER_CALLBACK,
			'options' 	=> '\BOTK\Filters::FILTER_SANITIZE_PERSON_NAME',
			'flags'  	=> FILTER_FORCE_ARRAY,
			),
		'givenName'				=> array(
			'filter'    => FILTER_CALLBACK,
			'options' 	=> '\BOTK\Filters::FILTER_SANITIZE_PERSON_NAME',
			'flags'  	=> FILTER_REQUIRE_SCALAR,
			),
		'familyName'				=> array(
			'filter'    => FILTER_CALLBACK,
			'options' 	=> '\BOTK\Filters::FILTER_SANITIZE_PERSON_NAME',
			'flags'  	=> FILTER_REQUIRE_SCALAR,
			),
		'additionalName'			=> array(
			'filter'    => FILTER_CALLBACK,
			'options' 	=> '\BOTK\Filters::FILTER_SANITIZE_PERSON_NAME',
			'flags'  	=> FILTER_REQUIRE_SCALAR,
			),
		'jobTitle'	=> array(
			'filter'    => FILTER_DEFAULT,
			'flags'  	=> FILTER_FORCE_ARRAY,
			),
		'honorificPrefix'	=> array(
			'filter'    => FILTER_DEFAULT,
			'flags'  	=> FILTER_FORCE_ARRAY,
			),
		'honorificSuffix'	=> array(
			'filter'    => FILTER_DEFAULT,
			'flags'  	=> FILTER_FORCE_ARRAY,
			),
		'gender'	=> array(	
			'filter'    => FILTER_CALLBACK,
			'options'    => '\BOTK\Filters::FILTER_SANITIZE_GENDER',
			'flags'  	=> FILTER_REQUIRE_SCALAR,
			),
		'worksFor'	=> array(		
			'filter'    => FILTER_CALLBACK,
			'options' 	=> '\BOTK\Filters::FILTER_VALIDATE_URI',
			'flags'  	=> FILTER_REQUIRE_SCALAR,
			),
		'telephone'			=> array(	
			'filter'    => FILTER_CALLBACK,	
			'options' 	=> '\BOTK\Filters::FILTER_SANITIZE_TELEPHONE',
			'flags'  	=> FILTER_FORCE_ARRAY,
			),
		'email'				=> array(	
			'filter'    => FILTER_CALLBACK,
			'options' 	=> '\BOTK\Filters::FILTER_SANITIZE_EMAIL',
			'flags'  	=> FILTER_FORCE_ARRAY,
			),
		'spokenLanguage'			=> array(	
			'filter'    => FILTER_CALLBACK,
			'options' 	=> '\BOTK\Filters::FILTER_SANITIZE_LANGUAGE',
			'flags'  	=> FILTER_FORCE_ARRAY,
			),
		'hasOptInOptOutDate'				=> array(	
			'filter'    => FILTER_CALLBACK,
			'options' 	=> '\BOTK\Filters::FILTER_SANITIZE_DATETIME',
			'flags'  	=> FILTER_REQUIRE_SCALAR,
			),
		'privacyFlag'		=> array(	
			'filter'    => FILTER_CALLBACK,
			'options' 	=> '\BOTK\Filters::FILTER_SANITIZE_BOOLEAN',
			'flags'  	=> FILTER_REQUIRE_SCALAR,
			),
		);


	private function makePersonNameDescription()
	{	
		if(empty($this->data['alternateName'])){
			$this->data['alternateName'] ='';
			if(!empty($this->data['givenName'])) { $this->data['alternateName'].= $this->data['givenName'].' ';}						
			if(!empty($this->data['additionalName'])) { $this->data['alternateName'].= $this->data['additionalName'].' ';}		
			if(!empty($this->data['familyName'])) { $this->data['alternateName'].= $this->data['familyName'].' ';}
		}
		
		if(!empty($this->data['alternateName'])){
			$this->data['alternateName']=\BOTK\Filters::FILTER_SANITIZE_PERSON_NAME($this->data['alternateName']);
		}

	}
	
	
	public function asTurtleFragment()
	{
		if(is_null($this->rdf)) {
			extract($this->data);

			// create uris
			$personUri = $this->getUri();
			
			// make a default for altenatename (can be empty)
			if(empty($alternateName)){
				$this->data['alternateName'] ='';
				if(!empty($givenName)) { $this->data['alternateName'].= $givenName.' ';}						
				if(!empty($additionalName)) { $this->data['alternateName'].= $additionalName.' ';}		
				if(!empty($familyName)) { $this->data['alternateName'].= $familyName.' ';}
			}
			
			$turtleString = parent::asTurtleFragment();
			$tripleCounter = $this->tripleCount;
			
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
			$turtleString .= "<$personUri> ";	
			!empty($aggregateRatingValue)&& $_('schema:aggregateRating [a schema:AggregateRating; schema:ratingValue "%s"^^xsd:float];', $aggregateRatingValue);
			!empty($taxID) 				&& $_('schema:taxID "%s";', $taxID);
			!empty($givenName)			&& $_('schema:givenName "%s";', $givenName);
			!empty($familyName)			&& $_('schema:familyName "%s";', $familyName);
			!empty($additionalName) 	&& $_('schema:additionalName "%s";', $additionalName);
			!empty($telephone) 			&& $_('schema:telephone "%s";', $telephone);
			!empty($faxNumber) 			&& $_('schema:faxNumber "%s";', $faxNumber);
			!empty($jobTitle)			&& $_('schema:jobTitle "%s";', $jobTitle);
			!empty($honorificPrefix)	&& $_('schema:honorificPrefix "%s";', $honorificPrefix);
			!empty($honorificSuffix)	&& $_('schema:honorificSuffix "%s";', $honorificSuffix);
			!empty($email) 				&& $_('schema:email "%s";', $email);
			!empty($gender)				&& $_('schema:gender "%s";', $gender);
			!empty($worksFor)			&& $_('schema:worksFor <%s> ;', $worksFor,false);
			!empty($spokenLanguage)		&& $_('botk:spokenLanguage "%s";', $spokenLanguage);
			!empty($hasOptInOptOutDate)	&& $_('botk:hasOptInOptOutDate "%s"^^xsd:dateTime;', $hasOptInOptOutDate);
			!empty($privacyFlag)		&& $_('botk:privacyFlag %s ;', $privacyFlag);		
			$_('a schema:Person.', $personUri);

			$this->rdf = $turtleString;
			$this->tripleCount = $tripleCounter;
		}

		return $this->rdf;
	}

}