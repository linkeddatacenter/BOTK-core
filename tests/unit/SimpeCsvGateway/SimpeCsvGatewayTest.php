<?php
use PHPUnit\Framework\TestCase;

class SimpleCsvGatewayTest extends TestCase
{
    
    public function testCsvTransformation()
    {
        $fpIn = fopen( __DIR__.'/input.csv', 'r');
        $fpOut = fopen('/tmp/output.ttl', 'w');
        
        $options = [
            'inputStream'      => $fpIn,
            'outputStream'      => $fpOut,
            'factsProfile' => [
                'model' => 'SampleSchemaThing',
                'modelOptions' => [
                    'base' => [ 'default'=> 'urn:yp:registry:' ]
                ],
                'datamapper'	=> function($rawdata){
                    $data = array();
                    $data['identifier'] = $rawdata[0];
                    $data['homepage'] =  $rawdata[1];
                    $data['alternateName'] = [ $rawdata[2], $rawdata[3]] ;
                    return $data;
                },
                'rawdataSanitizer' => function( $rawdata){
                    return (count($rawdata)==4)?$rawdata:false;
                },
             ],
            'skippFirstLine'	=> true,
            'fieldDelimiter' => ','
        ];
        
        $factory= BOTK\SimpleCsvGateway::factory($options);
        $factory->run();
        
        fclose($fpIn);
        fclose($fpOut);

        $this->assertEquals(['SampleSchemaThing'],$factory->getStorage());
        $this->assertEquals(file_get_contents('/tmp/output.ttl'),file_get_contents(__DIR__.'/output.ttl'));
    }

}

