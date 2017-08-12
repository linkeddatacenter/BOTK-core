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

	
	public function asTurtleFragment()
	{
		if(is_null($this->rdf)) {
			
			extract($this->data);
			
			// make a default for altenatename (can be empty) before calling parent::asTurtleFragment
			if(empty($alternateName)){
				$this->data['alternateName'] ='';
				if(!empty($givenName)) { $this->data['alternateName'].= $givenName.' ';}						
				if(!empty($additionalName)) { $this->data['alternateName'].= $additionalName.' ';}		
				if(!empty($familyName)) { $this->data['alternateName'].= $familyName.' ';}
			}			
			$this->rdf = parent::asTurtleFragment();
		
			$personUri = $this->getUri();
			
	 		// serialize schema:LocalBusiness	
			$this->rdf .= "<$personUri> ";	
			!empty($aggregateRatingValue)&& $this->addFragment('schema:aggregateRating [a schema:AggregateRating; schema:ratingValue "%s"^^xsd:float];', $aggregateRatingValue);
			!empty($taxID) 				&& $this->addFragment('schema:taxID "%s";', $taxID);
			!empty($givenName)			&& $this->addFragment('schema:givenName "%s";', $givenName);
			!empty($familyName)			&& $this->addFragment('schema:familyName "%s";', $familyName);
			!empty($additionalName) 	&& $this->addFragment('schema:additionalName "%s";', $additionalName);
			!empty($telephone) 			&& $this->addFragment('schema:telephone "%s";', $telephone);
			!empty($faxNumber) 			&& $this->addFragment('schema:faxNumber "%s";', $faxNumber);
			!empty($jobTitle)			&& $this->addFragment('schema:jobTitle "%s";', $jobTitle);
			!empty($honorificPrefix)	&& $this->addFragment('schema:honorificPrefix "%s";', $honorificPrefix);
			!empty($honorificSuffix)	&& $this->addFragment('schema:honorificSuffix "%s";', $honorificSuffix);
			!empty($email) 				&& $this->addFragment('schema:email "%s";', $email);
			!empty($gender)				&& $this->addFragment('schema:gender "%s";', $gender);
			!empty($worksFor)			&& $this->addFragment('schema:worksFor <%s> ;', $worksFor,false);
			!empty($spokenLanguage)		&& $this->addFragment('botk:spokenLanguage "%s";', $spokenLanguage);
			!empty($hasOptInOptOutDate)	&& $this->addFragment('botk:hasOptInOptOutDate "%s"^^xsd:dateTime;', $hasOptInOptOutDate);
			!empty($privacyFlag)		&& $this->addFragment('botk:privacyFlag %s ;', $privacyFlag);		
			$this->addFragment('a schema:Person.', $personUri);

		}

		return $this->rdf;
	}

}