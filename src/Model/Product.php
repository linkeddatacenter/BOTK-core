<?php
namespace BOTK\Model;


/**
 * An ibrid class that merge the semantic of schema:organization, schema:place and schema:geo, 
 * it is similar to schema:LocalBusiness.
 * Allows the bulk setup of properties
 */
class Product extends Thing 
{

	protected static $DEFAULT_OPTIONS = array (
        'brand' => array(	
			'filter'    => FILTER_CALLBACK,
			'options' 	=> '\BOTK\Filters::FILTER_VALIDATE_URI',
			),
        'category' => array(
			'filter'    => FILTER_DEFAULT,
			'flags'  	=> FILTER_FORCE_ARRAY,
			),
        'color' => array(
			'filter'    => FILTER_DEFAULT,
			'flags'  	=> FILTER_FORCE_ARRAY,
			),
        'depth' => array(
			'filter'    => FILTER_CALLBACK,
			'options' 	=> '\BOTK\Filters::FILTER_SANITIZE_RANGE',
			'flags'  	=> FILTER_REQUIRE_SCALAR
			),
        'gtin13' => array(
			'filter'    => FILTER_CALLBACK,
			'options' 	=> '\BOTK\Filters::FILTER_SANITIZE_GTIN13',
			'flags'  	=> FILTER_FORCE_ARRAY,
			),
        'gtin8' => array(
			'filter'    => FILTER_CALLBACK,
			'options' 	=> '\BOTK\Filters::FILTER_SANITIZE_GTIN8',
			'flags'  	=> FILTER_FORCE_ARRAY,
			),
        'height' => array(
			'filter'    => FILTER_CALLBACK,
			'options' 	=> '\BOTK\Filters::FILTER_SANITIZE_RANGE',
			'flags'  	=> FILTER_FORCE_ARRAY,
			),
        'isAccessoryOrSparePartFor' => array(	
			'filter'    => FILTER_CALLBACK,
			'options' 	=> '\BOTK\Filters::FILTER_VALIDATE_URI',
			'flags'  	=> FILTER_FORCE_ARRAY,
			),
        'isConsumableFor' => array(	
			'filter'    => FILTER_CALLBACK,
			'options' 	=> '\BOTK\Filters::FILTER_VALIDATE_URI',
			'flags'  	=> FILTER_FORCE_ARRAY,
			),
        'isRelatedTo' => array(	
			'filter'    => FILTER_CALLBACK,
			'options' 	=> '\BOTK\Filters::FILTER_VALIDATE_URI',
			'flags'  	=> FILTER_FORCE_ARRAY,
			),
        'isSimilarTo' => array(	
			'filter'    => FILTER_CALLBACK,
			'options' 	=> '\BOTK\Filters::FILTER_VALIDATE_URI',
			'flags'  	=> FILTER_FORCE_ARRAY,
			),
        'itemCondition' => array(
			'filter'    => FILTER_CALLBACK,
			'options' 	=> '\BOTK\Filters::FILTER_VALIDATE_URI',
			'flags'  	=> FILTER_REQUIRE_SCALAR
			),
        'manufacturer' => array(	
			'filter'    => FILTER_CALLBACK,
			'options' 	=> '\BOTK\Filters::FILTER_VALIDATE_URI',
			'flags'  	=> FILTER_REQUIRE_SCALAR,
			),
        'material' => array(	
			'filter'    => FILTER_CALLBACK,
			'options' 	=> '\BOTK\Filters::FILTER_VALIDATE_URI',
			'flags'  	=> FILTER_REQUIRE_SCALAR,
			),
        'mpn' => array(
			'filter'    => FILTER_DEFAULT,
			'flags'  	=> FILTER_FORCE_ARRAY,
			),
        'productionDate' => array(
			'filter'    => FILTER_CALLBACK,
			'options' 	=> '\BOTK\Filters::FILTER_SANITIZE_DATETIME',
			'flags'  	=> FILTER_REQUIRE_SCALAR,
			), 
        'purchaseDate' => array(
			'filter'    => FILTER_CALLBACK,
			'options' 	=> '\BOTK\Filters::FILTER_SANITIZE_DATETIME',
			'flags'  	=> FILTER_REQUIRE_SCALAR,
			), 
        'releaseDate' => array(
			'filter'    => FILTER_CALLBACK,
			'options' 	=> '\BOTK\Filters::FILTER_SANITIZE_DATETIME',
			'flags'  	=> FILTER_REQUIRE_SCALAR,
			), 
        'review' => array(
			'filter'    => FILTER_DEFAULT,
			'flags'  	=> FILTER_FORCE_ARRAY,
			),
        'sku' => array(
			'filter'    => FILTER_DEFAULT,
			'flags'  	=> FILTER_FORCE_ARRAY,
			),
        'weight' => array(
			'filter'    => FILTER_CALLBACK,
			'options' 	=> '\BOTK\Filters::FILTER_SANITIZE_RANGE',
			'flags'  	=> FILTER_REQUIRE_SCALAR
			),
        'width' => array(
			'filter'    => FILTER_CALLBACK,
			'options' 	=> '\BOTK\Filters::FILTER_SANITIZE_RANGE',
			'flags'  	=> FILTER_REQUIRE_SCALAR
			),
		);

	
	public function asTurtleFragment()
	{
		static $uriVars= array( 'brand','isAccessoryOrSparePartFor','isConsumableFor','isRelatedTo','isSimilarTo','itemCondition', 'manufacturer','material');
		static $stringVars= array('category','color','gtin13','gtin8','mpn','review','sku');
		static $dateVars= array('productionDate','purchaseDate','releaseDate');
		
		if(is_null($this->rdf)) {
			
			$this->rdf = parent::asTurtleFragment();
			
			$productUri = $this->getUri();

	 		// serialize uri properties
			$this->rdf .= "<$productUri> ";
			foreach ($uriVars as $var) {
				if(!empty($this->data[$var])){
					$this->addFragment("schema:$var <%s>;", $this->data[$var],false);	
				}
			}
			
			// serialize string properties		
			foreach ($stringVars as $var) {
				if(!empty($this->data[$var])){
					$this->addFragment("schema:$var \"%s\";", $this->data[$var]);	
				}
			}
			
			// serialize date properties
			foreach ($dateVars as $var ) {
				if(!empty($this->data[$var])){
					$this->addFragment("schema:$var \"%s\"^^xsd:dateTime;", $this->data[$var]);	
				}
			}
			
			$this->rdf.='a schema:Product.';
			$this->tripleCount++;	
			
			// serialize quantitative values		
			$statVars = array('depth','height','weight','width' );
			foreach ( $statVars as $statVar) {
				if(!empty($this->data[$statVar])&& ($range=\BOTK\Filters::PARSE_QUANTITATIVE_VALUE($this->data[$statVar])) ){
					list($min,$max)=$range;
					$base = $this->data['base'];
					if( $min===$max){
						$statUri =  "{$base}{$min}";			
						$this->rdf.= "<$productUri> schema:$statVar <$statUri>.<$statUri> schema:value $min .";	
						$this->tripleCount +=3;					
					} else {
						$statUri =  "{$base}{$min}to{$max}";			
						$this->rdf.= "<$productUri> schema:$statVar <$statUri>.<$statUri> schema:minValue $min ;schema:maxValue $max .";	
						$this->tripleCount +=4;											
					}
				}		
			}

		}

		return $this->rdf;
	}

}