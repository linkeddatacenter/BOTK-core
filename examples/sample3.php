<?php
require_once __DIR__.'/../vendor/autoload.php';

$profile = array(
	'model'	=> 'LocalBusiness',
	'source'=> 'http://www.salute.gov.it/dataset/farmacie.jsp',
	'options' => array(
		'base' => array( 'default'=> 'http://salute.gov.it/resource/farmacie#')
	),
	'datamapper'	=> function(array $rawdata){
		$data = array();
		$data['businessType']= 'schema:botk_farmacie';
		$data['id'] = $rawdata[0];
		$data['streetAddress'] = $rawdata[2];
		$data['businessName'][]= $rawdata[2];
		$data['businessName'][]= 'FARMACIA '.$rawdata[2];
		$data['vatID'] = $rawdata[4];
		$data['postalCode'] = $rawdata[5];
		$data['addressLocality'] = $rawdata[7];
		$data['addressRegion'] = $rawdata[10];
		$data['lat'] = $rawdata[18];
		$data['long'] = $rawdata[19];			
		
		return $data;
	},
);
$factsFactory = new \BOTK\FactsFactory($profile);

if (($handle = fopen("sample3.CSV", "r")) !== FALSE) {
	$header = fgets($handle);
	echo $factsFactory->generateLinkedDataHeader();

    while (($rawdata = fgetcsv($handle, 2000, ";")) !== FALSE) {
    	if($rawdata[15]!='-') continue;
    	try{
    		$facts =$factsFactory->factualize($rawdata);
    		echo $facts->asTurtle(), "\n";
			$factsFactory->addToTripleCounter($facts->getTripleCount()) ;
    	}catch (Exception $e) {
		    echo "\n# Caught exception: " ,  $e->getMessage(), "\n";
			$factsFactory->addError($e->getMessage());
		}
    }
	
	echo $factsFactory->generateLinkedDataFooter();
	
    fclose($handle);
	
}
?>