<?php
namespace BOTK\Model;

class Thing extends AbstractModel implements \BOTK\ModelInterface  
{
	
	protected static $DEFAULT_OPTIONS  = array(
		'uri'				=> array(
								'filter'    => FILTER_CALLBACK,
		                        'options' 	=> '\BOTK\Filters::FILTER_VALIDATE_URI',
                            	'flags'  	=> FILTER_REQUIRE_SCALAR,
			                   ),
		'base'				=> array(
								'default'	=> 'urn:local:',
								'filter'    => FILTER_CALLBACK,
		                        'options' 	=> '\BOTK\Filters::FILTER_VALIDATE_URI',
                            	'flags'  	=> FILTER_REQUIRE_SCALAR,
			                   ),
		'id'				=> array(
								'filter'    => FILTER_CALLBACK,
		                        'options' 	=> '\BOTK\Filters::FILTER_SANITIZE_ID',
                            	'flags'  	=> FILTER_REQUIRE_SCALAR,
			                   ),
		'page'				=> array(	
								'filter'    => FILTER_CALLBACK,
		                        'options' 	=> '\BOTK\Filters::FILTER_SANITIZE_HTTP_URL',
                            	'flags'  	=> FILTER_FORCE_ARRAY,
			                   ),
		'homepage'			=> array(	
								'filter'    => FILTER_CALLBACK,
		                        'options' 	=> '\BOTK\Filters::FILTER_SANITIZE_HTTP_URL',
                            	'flags'  	=> FILTER_FORCE_ARRAY,
			                   ),
		'disambiguatingDescription'=> array(	
								'filter'    => FILTER_DEFAULT,
                            	'flags'  	=> FILTER_FORCE_ARRAY,
			                   ),
		'subject'			=> array(	
								'filter'    => FILTER_CALLBACK,
		                        'options' 	=> '\BOTK\Filters::FILTER_VALIDATE_URI',
                            	'flags'  	=> FILTER_FORCE_ARRAY,
			                   ),
		'image'			=> array(	
								'filter'    => FILTER_CALLBACK,
		                        'options' 	=> '\BOTK\Filters::FILTER_SANITIZE_HTTP_URL',
                            	'flags'  	=> FILTER_FORCE_ARRAY,
			                   ),
		'sameas'			=> array(	
								'filter'    => FILTER_CALLBACK,
		                        'options' 	=> '\BOTK\Filters::FILTER_VALIDATE_URI',
                            	'flags'  	=> FILTER_FORCE_ARRAY,
			                   ),
		'name'				=> array(		
								'filter'    => FILTER_DEFAULT,
                            	'flags'  	=> FILTER_FORCE_ARRAY,
			                   ),
		'alternateName'		=> array(		
								'filter'    => FILTER_DEFAULT,
                            	'flags'  	=> FILTER_FORCE_ARRAY,
			                   ),
		'description'		=> array(		
								'filter'    => FILTER_DEFAULT,
                            	'flags'  	=> FILTER_FORCE_ARRAY,
			                   ),
		'similarName'		=> array(	
			'filter'    => FILTER_CALLBACK,
			'options' 	=> '\BOTK\Filters::FILTER_VALIDATE_URI',
			'flags'  	=> FILTER_FORCE_ARRAY
			),	
	);
	

	/**
	 * a generic implementation that use uri, base and id property (all optionals)
	 */
	public function getUri()
	{
		if(!empty($this->data['uri'])){
			$uri =  $this->data['uri'];
		} elseif(!empty($this->data['base'])) {
			$idGenerator=$this->uniqueIdGenerator;
			$uri = $this->data['base'];
			$uri.=empty($this->data['id'])?$idGenerator($this->data):$this->data['id'];
		} else{
			$idGenerator=$this->uniqueIdGenerator;
			$uri = 'urn:local:botk:'.$idGenerator($this->data);
		}
		
		return $uri;
	}

	
	public function asTurtleFragment()
	{
		if(is_null($this->rdf)) {
			$uri = $this->getUri();
			
			// define $_ as a macro to write simple rdf
			$_= function($format, $var,$sanitize=true) use(&$turtleString, &$tripleCounter){
				foreach((array)$var as $v){
					if($var){
						$turtleString.= sprintf($format,$sanitize?\BOTK\Filters::FILTER_SANITIZE_TURTLE_STRING($v):$v);
						$tripleCounter++;
					}
				}
			};

	 		// serialize uri properies
			$tripleCounter =0;
			$turtleString="<$uri> ";
			foreach (array(
				'page' 			=> 'foaf:page',
				'homepage'		=> 'foaf:homepage',
				'subject'		=> 'skos:subject',
				'image'			=> 'schema:image',
				'sameAs'		=> 'owl:sameAs',	
			) as $uriVar=>$property) {
				if(!empty($this->data[$uriVar])){
					$_("$property <%s>;", $this->data[$uriVar],false);	
				}
			}
			
			// serialize string properies
			foreach(array(
				'id'						=> 'dct:identifier',
				'disambiguatingDescription'	=> 'schema:disambiguatingDescription',
				'name'						=> 'schema:name',
				'alternateName'				=> 'schema:alternateName',
				'description'				=> 'schema:description',
			) as $stringVar=>$property) {
				if(!empty($this->data[$stringVar])){
					$_("$property \"%s\";", $this->data[$stringVar]);	
				}
			}
			
			if($tripleCounter){
				$this->rdf = substr($turtleString, 0, -1).'.';
				$this->tripleCount = $tripleCounter;
			} else {
				$this->rdf = ''; // no serialize if uri has no attributes
			}
			
		}

		return $this->rdf;
	}
}