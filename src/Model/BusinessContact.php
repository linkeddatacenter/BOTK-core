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
		static $uriVars = array(
			'gender' => 'schema:gender',
			'worksFor' => 'schema:worksFor',
		);
		static $stringVars = array(
			'taxID' => 'schema:taxID',
			'givenName' => 'schema:givenName',
			'familyName' => 'schema:familyName',
			'additionalName' => 'schema:additionalName',
			'telephone' => 'schema:telephone',
			'faxNumber' => 'schema:faxNumber',
			'jobTitle' => 'schema:jobTitle',
			'honorificPrefix' => 'schema:honorificPrefix',
			'honorificSuffix' => 'schema:honorificSuffix',
			'email' => 'schema:email',
			'spokenLanguage' => 'schema:spokenLanguage',
		);
		
		
		if(is_null($this->rdf)) {
			
			// make a default for altenatename (can be empty) before calling parent::asTurtleFragment
			if(empty($this->data['alternateName'])){
				$this->data['alternateName'] ='';
				if(!empty($this->data['givenName'])) { $this->data['alternateName'].= $this->data['givenName'].' ';}						
				if(!empty($this->data['additionalName'])) { $this->data['alternateName'].= $this->data['additionalName'].' ';}		
				if(!empty($this->data['familyName'])) { $this->data['alternateName'].= $this->data['familyName'].' ';}
			}			
			$this->rdf = parent::asTurtleFragment();
		
			$personUri = $this->getUri();
			
	 		// serialize schema:LocalBusiness	
			$this->rdf .= "<$personUri> ";	
			foreach ( $uriVars as $uriVar => $property) {
				if(!empty($this->data[$uriVar])){
					$this->addFragment("$property <%s>;", $this->data[$uriVar],false);	
				}
			}
			foreach ($stringVars as $stringVar => $property) {
				if(!empty($this->data[$stringVar])){
					$this->addFragment("$property \"%s\";", $this->data[$stringVar]);	
				}
			}
			!empty($this->data['hasOptInOptOutDate'])&& $this->addFragment('botk:hasOptInOptOutDate "%s"^^xsd:dateTime;', $this->data['hasOptInOptOutDate']);
			!empty($this->data['privacyFlag'])	&& $this->addFragment('botk:privacyFlag %s ;', $this->data['privacyFlag']);			
			$this->addFragment('a schema:Person.', $personUri);
					
			if(!empty($this->data['aggregateRatingValue'])){
				$ratingUri=$this->data['base'].'rating_'.$this->data['aggregateRatingValue'].
				$this->rdf .= "<$personUri> schema:aggregateRating <$ratingUri>.<$ratingUri> schema:ratingValue \"{$this->data['aggregateRatingValue']}\"^^xsd:float.";
				$this->tripleCount +=2;
			}

		}

		return $this->rdf;
	}

}