<?php
namespace BOTK\Model;

class SampleSchemaThing extends AbstractModel implements \BOTK\ModelInterface
{

    protected static $VOCABULARY  = array(
        'schema'	=> 'http://schema.org/',
    );
    
    
	protected static $DEFAULT_OPTIONS  = array(
	    'identifier'        => ['filter'=> FILTER_DEFAULT, 'flags'=> FILTER_REQUIRE_SCALAR],
		'homepage'			=> \BOTK\Filters::URL,
	    'subject'			=> \BOTK\Filters::URI,
	    'image'			    => \BOTK\Filters::URL,
	    'sameAs'			=> \BOTK\Filters::URI,
	    'name'				=> \BOTK\Filters::LITERAL,
	    'alternateName'		=> \BOTK\Filters::LITERAL,
	    'description'		=> \BOTK\Filters::LITERAL,
	);

	
	public function asTurtleFragment()
	{
		if(is_null($this->rdf)) {
		    $uri = $this->getUri($this->data['identifier']);
			
	 		// serialize uri properties
			$this->rdf = "<$uri> ";
			foreach (array(
				'homepage'		=> 'schema:url',
				'subject'		=> 'schema:subjectOf',
				'image'			=> 'schema:image',
				'sameAs'		=> 'schema:sameAs',
			) as $uriVar=>$property) {
				if(!empty($this->data[$uriVar])){
					$this->addFragment("$property <%s>;", $this->data[$uriVar],false);	
				}
			}
			
			// serialize string properties
			foreach(array(
				'identifier'				=> 'schema:identifier',
				'name'						=> 'schema:name',
				'alternateName'				=> 'schema:alternateName',
				'description'				=> 'schema:description',
			) as $stringVar=>$property) {
				if(!empty($this->data[$stringVar])){
					$this->addFragment("$property \"%s\";", $this->data[$stringVar]);	
				}
			}
			
			if($this->tripleCount){
				$this->rdf = substr($this->rdf, 0, -1).'.';
			} else {
				$this->rdf = ''; // no serialize if uri has no attributes
			}
			
			// Just for testing purposes
			// use $this->globalstorage just to communicate something to the calling stack
			
			$this->globalstorage= [ "SampleSchemaThing" ];
		}

		return $this->rdf;
	}
}