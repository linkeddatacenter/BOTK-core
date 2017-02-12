<?php
require_once __DIR__.'/../vendor/autoload.php';

$profile = array(
	'model'			=> 'LocalBusiness',
	'source'		=> 'https://www.dati.lombardia.it/Sanit-/Farmacie/cf6w-iiw9',
	'options'		=> array(
		'base' => array( 'default'=> 'http://salute.gov.it/resource/farmacie#')
	),
	'datamapper'	=> function(array $rawdata){
		$data = array();
		$data['id'] = $rawdata[2];
		$data['businessType']= 'schema:botk_farmacie';
		$data['businessName']= $rawdata[4];
		$data['streetAddress'] = $rawdata[5];
		$data['addressLocality'] = $rawdata[6];
		$data['telephone'] = $rawdata[7];
		$data['faxNumber'] = $rawdata[8];
		$data['email'] = $rawdata[9];
		$data['long'] = $rawdata[14];			
		$data['lat'] = $rawdata[13];
		
		return $data;
	},
);
$factsFactory = new \BOTK\FactsFactory($profile);

if (($handle = fopen("sample2.csv", "r")) !== FALSE) {
	$header = fgets($handle);
	echo $factsFactory->generateLinkedDataHeader();

    while (($rawdata = fgetcsv($handle, 2000, ",")) !== FALSE) {
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