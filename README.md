![logo](http://linkeddata.center/resources/v4/logo/Logo-colori-trasp_oriz-640x220.png)
# BOTK\Core
[![Build Status](https://img.shields.io/travis/linkeddatacenter/BOTK-core.svg?style=flat-square)](http://travis-ci.com/linkeddatacenter/BOTK-core)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/linkeddatacenter/BOTK-core/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/linkeddatacenter/BOTK-core/?branch=master)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/linkeddatacenter/BOTK-core.svg?style=flat-square)](https://scrutinizer-ci.com/g/linkeddatacenter/BOTK-core)
[![Latest Version](https://img.shields.io/packagist/v/botk/core.svg?style=flat-square)](https://packagist.org/packages/botk/core)
[![Total Downloads](https://img.shields.io/packagist/dt/botk/core.svg?style=flat-square)](https://packagist.org/packages/botk/core)
[![License](https://img.shields.io/packagist/l/botk/core.svg?style=flat-square)](https://packagist.org/packages/botk/core)

Super lightweight base classes for developing smart gateways to populate RDF knowlege base.


## Installation

The package is available on [Packagist](https://packagist.org/packages/botk/core).
You can install it using [Composer](http://getcomposer.org):

	composer require botk/core

## Overview

This package provides some simple tools to transform  raw data into rdf linked data.

This package is compatible with [LinkedData.Center SDaaS architecture](http://linkeddata.center/home/sdaas)

It provides:

- a set of libraries to help gateways development
- a set of libraries to help reasoners development



## Libraries usage

The goal of the libraries is to simplify the conversion of raw data (e.g. .csv or  xml file) in RDF. There are tons of tools to do this job and this is yet another. The idea is just to use PHP to do trivial data conversion instead to build and configure complex tool.

With BOTK you define simple models to describe things  with  a plain set of properties ( e.g a Business Entity, a contact, a product). Then a FactFactory processor cleans data and translates attributes in a RDF graph according with BOTK language profile.
More or less this is what you have to do do when process csv or excel files row by row.

With BOTK libraries it is easy to create "gateways" ie processors that get in stdin a data stream producing in sdout a RDF turtle stream

For example this code snippet:

```php
$options = [
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

BOTK\SimpleCsvGateway::factory($options)->run();
```

processes this csv dataset:

	 id,url,name,aka 
	 1,http://linkeddata.center/,LinkedData.Center,LDC
	 2,https://github.org/,GitHub,


and produces something similar to this RDF turtle file:
	
	@prefix schema: <http://schema.org/> .
	
	<urn:yp:registry:1> 
		schema:alternateName "LDC","LinkedData.Center" ;
	    schema:url <http://linkeddata.center/> .
	
	<urn:yp:registry:2> 
		schema:alternateName "GitHub" ;
	    schema:url <https://github.org/> .


The the dataset processing is driven by the SimpleCsvGateway class that uses a set of options that you can override:

| SimpleCsvGateway option | default   | note                                         |
|-------------------------|-----------|----------------------------------------------|
| missingFactsIsError     | true      | if a missing fact should considered an error |
| bufferSize              | 2000      |                                              |
| skippFirstLine          | true      |                                              |
| fieldDelimiter          | ','       |                                              |
| factsProfile            | array ... | see below                                    |

**factsProfile** are processed by FactsFactory class that uses following options:

| factsProfile option | default               | note                                                                                                              |
|---------------------|-----------------------|-------------------------------------------------------------------------------------------------------------------|
| model               | LocalBusiness         | if no namespace specified BOTK\Model is used                                                                      |
| modelOptions        | array ...             | see below                                                                                                         |
| entityThreshold     | 100                   | in numbers of entity that trigger error resilence computation                                                     |
| resilienceToErrors  | 0.3                   | if more than 30% of error throws a TooManyErrorException                                                          |
| resilienceToInsanes | 0.9                   | if more than 90% of unacceptable data throws a TooManyErrorException                                              |
| source              | 'http://example.com/' | the dataset source url                                                                                            |
| datamapper          | function              | ** YOU must provide at least this function **                                                                     |
| dataCleaner         | function              | a function to clean fields, by default removes ampty fields                                                       |
| factsErrorDetector  | function              | a function that detects logical errors in facts, by default reurne false (i.e. error) if no rdf triples generated |
| rawdataSanitizer    | function              | a function that pre-validate raw data before processing, can be used as a filter                                  |


**modelOptions**  override the default field options provided by the selected model in the $DEFAULT_OPTIONS variable. 
For example see this code snippet extracted from [Thing model](src\Model\Thing.php) that is a superclass of [LocalBusiness model](src\Model\LocalBusiness.php)

Configuring models Options you can force field clenacing and validation.

```php
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
Note that not always a field  generate just a RDF triple: sometime the rdf generation processing requires to create blank nodes or to reference named node.
For named node generation the 'base' uri namespace is normally used ("urn:local:." by default)

See [more examples here](tests/system/gateways).


## Contributing to this project

See [Contributing guidelines](CONTRIBUTING.md)

## License

Copyright © 2018-2021 by [LinkedData.Center](http://LinkedData.Center/)®

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
