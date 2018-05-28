<?php
namespace BOTK\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use SKAgarwal\GoogleApi\PlacesApi;

class GoogleMapQueryCommand extends Command
{
    const GOOGLE_PLACES_PREFIX = 'http://google.com/resource/place_';
    
    private $factsFactory;
    
    public function __construct(\BOTK\FactsFactory $factsFactory)
    {
        $this->factsFactory = $factsFactory;
        
        parent::__construct();
    }

    
    protected function configure()
    {
        $this
        ->setName('google:places:search')
        ->setDescription('Search a Place using Google places APIs.')
        ->setHelp('This command search a name in google places returning a ttl file according botk Language profile....')
        ->addOption('key','k',  InputOption::VALUE_REQUIRED, 
            'the google place api key or an environment variable that contains the api key prefixed by "@"',
            '@BOTK_API_KEY'
        )
        ->addOption('delay','d',  InputOption::VALUE_REQUIRED,
            'delay each call of a fixed amount of seconds',
            0
        )
        ->addOption('skip','s', InputOption::VALUE_REQUIRED,
            'number of INPUT lines to skip',
            0
        )
        ->addOption('resilience','r', InputOption::VALUE_REQUIRED,
            'max number of errors tolerated before aborting',
            10
        )
        ->addOption('details','t', InputOption::VALUE_REQUIRED,
            'detalis level required (none|contact)',
            'contact'
        )
        ->addOption('limit','l', InputOption::VALUE_REQUIRED,
            'max number of calls to google APIs',
            4000
        );
    }
    
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {    
        //input paramaters
        $limit = $input->getOption('limit');
        $sleepTime = $input->getOption('delay');
        $detailLevel = $input->getOption('details');
        $resilience = $input->getOption('resilience');
        $key = $input->getOption('key');
        
        // get google places api key, reading it from an environment variable if requested
        if ( (strlen($key)>1) && $key[0]==='@') {
            $key = getenv(trim($key, '@'));
        }
        $googlePlaces = new PlacesApi($key);
        
        // print turtle prefixes
        echo $this->factsFactory->generateLinkedDataHeader();

        // skip input headers
        for ($i = 0; $i < $input->getOption('skip'); $i++) {
            $output->writeln("<info># Ignored header: ". fgets(STDIN) . '</info>'); 
        }
        
        // main input loop
        $lineCount=$callErrorCount = $consecutiveErrorsCount = $callCount = 0; 
        while( ($rawData= fgetcsv(STDIN)) && ($callCount <$limit)  ){
            $lineCount++;
            if(!is_array($rawData) || (count($rawData)!=2)) { 
                $output->writeln("<error># Invalid row found at line $lineCount.</error>");
                continue; 
            }
            list($uri, $query) = $rawData;
            
                      
            //--------------------------------------------------------------------------------
            // call google place textSearch api, tolerating some errors.
            //--------------------------------------------------------------------------------
            try {
                $searchResultsCollection=$googlePlaces->textSearch($query, array('region'=>'IT'));
                $consecutiveErrorsCount=0;
                sleep($sleepTime);
                $callCount++;
            } catch (\Exception $e) {
                $consecutiveErrorsCount++;$callErrorCount++;
                if( $consecutiveErrorsCount > $resilience ){
                    throw $e;
                }
                $output->writeln("<error># Ignored Api ERROR ($consecutiveErrorsCount): ". $e->getMessage().'</error>');
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
            $data['uri'] = self::GOOGLE_PLACES_PREFIX . $placeId;;
            $data['businessType'] = 'http://schema.org/Place';
            $data['similarTo'] = $uri; 
            
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
                    $output->writeln("<error># Ignored Api ERROR ($consecutiveErrorsCount): ". $e->getMessage().'</error>');
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
                $facts =$this->factsFactory->factualize($data);
                echo $facts->asTurtleFragment(), "\n";
                $droppedFields = $facts->getDroppedFields();
                if(!empty($droppedFields)) {
                    $output->writeln("<error># Dropped ".implode(", ", $droppedFields).'</error>');
                    $this->factsFactory->addToCounter('error');
                }
            } catch (\BOTK\Exception\Warning $e) {
                $output->writeln("<comment># ".$e->getMessage().'</comment>');
            } 
        }
        
        if ($callCount >= $limit && $placeId) {
            $output->writeln("<comment># Api call limit reached ($callCount).</comment>");
        }
        
        // prints provenances and other metadata
        echo $this->factsFactory->generateLinkedDataFooter();
        $output->writeln("<info># Called $callCount APIs, $callErrorCount errors.</info>");
    }
}