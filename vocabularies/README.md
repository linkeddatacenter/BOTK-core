# BOTK Language profile

The BOTK language profile is a Semantic Web application that extends schema.org with some business related stuff. 
BOTK is compatible with KEES (Knowledge Exchange Engine Schema) applications.

Following vocabularies are partially supported:

| Vocabulary																| Prefix	| Namespace											|
|---------------------------------------------------------------------------|-----------|---------------------------------------------------|
| [xml schema](http://www.w3.org/2001/XMLSchema) 							| xsd:		| <http://www.w3.org/2001/XMLSchema#> 				|
| [rdf](https://www.w3.org/TR/rdf-schema) 									| rdf:		| <http://www.w3.org/1999/02/22-rdf-syntax-ns#> 	|
| [rdfs](http://www.w3.org/2000/01/rdf-schema) 								| rdfs:		| <http://www.w3.org/2000/01/rdf-schema#>			|
| [owl](http://www.w3.org/2002/07/owl) 										| owl:		| <http://www.w3.org/2002/07/owl#> 					|
| [Dublin Core](http://purl.org/dc/terms/) 									| dct:  	| <http://purl.org/dc/terms/> 						|
| [VoiD](https://www.w3.org/TR/vocab-data-cube/#bib-void)					| void:  	| <http://rdfs.org/ns/void#> 						|
| [PROV](https://www.w3.org/TR/prov-o/)										| prov:  	| <	http://www.w3.org/ns/prov#> 					|
| [schema.org](http://schema.org) 											| schema:	| <http://schema.org/>								|
| [WGS 84](http://www.w3.org/2003/01/geo/)									| wgs:  	| <http://www.w3.org/2003/01/geo/wgs84_pos#> 		|
| [FOAF](http://xmlns.com/foaf/spec/)										| foaf:  	| <http://xmlns.com/foaf/0.1/> 						|
| [Data Cube](https://www.w3.org/TR/vocab-data-cube/)						| qb:  		| <http://purl.org/linked-data/cube#> 				|
| [DaQ framework](http://butterbur04.iai.uni-bonn.de/ontologies/daq/daq)	| daq:  	| <http://purl.org/eis/vocab/daq#> 					|
| [SPARQL service](http://butterbur04.iai.uni-bonn.de/ontologies/daq/daq)	| sd:  		| <http://www.w3.org/ns/sparql-service-description#>|
| [SKOS](https://www.w3.org/TR/skos-reference/)								| skos:  	| <http://www.w3.org/2004/02/skos/core#> 			|
| [KEES](http://linkeddata.center/kees)										| kees:  	| <http://linkeddata.center/kees/v1#> 				|
| [BOTK](https://github.com/linkeddatacenter/BOTK-core)						| botk:  	| <http://linkeddata.center/botk/v1#> 				|



The primary focus o BOTK are Local Business, defined as a legal organization Business with a physical postal address contact point. 
BOTK vocabulary extends schema.org with some custom resource related to businesses:


## BOTK Axioms

## String context
No string context should be used to qualify strings.



### schema:QuantitativeValue

An a-dimentional range of values with following restriction:

- schema:minValue with cardinality <= 1
- schema:maxValue with cardinality <= 1,
- schema:value with cardinality <= 1,

if schema:value with cardinality = 1 then schema:minValue = schema:maxValue =schema:minValue i.e.:

```
construct { ?s schema:minValue ?value; $schema:maxValue ?value} 
where {
	?s schema:value ?value
}
```


## schema:LocalBusiness

Captures the concept about a public legal registered business organization  with a contactable official contact point .	

It is a subclass of schema:LocalBusiness that adds some properties an drestrictions.
This class can be specialized  to state the reason of the business interest (e.g. see schema:LocalBusiness classifications).


Following properties/annotations supported: 

- rdf:type	with cardinality >= 1 ,  type in the form of prefix:classname at least schema:LocalBusiness must be present
- schema:vatID with cardinality <= 1, as (NB. is an Inverse Functional Property in country context)
- schema:taxID with cardinality <= 1,
- schema:legalName with cardinality <= 1, as the legal name of the location
- schema:alternateName with cardinality >= 0, contains the insignia of the shop as normalized text
- schema:address  with cardinality <=1 , contact info for this local business  as a schema:PostalAddress individual
- schema:geo with cardinality <= 1 pointer a schema:GeoCoordinates
- schema:hasMap with cardinality >= 0 pointer an URL with a map of the place
- schema:telephone with cardinality >= 0, formatted as string with no space, can start with '+', if 00 is present at the beginning,it is substituted with +, as primary telephone contact point for this location
- schema:faxNumber with cardinality >= 0, same formatting of telephone, as primary fax  contact point for this organization
- schema:email with cardinality >= 0,  as  email for a contact point for this organization
- schema:aggregateRating with cardinality <= 1,  as a schema:AggregateRating instance, schema:ratingValue attribute must be present as non negative float value
- schema:openingHours with cardinality <= 1,  if  ="permanantly closed" the local business is permanantly closed
- schema:department with cardinality >= 0,  as an office or sub organization
- botk:ateco2007 with cardinality <=1
- botk:naceV2 with cardinality <=1
- schema:isicV4 with cardinality <=1
- botk:hasServerManufacturer with cardinality <=1
- botk:hasServerVirtualizationManufacturer with cardinality <=1
- botk:hasDASManufacturer with cardinality <=1
- botk:hasNASManufacturer with cardinality <=1
- botk:hasSANManufacturer with cardinality <=1
- botk:hasTapeLibraryManufacturer with cardinality <=1
- botk:hasStorageVirtualizationManufacturer with cardinality <=1
- botk:naics with cardinality <=1
- botk:hasNAFCode with cardinality <=1
- botk:hasServerSeries with cardinality <=1
- botk:hasDesktopManufacturer with cardinality <=1
- botk:hasLaptopManufacturer with cardinality <=1
- botk:hasDesktopVirtualizationManufacturer with cardinality <=1
- botk:hasWorkstationManufacturer with cardinality <=1
- botk:hasNetworkPrinterManufacturer with cardinality <=1
- botk:hasHighVolumePrinterManufacturer with cardinality <=1
- botk:hasCopierManufacturer with cardinality <=1
- botk:hasUPSManufacturer with cardinality <=1
- botk:hasERPSuiteVendor with cardinality <=1
- botk:hasERPSoftwareasaServiceManufacturer with cardinality <=1
- botk:hasAppServerSoftwareVendor with cardinality <=1
- botk:hasBusIntellSoftwareVendor with cardinality <=1
- botk:hasCollaborativeSoftwareVendor with cardinality <=1
- botk:hasCRMSoftwareVendor with cardinality <=1
- botk:hasCRMSoftwareasaServiceManufacturer with cardinality <=1
- botk:hasDocumentMgmtSoftwareVendor with cardinality <=1
- botk:hasAppConsolidationSoftwareVendor with cardinality <=1
- botk:hasHumanResourceSoftwareVendor with cardinality <=1
- botk:hasSupplyChainSoftwareVendor with cardinality <=1
- botk:hasWebServiceSoftwareVendor with cardinality <=1
- botk:hasDatawarehouseSoftwareVendor with cardinality <=1
- botk:hasSaaSVendor with cardinality <=1
- botk:hasEmailMessagingVendor with cardinality <=1
- botk:hasEmailSaaSManufacturer with cardinality <=1
- botk:hasOSVendor with cardinality <=1
- botk:hasOSModel with cardinality <=1
- botk:hasDBMSVendor with cardinality <=1
- botk:hasAcctingVendor with cardinality <=1
- botk:hasAntiVirusVendor with cardinality <=1
- botk:hasAssetManagementSoftwareVendor with cardinality <=1
- botk:hasEnterpriseManagementSoftwareVendor with cardinality <=1
- botk:hasIDAccessSoftwareVendor with cardinality <=1
- botk:hasStorageManagementSoftwareVendor with cardinality <=1
- botk:hasStorageSaaSManufacturer with cardinality <=1
- botk:hasEthernetTechnology with cardinality <=1
- botk:haseCommerceType with cardinality <=1
- botk:hasHostorRemoteStatus with cardinality <=1
- botk:hasNetworkLineCarrier with cardinality <=1
- botk:hasVideoConfServicesProvider with cardinality <=1
- botk:hasUnifiedCommSvcProvider with cardinality <=1
- botk:hasRouterManufacturer with cardinality <=1
- botk:hasSwitchManufacturer with cardinality <=1
- botk:hasVPNManufacturer with cardinality <=1
- botk:hasISP with cardinality <=1
- botk:hasNetworkServiceProvider with cardinality <=1
- botk:hasPhoneSystemManufacturer with cardinality <=1
- botk:hasVoIPManufacturer with cardinality <=1
- botk:hasVoIPHosting with cardinality <=1
- botk:hasLongDistanceCarrier with cardinality <=1
- botk:hasWirelessProvider with cardinality <=1
- botk:hasPhoneSystemMaintenanceProvider with cardinality <=1
- botk:hasSmartphoneManufacturer with cardinality <=1
- botk:hasSmartphoneOS with cardinality <=1
- botk:hasFYE with cardinality <=1


Beside some  following statistical dimensions apply; all these properties have  cardinality <=1 and range schema:LocalBusiness:

- schema:numberOfEmployees expressend in number of employees
- botk:annualTurnover expressend in Thousand EURO
- botk:netProfit expressend in Thousand EURO
- botk:ebitda last known  net earnings, before interest expenses, taxes, depreciation and amortization , expressend in Thousand EURO
- botk:hasTotDevelopers expressend in number of people
- botk:itBudget expressend in Thousand EURO
- botk:itStorageBudget expressend in Thousand EURO
- botk:itHardwareBudget expressend in Thousand EURO
- botk:itServerBudget expressend in Thousand EURO
- botk:softwareBudget expressend in Thousand EURO		
- botk:hasITEmployees expressend in in number of people
- botk:hasNumberOfPCs expressend in in pcs
- botk:hasITBudget expressend in Thousand EURO	
- botk:hasTablets expressend in pcs
- botk:hasWorkstations expressend in in pcs
- botk:hasStorageBudget expressend in Thousand EURO	
- botk:hasServerBudget expressend in Thousand EURO	
- botk:hasServers expressend in pcs
- botk:hasDesktop expressend in pcs
- botk:hasLaptops expressend in pcs
- botk:hasPrinters expressend in pcs
- botk:hasMultifunctionPrinters expressend in pcs
- botk:hasColorPrinter expressend in pcs
- botk:hasInternetUsers expressend in people
- botk:hasWirelessUsers expressend in people
- botk:hasNetworkLines expressend in pcs
- botk:hasRouters expressend in pcs
- botk:hasStorageCapacity expressend in TB 
- botk:hasExtensions expressend in pcs
- botk:hasTotCallCenterCallers expressend in people
- botk:hasThinPC expressend in pcs
- botk:hasSalesforce expressend in pcs
- botk:hasRevenue expressend in Thousand EURO	
- botk:hasCommercialBudget expressend in Thousand EURO	
- botk:hasHardwareBudget expressend in Thousand EURO	
- botk:hasSoftwareBudget expressend in Thousand EURO	
- botk:hasOutsrcingBudget expressend in Thousand EURO	
- botk:hasOtherHardwareBudget expressend in Thousand EURO	
- botk:hasPCBudget expressend in Thousand EURO	
- botk:hasPrinterBudget expressend in Thousand EURO	
- botk:hasTerminalBudget expressend in Thousand EURO	
- botk:hasPeripheralBudget expressend in Thousand EURO	
- botk:hasDesktopPrinters expressend in pcs	
- botk:hasNetworkPrinters expressend in pcs
- botk:hasSmartphoneUsers expressend in pc s
- botk:hasEnterpriseSmartphoneUsers expressend in users



Example1 (in rdf turtle):
```
ex:org1 a schema:LocalBusiness, schema:HealthAndBeautyBusiness ;
	schema:vatID "01209991007" ;
	schema:legalName "ERBORISTERIA \"I PRATI\" DI GIOVANNA MONAMI S.A.S." ;
	schema:alternateName "ERBORISTERIA I PRATI", "I PRATI", "I PRATI DI GIOVANNA MONAMI" ;
	schema:telephone "063700061" ;
	schema:email "INFO@IPRATI.IT" ;
	schema:address ex:org1_address1;
	schema:geo <geo:41.914,12.464163>;
	schema:aggregateRating [ a schema:AggregateRating; schema:ratingValue 4.1 ];
	schema:hasMap <https://maps.google.com/?cid=11195466023234233409836>;
	botk:numberOfEmployees [ schema:maxValue 10 ];
	botk:annualTurnover [ schema:minValue 1000; schema:maxValue 10000 ];
.

```

Example 2 (in rdf turtle):

```
<urn:aberdeen:S308001886> a schema:LocalBusiness ;
    botk:hasAcctingVendor "OTHER" ;
    botk:hasAntiVirusVendor "TREND-MICRO" ;
    botk:hasCommercialBudget <urn:aberdeen:13130> ;
    botk:hasDBMSVendor "IBM" ;
    botk:hasDesktop <urn:aberdeen:68> ;
    botk:hasDesktopManufacturer "OTHER" ;
    botk:hasDesktopPrinters <urn:aberdeen:8> ;
    botk:hasExtensions <urn:aberdeen:1> ;
    botk:hasFYE "DEC" ;
    botk:hasHardwareBudget <urn:aberdeen:10400> ;
    botk:hasHumanResourceSoftwareVendor "BESPOKE" ;
    botk:hasISP "INFRACOM" ;
    botk:hasITBudget <urn:aberdeen:92560> ;
    botk:hasITEmployees <urn:aberdeen:1to4> ;
    botk:hasLaptopManufacturer "HP" ;
    botk:hasLaptops <urn:aberdeen:24> ;
    botk:hasLongDistanceCarrier "OTHER" ;
    botk:hasMultifunctionPrinters <urn:aberdeen:18> ;
    botk:hasNetworkLineCarrier "OTHER" ;
    botk:hasNetworkLines <urn:aberdeen:1> ;
    botk:hasNetworkPrinters <urn:aberdeen:12> ;
    botk:hasNumberOfPCs <urn:aberdeen:102> ;
    botk:hasOutsrcingBudget <urn:aberdeen:38740> ;
    botk:hasPCBudget <urn:aberdeen:6983> ;
    botk:hasPrinters <urn:aberdeen:27> ;
    botk:hasRouterManufacturer "CISCO" ;
    botk:hasRouters <urn:aberdeen:2> ;
    botk:hasSANManufacturer "IBM" ;
    botk:hasServerBudget <urn:aberdeen:121> ;
    botk:hasServerManufacturer "IBM" ;
    botk:hasServerSeries "XSERIES" ;
    botk:hasServerVirtualizationManufacturer "VMWARE" ;
    botk:hasServers <urn:aberdeen:4> ;
    botk:hasSoftwareBudget <urn:aberdeen:30290> ;
    botk:hasStorageBudget <urn:aberdeen:647> ;
    botk:hasStorageCapacity <urn:aberdeen:100to500> ;
    botk:hasStorageManagementSoftwareVendor "OTHER" ;
    botk:hasTablets <urn:aberdeen:4> ;
    botk:hasTapeLibraryManufacturer "IBM" ;
    botk:hasTotDevelopers <urn:aberdeen:1to4> ;
    botk:hasWirelessProvider "OTHER" ;
    botk:hasWorkstations <urn:aberdeen:5> ;
    botk:naceV2 "94.11" ;
    botk:naics "813990" ;
    dct:identifier "308001886" ;
    schema:address <urn:aberdeen:S308001886_address> ;
    schema:alternateName "CAMERA DI COMMERCIO INDUSTRIA ARTIGIANATO E AGRICOLTURA DI TRENTO" ;
    schema:faxNumber "461239853" ;
    schema:isicV4 "8699" ;
    schema:numberOfEmployees <urn:aberdeen:130> ;
    schema:parentOrganization <urn:aberdeen:E03733284> ;
    schema:vatID "00262170228" ;
    foaf:homepage <http://tn.camcom.it> .
```

## schema:PostalAddress 

Captures a contact point with a postal address
- schema:description with cardinality >= 1 possibly as normalized string from template "DUF DUG, CIVIC, ZIP LOCALITY", es "LUNGOLARIO CESARE BATTISTI, 5, 23900 LECCO LC" ) 
- schema:addressCountry with cardinality = 1, Country  in two-letter ISO 3166-1 alpha-2 country code no language specs
- schema:addressLocality with cardinality <= 1, The locality as normalized string. For example, "MILANO". Should be present in an official country adminstrative db as SKOS:primaryName or rdfs:label
- schema:addressRegion	with cardinality <= 1, The second administrative level as normalized string. For example, "MI". . Should be present in country adminstrative db as SKOS:primaryName
- schema:streetAddress	with cardinality <= 1,	a normalizzed string from template "DUF DUG, CIVIC". For example, "VIA ANTONIO MORDINI, 3"
- schema:postalCode	with cardinality <= 1,	Text 	The postal code. For example, 94043.

Example (in rdf turtle):
```
ex:org1_address1 a schema:PostalAddress ;	
	schema:description 
		"VIA ANTONIO MORDINI, 3, 00195 ROMA RM",
		"VIA ANTONIO MORDINI, 3 - ROMA";
	schema:addressCountry "IT";
	schema:addressLocality "ROMA";
	schema:streetAddress "VIA ANTONIO MORDINI, 3";
	schema:addressRegion "RM";
	schema:postalCode "00195";
.
```

## schema:GeoCoordinates

Captures the center of a geographic location that is related with a place (not with an postal address)

The geo uri SHOULD be formatted according [rfc 5870](https://tools.ietf.org/html/rfc5870)
Following properties/annotations supported: 

- wgs:lat with cardinality = 1,
- wgs:long with cardinality = 1

Both lat and long should be conformant to following regexp: *^-?([1-8]?[0-9]\.{1}\d{1,20}$|90\.{1}0{1,20}$)*

Example (in rdf turtle):
```
<geo:41.914,12.464163> a schema:GeoCoordinates ;
	wgs:lat 41.914 ;
	wgs:long 12.464163 ;
.
```

## owl:Thing

Following properties/annotations supported for all object: 

- foaf:homepage with cardinality >= 0 strictly as Inverse Functional Property
- foaf:mbox with cardinality >= 0 strictly as Inverse Functional Property
- foaf:page with cardinality >= 0a web page related to the resource
- dct:identifier with cardinality <= 1 an unique identifier in the context of the subject namespace.
- owl:sameAs with cardinality >= 0,URL of a reference Web page that unambiguously indicates the item's identity.
- schema:disambiguatingDescription with cardinality >= 0, A short description of the item used to disambiguate from other, similar items (e.g a category)
- skos:subject >=0 a link to a concept defined into a taxonomy schema described with skos

Example (in rdf turtle):
```
	ex:org1_address owl:sameAs ex2:org2_address .
	ex:org1_address schema:disambiguatingDescription "restaurant", "food", "point_of_interest","establishment" .
```

## Data trust ##

Trust in data can be expressed according with the [Dataset Quality Vocabulary (daQ)](http://butterbur04.iai.uni-bonn.de/ontologies/daq/daq).
Quality observation can be associated to any uri or reficated statements :

Example (in rdf turtle):
```
	# trust level about an individual
	ex:obs1 a qb:Observation ;
		daq:computedOn ex:localbusines_1 ;
		dct:date "2014-01-23T14:53:01"^^xsd:dateTime ;
		daq:value "1.0"^^xsd:double ;
		daq:metric kees:trustMetric ;
		daq:computedBy [ foaf:homepage <http://www.linkeddata.center/> ] ;
		daq:isEstimated false .
		
	# trust level about a statement		
	ex:obs2 a qb:Observation ;
		daq:computedOn [
			a rdf:Statement;
			rdf:subject	ex:org1_address;
			rdf:predicate owl:sameAs;
			rdf:object ex2:org2_address;
		];
		daq:value "0.6"^^xsd:double ;
		daq:metric kees:trustMetric ;
		daq:isEstimated true .
.
```

