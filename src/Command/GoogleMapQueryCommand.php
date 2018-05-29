<?php
namespace BOTK\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use SKAgarwal\GoogleApi\PlacesApi;
use BOTK\FactsFactory;

class GoogleMapQueryCommand extends Command
{    
    protected function configure()
    {
        $this
        ->setName('google:places:reasoner')
        ->setDescription('Discover information about a local business using Google places APIs.')
        ->setHelp('This command search a name in google places returning a ttl file according botk Language profile....')
        ->addOption('key','k',  InputOption::VALUE_REQUIRED, 
            'the mandatory google place api key (see https://developers.google.com/places/web-service/get-api-key)'
        )
        ->addOption('namespace','u',  InputOption::VALUE_REQUIRED,
            'the namespace for created URI',
            'http://linkeddata.center/resource/'
        )
        ->addOption('delay','d',  InputOption::VALUE_REQUIRED,
            'delay each call of a fixed amount of seconds',
            0
        )
        ->addOption('skip','s', InputOption::VALUE_REQUIRED,
            'number of INPUT lines to skip',
            1
        )
        ->addOption('resilience','r', InputOption::VALUE_REQUIRED,
            'max number of errors tolerated before aborting',
            10
        )
        ->addOption('fields','f', InputOption::VALUE_REQUIRED,
            'detalis level required (none|contact)',
            'contact'
        )
        ->addOption('type','t', InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY,
            'additional RDF type as uri',
            array('http://schema.org/Place')
        )
        ->addOption('assert','a', InputOption::VALUE_REQUIRED,
            'asserted link predicate (sameAs|similarTo)',
            'similarTo'
        )
        ->addOption('limit','l', InputOption::VALUE_REQUIRED,
            'max number of calls to google APIs',
            4000
        );
    }
    
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {    
        //cache input parameters
        $uriNameSpace = $input->getOption('namespace');
        $limit = $input->getOption('limit');
        $sleepTime = $input->getOption('delay');
        $detailLevel = $input->getOption('fields');
        $resilience = $input->getOption('resilience');
        $types = $input->getOption('type');
        $similarityPredicate = $input->getOption('assert');
        
        if( !($key = $input->getOption('key'))){
            
            $helper = $this->getHelper('question');
            $question = new Question('Please enter your google place api key: ');
            $question->setValidator(function ($value) {
                if (trim($value) == '') {
                    throw new \Exception('The key cannot be empty');
                }
                
                return $value;
            });
            $question->setHidden(true);
            $question->setMaxAttempts(20);
            
            $key = $helper->ask($input, $output, $question);
        }
        
        $googlePlaces = new PlacesApi($key);
        $factsFactory = new FactsFactory( array(
            'model'			=> 'LocalBusiness',
            'modelOptions'		=> array(
                // override the default lowercase filter for id because placeId is case sensitive
                'id' => array('filter'=> FILTER_DEFAULT)
            )
        ));
        
        // print turtle prefixes
        echo $factsFactory->generateLinkedDataHeader();

        $lineCount=$callErrorCount = $consecutiveErrorsCount = $callCount = 0; 
        
        // skip input headers
        for ($i = 0; $i < $input->getOption('skip'); $i++) {
            $lineCount++;
            $output->writeln("<info># Ignored header $lineCount: ". trim(fgets(STDIN)) . '</info>'); 
        }
        
        // main input loop
        while( ($rawData= fgetcsv(STDIN)) && ($callCount <$limit)  ){
            $lineCount++;
            if(!is_array($rawData) || (count($rawData)!=2)) { 
                $output->writeln("<error># Ignored invalid row at line $lineCount.</error>");
                continue; 
            }
            list($uri, $query) = $rawData;
            
                      
            //--------------------------------------------------------------------------------
            // call google place textSearch api, tolerating some errors.
            //--------------------------------------------------------------------------------
            try {
                $searchResultsCollection=$googlePlaces->textSearch($query, array('region'=>'IT'));
                $consecutiveErrorsCount=0;
                $callCount++;
            } catch (\Exception $e) {
                $consecutiveErrorsCount++;$callErrorCount++;
                if( $consecutiveErrorsCount > $resilience ){
                    throw $e;
                }
                $messageString = trim(preg_replace('/\s+/', ' ', $e->getMessage()));
                $output->writeln("<error># Ignored Search Api ERROR ($consecutiveErrorsCount): $messageString</error>");
                continue;
            }
            
            // skip empty results
            if ($googlePlaces->getStatus()==='ZERO_RESULTS'){   
                $output->writeln("<info># no results for '$query'.</info>");
                continue;
            }
            
            $output->writeln("<info># discovered data for '$query'.</info>");
            // factualize textSearch results
            $result =$searchResultsCollection['results']->first();
            $placeId = $result['place_id'];
            $data['id'] = $placeId;
            $data['uri'] = $uriNameSpace . $placeId;
            $data['businessType'] = $types;
            $data[$similarityPredicate] = $uri; 
            
            if( isset($result['geometry']['location'])) {
                $data['lat'] = $result['geometry']['location']['lat'];
                $data['long'] = $result['geometry']['location']['lng'];
            }
            if( isset($result['formatted_address'])) {
                $data['addressDescription'] = $result['formatted_address'];
            }
            if( isset($result['name'])) {
                $data['businessName'] = $result['name'];
            }
            if( isset($result['types'])) {
                $data['disambiguatingDescription'] = $result['types'];
            } 
            

            //--------------------------------------------------------------------------------
            // call google place details api, tolerating some errors.
            //--------------------------------------------------------------------------------            
            if ($detailLevel==='contact') {
                try {
                    $details=$googlePlaces->placeDetails($placeId, array('region'=>'IT'));
                    $consecutiveErrorsCount=0;
                    $callCount++;
                } catch (\Exception $e) {
                    $consecutiveErrorsCount++;$callErrorCount++;
                    if( $consecutiveErrorsCount > $resilience){
                        throw $e;
                    }
                    $messageString = trim(preg_replace('/\s+/', ' ', $e->getMessage()));
                    $output->writeln("<error># Ignored Details Api ERROR ($consecutiveErrorsCount): $messageString</error>");
                }
                
                // skip empty results
                if ('OK' === $googlePlaces->getStatus()){               
                    // factualize placeDetails results
                    $result =$details['result'];
                    if( isset($result['address_components'][1]['short_name']) ) {
                        $data['streetAddress'] = $result['address_components'][1]['short_name'];
                    }
                    if( isset($result['address_components'][0]['short_name']) ) {
                        $data['streetAddress'] .= ', ' . $result['address_components'][0]['short_name'];
                    }
                    if( isset($result['address_components'][3]['short_name']) ) {
                        $data['addressLocality'] = $result['address_components'][3]['short_name'];
                    }
                    if( isset($result['address_components'][0]['short_name']) ) {
                        $data['addressRegion'] = $result['address_components'][4]['short_name'];
                    }
                    if( isset($result['address_components'][5]['short_name']) ) {
                        $data['addressRegioneIstat'] = $result['address_components'][5]['short_name'];
                    }
                    if( isset($result['address_components'][7]['short_name']) ) {
                        $data['postalCode'] = $result['address_components'][7]['short_name'];
                    }
                    if( isset($result['formatted_phone_number']) ) {
                        $data['telephone'] = $result['formatted_phone_number'];
                    }
                    if( isset($result['website']) ) {
                        $data['page'] = $result['website'];
                    }
                    if( isset($result['url']) ) {
                        $data['hasMap'] = $result['url'];
                    }
                } else {                  
                    $output->writeln("<info># no details for place id '$placeId' details</info>");
                }
            }
            
            try {
                $facts =$factsFactory->factualize($data);
                echo $facts->asTurtleFragment(), "\n";
                $droppedFields = $facts->getDroppedFields();
                if(!empty($droppedFields)) {
                    $output->writeln("<error># Dropped ".implode(", ", $droppedFields).'</error>');
                    $this->factsFactory->addToCounter('error');
                }
            } catch (\BOTK\Exception\Warning $e) {
                $output->writeln("<comment># ".$e->getMessage().'</comment>');
            } 
            
            
            sleep($sleepTime);
        }
        
        if ($callCount >= $limit && $placeId) {
            $output->writeln("<comment># Api call limit reached ($callCount).</comment>");
        }
        
        // prints provenances and other metadata
        echo $factsFactory->generateLinkedDataFooter();
        $output->writeln("<info># Called $callCount APIs, $callErrorCount errors.</info>");
    }
}