# BOTK\Core
[![Build Status](https://img.shields.io/travis/linkeddatacenter/BOTK-core.svg?style=flat-square)](http://travis-ci.org/linkeddatacenter/BOTK-core)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/linkeddatacenter/BOTK-core.svg?style=flat-square)](https://scrutinizer-ci.com/g/linkeddatacenter/BOTK-core)
[![Latest Version](https://img.shields.io/packagist/v/botk/core.svg?style=flat-square)](https://packagist.org/packages/botk/core)
[![Total Downloads](https://img.shields.io/packagist/dt/botk/core.svg?style=flat-square)](https://packagist.org/packages/botk/core)
[![License](https://img.shields.io/packagist/l/botk/core.svg?style=flat-square)](https://packagist.org/packages/botk/core)

Super lightweight classes and ontologies for developing smart gateways to populate a business knowlege base.


## Installation

The package is available on [Packagist](https://packagist.org/packages/botk/core).
You can install it using [Composer](http://getcomposer.org).

Add following dependance to **composer.json** file in your project root:

```
    {
        "require": {
            "botk/core": "~7.2",
        }
    }
```

## Usage

This package provides some simple tools to transform  raw data into rdf linked data according [BOTK language profile](vocabularies).

This package is compatible both with [KEES architecture](http://linkeddata.center/kees and with [LinkedData.Center SDaaS plans](http://linkeddata.center/home/sdaas)

For example this code snipplet:

```
<?php
require_once __DIR__.'/../vendor/autoload.php';

$options = array(
	'source' => 'urn:dataset:example2',
	'factsProfile' => array(
		'datamapper'	=> function(array $rawdata){
			$data = array();
			$data['id'] = $rawdata[2];
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
	),
);

BOTK\SimpleCsvGateway::factory($options)->run();
```

processes [this csv dataset](examples/input/sample2.csv) producing  this rdf file:

```
@prefix botk: <http://linkeddata.center/botk/v1#> .
@prefix daq: <http://purl.org/eis/vocab/daq#> .
@prefix dct: <http://purl.org/dc/terms/> .
@prefix dcterms: <http://purl.org/dc/terms/> .
@prefix foaf: <http://xmlns.com/foaf/0.1/> .
@prefix geo: <http://www.opengis.net/ont/geosparql#> .
@prefix kees: <http://linkeddata.center/kees/v1#> .
@prefix owl: <http://www.w3.org/2002/07/owl#> .
@prefix prov: <http://www.w3.org/ns/prov#> .
@prefix qb: <http://purl.org/linked-data/cube#> .
@prefix rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#> .
@prefix rdfs: <http://www.w3.org/2000/01/rdf-schema#> .
@prefix schema: <http://schema.org/> .
@prefix sd: <http://www.w3.org/ns/sparql-service-description#> .
@prefix skos: <http://www.w3.org/2004/02/skos/core#> .
@prefix void: <http://rdfs.org/ns/void#> .
@prefix wgs: <http://www.w3.org/2003/01/geo/wgs84_pos#> .
@prefix xml: <http://www.w3.org/XML/1998/namespace> .
@prefix xsd: <http://www.w3.org/2001/XMLSchema#> .

<geo:45.822059,8.818115> schema:latitude "45.822059"^^xsd:float ;
    schema:longitude "8.818115"^^xsd:float .

<http://salute.gov.it/resource/farmacie#3876> a schema:LocalBusiness;
    dct:identifier "3876" ;
    schema:address <http://salute.gov.it/resource/farmacie#3876_address> ;
    schema:alternateName "Farmacia DELLA BRUNELLA Dr. Prof. A. RIGAMONTI" ;
    schema:email <file:///base/data/home/apps/s%7Erdf-translator/1.380697414950152317/FARMACIA_BRUNELLA@LIBERO.IT> ;
    schema:faxNumber "0332214856" ;
    schema:telephone "0332289300" .

<http://salute.gov.it/resource/farmacie#3876_address> a schema:PostalAddress ;
    schema:addressCountry "IT" ;
    schema:addressLocality "VARESE" ;
    schema:description "VIA SALVO D'ACQUISTO, 2, VARESE" ;
    schema:streetAddress "VIA SALVO D'ACQUISTO, 2" .

...

<> prov:generatedAtTime "2017-08-14T08:24:03+00:00"^^xsd:dateTime;dct:source <urn:dataset:example2>;foaf:primaryTopic <#dataset>.
<#dataset> a void:Dataset; void:datadump <>;void:triples 274 ;void:entities 18.
# Generated 274 good triples from 18 entities (0 ignored), 0 errors

```

The the dataset processing is driven by the SimpleCsvGateway class that uses a set of options that you can override:

| SimpleCsvGateway option | default | note |
|--------|---------|------|
| missingFactsIsError | true | if a missing fact should considered an error
| bufferSize | 2000 | |
| skippFirstLine | true | |
| fieldDelimiter | ',' | |
| factsProfile | array ...| see below |


**factsProfile** are processed bt FactsFactory class that use following options:

| factsProfile option | default | note |
|--------|---------|------|
| model | LocalBusiness | if no namespace spceified BOTK\Model is used |
| modelOptions | array ...| see below |
| entityThreshold | 100 | in numbers of entity that trigger error resilence computation |
| resilienceToErrors | 0.3 | if more than 30% of error throws a TooManyErrorException |
| resilienceToInsanes | 0.9 | if more than 90% of unacceptable data throws a TooManyErrorException |
| source | 'http://example.com/' | the dataset source url |
| datamapper | function | ** YOU must provide at least this function ** |
| dataCleaner | function |  a function to clean fields, by default removes ampty fields |
| factsErrorDetector | function |  a function that detects logical errors in facts, by default reurne false (i.e. error) if no rdf triples generated |
| rawdataSanitizer |  function | a function that pre validate raw data before processing, can be used as a filter |


**modelOptions** are override to the default field options provided by the selected model in the $DEFAULT_OPTIONS variable. 
For example see this code snippet extracted from [Thing model](src\Model\Thing.php) that is a superclass of [LocalBusiness model](src\Model\LocalBusiness.php)

```
...
	'uri' => array(
		'filter'    => FILTER_CALLBACK,
		'options' 	=> '\BOTK\Filters::FILTER_VALIDATE_URI',
		'flags'  	=> FILTER_REQUIRE_SCALAR
	),
	'base' => array(
		'default'	=> 'urn:local:',
		'filter'    => FILTER_CALLBACK,
		'options' 	=> '\BOTK\Filters::FILTER_VALIDATE_URI',
		'flags'  	=> FILTER_REQUIRE_SCALAR
	),
	'id' => array(
		'filter'    => FILTER_CALLBACK,
		'options' 	=> '\BOTK\Filters::FILTER_SANITIZE_ID',
		'flags'  	=> FILTER_REQUIRE_SCALAR
	),
	'page' => array(	
		'filter'    => FILTER_CALLBACK,
		'options' 	=> '\BOTK\Filters::FILTER_SANITIZE_HTTP_URL',
		'flags'  	=> FILTER_FORCE_ARRAY
	),
...
```

a field definition drives the process of data cleansing and rdf generation that is provided by model implementation.
Note that not always a field  generate just a RDF triple: sometime the rdf genartion processing requires to create blank nodes or to reference named node.
For named node generation the 'base' uri namespace is normally used ("urn:local:." by default)

See [more examples here](examples).

## Contributing to this project

See [Contributing guidelines](CONTRIBUTING.md)

## License

Copyright © 2017 by [LinkedData.Center](http://LinkedData.Center/)®

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
