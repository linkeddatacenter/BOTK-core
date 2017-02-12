
# BOTK\Core
[![Build Status](https://img.shields.io/travis/linkeddatacenter/BOTK-core.svg?style=flat-square)](http://travis-ci.org/linkeddatacenter/BOTK-core)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/linkeddatacenter/BOTK-core.svg?style=flat-square)](https://scrutinizer-ci.com/g/linkeddatacenter/BOTK-core)
[![Latest Version](https://img.shields.io/packagist/v/botk/core.svg?style=flat-square)](https://packagist.org/packages/botk/core)
[![Total Downloads](https://img.shields.io/packagist/dt/botk/core.svg?style=flat-square)](https://packagist.org/packages/botk/core)
[![License](https://img.shields.io/packagist/l/botk/core.svg?style=flat-square)](https://packagist.org/packages/botk/core)

Super lightweight classes for developing smart gateways to populate a business knowlege base 
compliant with [KEES](http://linkeddata.center/kees) model.

## quickstart

The package is available on [Packagist](https://packagist.org/packages/botk/core).
You can install it using [Composer](http://getcomposer.org).

```bash
composer require botk/core
```

Some code examples in samples directory.


## Installation

This package require [composer](http://getcomposer.org/).

Add following dependance to **composer.json** file in your project root:

```
    {
        "require": {
            "botk/core": "~5.0",
        }
    }
```


## BOTK Language profile

The BOTK language profile extends KEES (Knowledge Exchange Engine Schema) with some business related stuff. 
Following vocabularies are partially supported:

| Vocabulary									| Prefix	| Namespace										|
|-----------------------------------------------|-----------|-----------------------------------------------|
| [schema.org](http://schema.org) 				| schema:	| <http://schema.org/>							|
| [WGS 84](http://www.w3.org/2003/01/geo/)		| wgs:  	| <http://www.w3.org/2003/01/geo/wgs84_pos#> 	|
| [FOAF](http://xmlns.com/foaf/spec/)			| foaf:  	| <http://xmlns.com/foaf/0.1/> 					|
| [BOTK](http://linkeddata.center/botk/)		| foaf:  	| <http://linkeddata.center/botk/v1#> 			|
| [Dublin Core](http://purl.org/dc/terms/) 		| dct:  	| <http://purl.org/dc/terms/> 					|


This picture summarize the main concepts managed:

![UML schema](doc/uml.png)

The primary focus o BOTK are:
- Local Business, defined as a legal organization Business with at least a physical point of sell.
- Geograpic point of interest related with business.

BOTK extend schema.org with some custom resource related to businesses (see [Business Ontology](doc/ontology/README.md))

### RDF Localization

RDF language qualificator for string litterals are also used  to qualify a string code in a country domain.

For example: `ex:org1 schema:vatID "01209991007"@it` means that ex:org1 has an italian VAT. 
A smart business rule can infer `ex:org1 schema:vatID "IT01209991007"@fr` 

### https://schema.org/Organization

Captures a public legal registered business organization.

Following properties/annotations supported: 

- schema:vatID with cardinality <= 1, as Inverse Functional Property in country context ??
- schema:taxID with cardinality <= 1,
- schema:legalName with cardinality <= 1, as the legal name of the location, as Inverse Functional Property in country context
- schema:alternateName with cardinality >= 0
- schema:location with cardinality >= 0, a local business or a subsidiary

Example (in rdf turtle):
```
ex:org1 a schema:Organization ;
	schema:vatID "01209991007"@it ;
	schema:legalName """ERBORISTERIA "I PRATI" DI GIOVANNA MONAMI S.A.S.""""@it ;
	schema:alternateName """Erboristeria i prati sas"""@it ;
	schema:location ex:org1_pos1;
.
```

### https://schema.org/Place

Captures the POI (Point Of Interest) concept, that is a geographic point near witch there is something contactable.	
This class can be specialized  to state the reason of interest (e.g. see schema:LocalBusiness classifications).

Following properties/annotations supported: 

- rdf:type	with cardinality >= 0 , additional type in the form of prefix:classname
- schema:address  with cardinality >= 0 , contact info for this place
- schema:geo with cardinality <= 1

Example (in rdf turtle):
```
ex:org1_pos1 a schema:Place, schema:HealthAndBeautyBusiness, botk:RegisteredOffice ;	
	schema:address ex:org1_address1;
	schema:geo <geo:41.914001,12.464163>;
.
```


### https://schema.org/PostalAddress 

Captures a contact point with a postal address, telephone, fax, e-mail.

- schema:alternateName with cardinality >= 0 with the name of the  contact point (e.g. the shop name)
- schema:addressCountry with cardinality <= 1, Country  in two-letter ISO 3166-1 alpha-2 country code no language specs
- schema:addressLocality with cardinality <= 1, The locality. For example, Mountain View. Sholud be present in country adminstrative db as SKOS:primaryName
- schema:addressRegion	with cardinality <= 1, The region. Sholud be present in country adminstrative db as SKOS:primaryName
- schema:streetAddress	with cardinality <= 1,	possibly  normalizzed from template "DUF DUG, CIVIC" For example, "VIA ANTONIO MORDINI, 3"
- schema:postalCode	with cardinality <= 1,	Text 	The postal code. For example, 94043.
- schema:telephone with cardinality >= 0, formatted as string with no space, can start with '+', if 00 is present at the beginning,it is substituted with +, as primary telephone contact point for this location
- schema:faxNumber with cardinality >= 0, same formatting of telephone, as primary fax  contact point for this location
- schema:email with cardinality >= 0,  as  email for this contact

Example (in rdf turtle):
```
ex:org1_address1 a schema:PostalAddress ;
	schema:alternateName 
		"""Erboristeria i prati"""@it, 
		"""I PRATI"""@it, 
		"""I prati di Giovanna Monami"""@it;
	schema:addressCountry "IT";
	schema:addressLocality "ROMA"@it;
	schema:streetAddress """VIA ANTONIO MORDINI, 3"""@it;
	schema:addressRegion """RM"""@it;
	schema:postalCode "00195"@it;
	schema:telephone "063700061"@it ;
	schema:webPage <http://bit.ly/2k1GXs7> ;
.
```

### https://schema.org/GeoCoordinates

Captures the center of a geographic location

If lat and long are known, geo uri SHOULD be formatted according [rfc 5870](https://tools.ietf.org/html/rfc5870)
Following properties/annotations supported: 

- schema:alternateName with cardinality >= 0 possibly as formatted string ( "DUF DUG, CIVIC, ZIP LOCALITY, COUNTRY_ID", es "LUNGOLARIO CESARE BATTISTI, 5, 23900 LECCO LC, ITALY" ) 
- wgs:lat with cardinality <= 1,
- wgs:long with cardinality <= 1

Example (in rdf turtle):
```
<geo:41.914001,12.464163> a schema:GeoCoordinates ;
	schema:alternateName 
		"""VIA ANTONIO MORDINI, 3, 00195 ROMA RM, ITALY"""@it,
		"""Via A.Mordini num.3 - Roma"""@it;
	wgs:lat 41.914001 ;
	wgs:long 12.464163 ;
.
```

### owl:Thing

Following properties/annotations supported: 

- foaf:homepage strictly as Inverse Functional Property
- foaf:mbox strictly as as Inverse Functional Property
- foaf:page a web page related to the resource


## Reasoning

Following properties/entities should be (at least partially) supported in reasoning:

- owl:sameAs
- owl:FunctionalProperty
- owl:InverseFunctionalProperty
- rdfs:subClassOf


## License

 Copyright © 2017 by  Enrico Fagnoni at [LinkedData.Center](http://LinkedData.Center/)®

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

  
