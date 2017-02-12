<?php
require_once __DIR__.'/../../vendor/autoload.php';

$profile = array(
	'model'			=> 'LocalBusiness',
	'options'		=> array(
		'base' => array( 'default'=> 'urn:test:')
	),
	'datamapper'	=> function(array $rawdata){
		$data = array();
		$data['id'] = $rawdata[0];
		$data['alternateName'][] = $rawdata[2] . ' ' . $rawdata[1];
		$data['alternateName'][] = $rawdata[2];
		$data['vatID'] = $rawdata[3];
		$data['email'] = $rawdata[4];
		$data['addressLocality'] = $rawdata[5];
		$data['postalCode'] = $rawdata[7];
		$data['addressRegion'] = $rawdata[8];
		$data['streetAddress'] = $rawdata[9] . ' ' . $rawdata[10] . ', ' . $rawdata[11];
		$data['long'] = $rawdata[14];			
		$data['lat'] = $rawdata[15];
		
		return $data;
	},
);
$factsFactory = new \BOTK\FactsFactory($profile);

if (($handle = fopen("sample1.txt", "r")) !== FALSE) {
	$header = fgets($handle);
	echo $factsFactory->generateLinkedDataHeader();

    while (($rawdata = fgetcsv($handle, 2000, "|")) !== FALSE) {
    	try{
    		$facts =$factsFactory->factualize($rawdata);
    		echo $facts->asTurtle();
			$factsFactory->addToTripleCounter($facts->getTripleCount()) ;
    	}catch (Exception $e) {
		    echo '# Caught exception: ',  $e->getMessage(), "\n";
			$factsFactory->addError($e->getMessage());
		}
    }
	
	echo $factsFactory->generateLinkedDataFooter();
	
    fclose($handle);
	
}
?>