# Change Log
All notable changes to this project will be documented in this file.
This project adheres to [Semantic Versioning](http://semver.org/) and [changelog format](http://keepachangelog.com/)

## [Unreleased]

## 8.3.0

### added

- onStart and on succesHook in SimpleCsvGateway
-

## 8.2.5

### added

- compatibility with php 8 ( issue #19)

### removed

- obsolete system tests and their dependencies with old SDaaS platform versions


## 8.2.4

### fixed
- regression
- bug #12 #13 #15

### added

- added 'dct', 'void', and 'foaf' prefix to the Abstract Model
- documentUri to options to drive metadata printing

### removed

- provenance info
- system tests with old relase of sdaas

### changed

-  rdf footer


## 8.2.3

### fixed

- regression

## 8.2.2

### fixed

- regression

## 8.2.1

### added

- added 'dct', 'void', and 'foaf' prefix to the Abstract Model
- documentUri to options to drive metadata printing

### fixed

- bug #15

### removed

- provenance info

### changed

- footer

## 8.2.0

### fixed

- bugs #12 #13  

## removed

- system tests with old relase of sdaas

## 8.1.1

### added

- code quality badge
- added more unit tests

### fixed

- fixed default model in FactsFactory (interface bug)
- added getUri suffix optional parameter (interface bug)
- bug in getURI

## 8.1.0

Various bug fixed on v8 interface

### added

- system tests

## 8.0.0

A project re-factory that removes specific ontology model just keeping only the core classes.
Not good for production

### removed

- all models except things
- all unused test
- the language profile

### Changed

- to php 7.3
- to phpunit 9
- AbstractModel includes core $DEFAULT_OPTIONS (uri, base, id)

## 7.6.1


## fixed

- \mb_str.. in filters

## 7.6.0

### Changed

- Substituted vagrat with docker


### addded

- Abstract reasoner

## removed

- command line interface

## 7.5.1

### addded

- factsFactory as conatiner
- file argument

## 7.5.0

### added

- normalized quantitative value axioms
- botk application and postman	:reasoning command

## fixed

- botk:similarTo property

## removed

- botk:similarName propery

## 7.4.2

### added

- model code and properties for implementing Italian Postal code


## 7.4.1

### fixed

- email in LocalBusiness is string not an uri.


### changed

- readme fix

## 7.4.0 update language profile

### added

- added administrative area definition for italy

## 7.3.7 bug fixing release

### fixed

- bug #10



## 7.3.6 bug fixing release

### fixed

- fixed failing test


## 7.3.6 bug fixing release

### fixed

- fixed failing test


## 7.3.5 bug fixing release

### fixed

- bug #9 (taxID)

## 7.3.4 bug fixing release

## 7.3.3 bug fixing release

### fixed

- bugs in language profile documentation and Readme

## removed

- alternateNames.constuct and linkToCityConstruct


## 7.3.2 bug fixing release

### fixed

- bugs in language profile documentation


## 7.3.1 bug fixing release

### fixed

- bug in local_business_answers.ttl
- bug in linkCities.construct
- bugs in some queries


## 7.3.0

### Fixed

- sameas moved to sameAs in Thing


### Added

- silent option

 
## 7.2.0


### Changed

- README.md improvements

### Added

- restored CONTRIBUTING.md file
- example axioms
 

## 7.1.0

### Added

- property foundingDate in LocalBusiness
 
### Changed

- fixed cardinality errors in LocalBusiness
- refactory of rdf generation in BusinessContact
- fixed vocabulary description


## 7.0.2

### Changed

- minor bug fixing and code opimization

## 7.0.1

### Changed

- Bug in trevis config

## 7.0.0

### Changed

- Code refactory
- optimization in rdf generation
- minor changes to restrictions in BOTK language profile

### Added

- Product and Thing model 
- added sample6.php
- functional tests
- raptor installation in vagrant


## 6.4.0

### Added

- BusinessContact model with lot of new properties
- added sample5B.php


### Changed

- doc: CONTRIBUTING.MD merged with README.
- composer updated.

## 6.3.0


### Added

- added lot of new properties
- added sample5A.php

### Changed

- LocalBusiness rdf creation strategy

## 6.2.1

### Added

- added new properties: annualTurnover, netProfit, ateco2007 and EBITDA

## 6.2.0

### Added

- added new properties numberOfEmployees

### Changed
- changed botk namespace

### Fixed

- fixed error in entity count in FactsFactory

## 6.1.0

### Added

- addedd property numberOfEmployees

### Changed

- Refactory.
- Changed Model interface

## 6.0.0

Completelly rewitten

## 5.1.1 - 2016-01-29

### Fixed

- bug in AbstractCOntentNegotiation

## 5.1.0 - 2016-01-29

### Changed

- refactory of Standard Representation

## 5.0.1 - 2016-01-29
### Fixed

- removed install script problem

### Changed

- removed subversion dependency
- some project cleaning

## 5.0.0 - 2016-01-28

### Fixed
- html renderer bugs

### Removed

- removed xml renderer support (who care of a genetic xml application?)

### Changed

- moved default css to linkeddata.center resorces
- php unit refactory

### Added

- vagrant support
- contribution instructions
- badge management
- trevis-ci support
- scrutinizer support


## 4.0.1 - 2016-01-28

### Added

- integration with packagist
- changelog alignement

## 4.0.0 - 2016-01-28

### Added
- Moved from google code
- Code, doc and tests completed
