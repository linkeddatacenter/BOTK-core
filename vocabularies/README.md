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


BOTK vocabulary extends schema.org with some custom properties and restrictions.
SKOS vocabulary is used to categorize things

The primary focus o BOTK are:
- **Local Businesses**, defined as a legal business organization with a physical postal address contact point. Modelled as schema:LocalBusiness
- **Business contacts**, modelled as a schema:Person, it define the business identity of a person (i.e. his/her business card)
- **Products**, modelled as schema:Product


## BOTK Axioms

BOTK extends and introduces some restrictions to the supported ontologies. 

## String context restriction

No string context should be used to qualify strings.


### schema:QuantitativeValue

schema:QuantitativeValue is used as an a-dimentional range of values with following restriction:

- [schema:minValue ](http://schema.org/minValue )with cardinality <= 1
- [schema:maxValue ](http://schema.org/maxValue )with cardinality <= 1,
- [schema:value ](http://schema.org/value )with cardinality <= 1,

if schema:value with cardinality = 1 then schema:minValue = schema:maxValue =schema:minValue i.e.:

```
construct { ?s schema:minValue ?value; $schema:maxValue ?value} 
where {
	?s schema:value ?value
}
```


## botk:BusinesOrganization

Just a virtual collection of LocalBusiness and BusinessContact with some statistic data. 
Extends schema:Organization with following properties and restrictions:

- botk:ateco2007 with cardinality >=0
- botk:naceV2 with cardinality >=0
- [schema:isicV4 ](http://schema.org/isicV4 )with cardinality =>0
- botk:hasServerManufacturer with cardinality >=0
- botk:hasServerVirtualizationManufacturer with cardinality >=0
- botk:hasDASManufacturer with cardinality >=0
- botk:hasNASManufacturer with cardinality >=0
- botk:hasSANManufacturer with cardinality >=0
- botk:hasTapeLibraryManufacturer with cardinality >=0
- botk:hasStorageVirtualizationManufacturer with cardinality >=0
- botk:naics with cardinality >=0
- botk:hasNAFCode with cardinality >=0
- botk:hasServerSeries with cardinality >=0
- botk:hasDesktopManufacturer with cardinality >=0
- botk:hasLaptopManufacturer with cardinality >=0
- botk:hasDesktopVirtualizationManufacturer with cardinality >=0
- botk:hasWorkstationManufacturer with cardinality >=0
- botk:hasNetworkPrinterManufacturer with cardinality >=0
- botk:hasHighVolumePrinterManufacturer with cardinality >=0
- botk:hasCopierManufacturer with cardinality >=0
- botk:hasUPSManufacturer with cardinality >=0
- botk:hasERPSuiteVendor with cardinality >=0
- botk:hasERPSoftwareasaServiceManufacturer with cardinality >=0
- botk:hasAppServerSoftwareVendor with cardinality >=0
- botk:hasBusIntellSoftwareVendor with cardinality >=0
- botk:hasCollaborativeSoftwareVendor with cardinality >=0
- botk:hasCRMSoftwareVendor with cardinality <>=0
- botk:hasCRMSoftwareasaServiceManufacturer with cardinality >=0
- botk:hasDocumentMgmtSoftwareVendor with cardinality >=0
- botk:hasAppConsolidationSoftwareVendor with cardinality >=0
- botk:hasHumanResourceSoftwareVendor with cardinality >=0
- botk:hasSupplyChainSoftwareVendor with cardinality >=0
- botk:hasWebServiceSoftwareVendor with cardinality >=0
- botk:hasDatawarehouseSoftwareVendor with cardinality >=0
- botk:hasSaaSVendor with cardinality >=0
- botk:hasEmailMessagingVendor with cardinality >=0
- botk:hasEmailSaaSManufacturer with cardinality >=0
- botk:hasOSVendor with cardinality >=0
- botk:hasOSModel with cardinality >=0
- botk:hasDBMSVendor with cardinality >=0
- botk:hasAcctingVendor with cardinality >=0
- botk:hasAntiVirusVendor with cardinality >=0
- botk:hasAssetManagementSoftwareVendor with cardinality >=0
- botk:hasEnterpriseManagementSoftwareVendor with cardinality >=0
- botk:hasIDAccessSoftwareVendor with cardinality >=0
- botk:hasStorageManagementSoftwareVendor with cardinality >=0
- botk:hasStorageSaaSManufacturer with cardinality >=0
- botk:hasEthernetTechnology with cardinality >=0
- botk:haseCommerceType with cardinality >=0
- botk:hasHostorRemoteStatus with cardinality <=1
- botk:hasNetworkLineCarrier with cardinality >=0
- botk:hasVideoConfServicesProvider with cardinality >=0
- botk:hasUnifiedCommSvcProvider with cardinality >=0
- botk:hasRouterManufacturer with cardinality >=0
- botk:hasSwitchManufacturer with cardinality >=0
- botk:hasVPNManufacturer with cardinality >=0
- botk:hasISP with cardinality >=0
- botk:hasNetworkServiceProvider with cardinality >=0
- botk:hasPhoneSystemManufacturer with cardinality >=0
- botk:hasVoIPManufacturer with cardinality >=0
- botk:hasVoIPHosting with cardinality >=0
- botk:hasLongDistanceCarrier with cardinality >=0
- botk:hasWirelessProvider with cardinality >=0
- botk:hasPhoneSystemMaintenanceProvider with cardinality >=0
- botk:hasSmartphoneManufacturer with cardinality >=0
- botk:hasSmartphoneOS with cardinality >=0
- botk:hasFYE with cardinality <=1
- [schema:foundingDate](http://schema.org/foundingDate) with cardinality <=1
- [schema:subOrganization ](http://schema.org/subOrganization )with cardinality >= 0


Following statistical dimensions also apply; all these properties have  cardinality <=1 and range schema:QuantitativeValue. The range has an implicit unit of measure,

- [schema:numberOfEmployees ](http://schema.org/numberOfEmployees )expressend in number of employees
- botk:annualTurnover expressend in Thousand EURO
- botk:netProfit expressend in Thousand EURO
- botk:itBudget expressend in Thousand EURO
- botk:itStorageBudget expressend in Thousand EURO
- botk:itHardwareBudget expressend in Thousand EURO
- botk:itServerBudget expressend in Thousand EURO
- botk:softwareBudget expressend in Thousand EURO	
- botk:hasStorageBudget expressend in Thousand EURO	
- botk:hasServerBudget expressend in Thousand EURO	
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
- botk:ebitda last known  net earnings, before interest expenses, taxes, depreciation and amortization , expressend in Thousand EURO
- botk:hasITBudget expressend in Thousand EURO
- botk:hasITEmployees expressend in in number of people
- botk:hasNumberOfPCs expressend in in pcs
- botk:hasTotDevelopers expressend in number of people	
- botk:hasTotCallCenterCallers expressend in people
- botk:hasInternetUsers expressend in people
- botk:hasWirelessUsers expressend in people
- botk:hasEnterpriseSmartphoneUsers expressend in people
- botk:hasTablets expressend in pcs
- botk:hasWorkstations expressend in in pcs
- botk:hasServers expressend in pcs
- botk:hasDesktop expressend in pcs
- botk:hasLaptops expressend in pcs
- botk:hasPrinters expressend in pcs
- botk:hasMultifunctionPrinters expressend in pcs
- botk:hasColorPrinter expressend in pcs
- botk:hasNetworkLines expressend in pcs
- botk:hasRouters expressend in pcs
- botk:hasExtensions expressend in pcs
- botk:hasThinPC expressend in pcs
- botk:hasSalesforce expressend in pcs
- botk:hasDesktopPrinters expressend in pcs	
- botk:hasNetworkPrinters expressend in pcs
- botk:hasSmartphoneUsers expressend in pcs
- botk:hasStorageCapacity expressend in TB (tera bytes)


Example 2 (in rdf turtle):

```
ex:org1 a schema:Organization ;
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
    schema:isicV4 "8699" ;
    schema:numberOfEmployees [ schema:minValu1e 1000 ; schema:maxValue 2000 ] ;
```

## schema:LocalBusiness

Captures the concept about a public legal registered business organization  with a contactable official contact point .	

It is a subclass of schema:LocalBusiness and botk:BusinessOrganization that adds some properties and restrictions.
This class can be specialized  to state the reason of the business interest (e.g. see schema:LocalBusiness classifications).


Following properties/annotations supported: 

- rdf:type	with cardinality >= 1 ,  type in the form of prefix:classname at least schema:LocalBusiness must be present
- [schema:vatID ](http://schema.org/vatID )with cardinality <= 1, as (NB. is an Inverse Functional Property in country context)
- [schema:taxID ](http://schema.org/taxID )with cardinality <= 1,
- [schema:legalName ](http://schema.org/legalName )with cardinality <= 1, as the legal name of the location
- [schema:alternateName ](http://schema.org/alternateName )with cardinality >= 0, contains the insignia of the shop as normalized text
- [schema:address ](http://schema.org/address ) with cardinality <=1 , contact info for this local business  as a schema:PostalAddress individual
- [schema:geo ](http://schema.org/geo )with cardinality <= 1 pointer a schema:GeoCoordinates
- [schema:hasMap ](http://schema.org/hasMap )with cardinality >= 0 pointer an URL with a map of the place
- [schema:telephone ](http://schema.org/telephone )with cardinality >= 0, formatted as string with no space, can start with '+', if 00 is present at the beginning,it is substituted with +, as primary telephone contact point for this location
- [schema:faxNumber ](http://schema.org/faxNumber )with cardinality >= 0, same formatting of telephone, as primary fax  contact point for this organization
- [schema:email ](http://schema.org/email )with cardinality >= 0,  as  email for a contact point for this organization
- [schema:aggregateRating ](http://schema.org/aggregateRating )with cardinality <= 1,  as a schema:AggregateRating instance, schema:ratingValue attribute must be present as non negative float value
- [schema:openingHours ](http://schema.org/openingHours )with cardinality <= 1,  if  ="permanantly closed" the local business is permanantly closed
- [schema:department ](http://schema.org/department )with cardinality >= 0,  as an office or sub organization
- [schema:parentOrganization ](http://schema.org/parentOrganization )with cardinality > 0;


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


## schema:PostalAddress 

Captures a contact point with a postal address
- [schema:description ](http://schema.org/description )with cardinality >= 1 possibly as normalized string from template "DUF DUG, CIVIC, ZIP LOCALITY", es "LUNGOLARIO CESARE BATTISTI, 5, 23900 LECCO LC" ) 
- [schema:addressCountry ](http://schema.org/addressCountry )with cardinality = 1, Country  in two-letter ISO 3166-1 alpha-2 country code no language specs
- [schema:addressLocality ](http://schema.org/addressLocality )with cardinality <= 1, The locality as normalized string. For example, "MILANO". Should be present in an official country adminstrative db as SKOS:primaryName or rdfs:label
- [schema:addressRegion](http://schema.org/addressRegion) with cardinality <= 1, The second administrative level as normalized string. For example, "MI". . Should be present in country adminstrative db as SKOS:primaryName
- [schema:streetAddress](http://schema.org/streetAddress) with cardinality <= 1,	a normalizzed string from template "DUF DUG, CIVIC". For example, "VIA ANTONIO MORDINI, 3"
- [schema:postalCode](http://schema.org/postalCode)	with cardinality <= 1,	Text 	The postal code. For example, 94043.

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

- schema:latitude with cardinality = 1,
- schema:longitude with cardinality = 1

Both lat and long should be conformant to following regexp: *^-?([1-8]?[0-9]\.{1}\d{1,20}$|90\.{1}0{1,20}$)*

Example (in rdf turtle):
```
<geo:41.914,12.464163> ;
	schema:latitude 41.914 ;
	schema:longitude 12.464163 ;
.
```

## schema:Person

capture a business contact. That is to be considered  similar to a visit card (see vc ontology) that to a phisical person (like foaf:person). 
It represents aperson identity related to a workplace:

- dct:identifier with cardinality <= 1
- [schema:disambiguatingDescription ](http://schema.org/disambiguatingDescription )with cardinality <= 1
- [schema:aggregateRating ](http://schema.org/aggregateRating )with cardinality <= 1,  as a schema:AggregateRating instance, schema:ratingValue attribute must be present as non negative float value
- [schema:taxID ](http://schema.org/taxID )with cardinality <= 1
- [schema:givenName ](http://schema.org/givenName )with cardinality <= 1
- [schema:familyName ](http://schema.org/familyName )with cardinality <= 1
- [schema:additionalName ](http://schema.org/additionalName )with cardinality <= 1
- [schema:alternateName ](http://schema.org/alternateName )with cardinality >= 0
- [schema:telephone ](http://schema.org/telephone )with cardinality >= 0
- [schema:faxNumber ](http://schema.org/faxNumber )with cardinality >= 0
- [schema:jobTitle ](http://schema.org/jobTitle )with cardinality <>= 0
- [schema:honorificPrefix ](http://schema.org/honorificPrefix )with cardinality >= 0
- [schema:honorificSuffix ](http://schema.org/honorificSuffix )with cardinality >= 0
- [schema:email ](http://schema.org/email )with cardinality >= 0
- [schema:gender ](http://schema.org/gender )with cardinality <= 1 (as http:/schema.org/Male or http://schema.org/Female)
- [schema:worksFor ](http://schema.org/worksFor )with cardinality >= 1 and range an Organization
- botk:spokenLanguage with cardinality <= 1
- botk:hasOptInOptOutDate with cardinality <= 1, the last date the privacyFlag changed
- botk:privacyFlag with cardinality <= 1 ad boolean	

Example (in rdf turtle):

```
<urn:aberdeen:S308001886_4> a schema:Person ;
    botk:spokenLanguage "it" ;
    schema:honorificPrefix "Sig." ;
    schema:jobTitle "Direttore Sistemi Informativi   Rete" ;
    botk:privacyFlag false ;
    schema:worksFor <urn:aberdeen:S308001886> .
    
<urn:test:b> schema:Person ;
    botk:hasOptInOptOutDate "2003-10-10T00:00:00+00:00" ;
    botk:privacyFlag true ;
    botk:spokenLanguage "it" ;
    schema:additionalName "ADDITIONAL" ;
    schema:alternateName "GIVEN ADDITIONAL FAMILY" ;
    schema:email "A@B.C" ;
    schema:familyName "FAMILY" ;
    schema:gender "http://schema.org/Male" ;
    schema:givenName "GIVEN" ;
    schema:jobTitle "dr.",
        "grand.uff.",
        "ing.",
        "lup.mannar." ;
    schema:taxID "1234" ;
    schema:telephone "1234567" ;
    schema:worksFor <http:/a.c/> .
```


## schema:Product

capture a product or service with these properties and restrictions:


- dct:identifier with cardinality <= 1
- [schema:brand ](http://schema.org/brand )with cardinality >=0
- [schema:category ](http://schema.org/category )
- [schema:color ](http://schema.org/color )
- [schema:depth ](http://schema.org/depth ) with cardinality <= 1 and range a quantitative value expressed in meters
- [schema:gtin13 ](http://schema.org/gtin13 )
- [schema:gtin8 ](http://schema.org/gtin8 )
- [schema:height ](http://schema.org/height ) with cardinality <= 1 and range a quantitative value expressed in meters
- [schema:isAccessoryOrSparePartFor ](http://schema.org/isAccessoryOrSparePartFor )
- [schema:isConsumableFor ](http://schema.org/isConsumableFor )
- [schema:isRelatedTo ](http://schema.org/isRelatedTo )
- [schema:isSimilarTo ](http://schema.org/isSimilarTo )
- [schema:itemCondition ](http://schema.org/itemCondition ), with range a valid schema.org enum value
- [schema:manufacturer ](http://schema.org/manufacturer ), with range a valid LocalBusiness uri
- [schema:material ](http://schema.org/material ), with range a valid product suri
- [schema:mpn ](http://schema.org/gtin13 )
- [schema:productionDate ](http://schema.org/productionDate ), with range a date litteral compatible with php function str2date
- [schema:purchaseDate ](http://schema.org/gtin13 ), with range a date litteral compatible with php function str2date
- [schema:releaseDate ](http://schema.org/releaseDate ), with range a date litteral compatible with php function str2date
- [schema:review ](http://schema.org/review )
- [schema:sku ](http://schema.org/sku )
- [schema:weight ](http://schema.org/weight )with cardinality >= 1 and range a quantitative value expressed in gramms
- [schema:additionalName ](http://schema.org/additionalName )
- [schema:width ](http://schema.org/alternateName ) with cardinality <= 1 and range a quantitative value expressed in meters

Example (in rdf turtle):

```
<https://data.icecat.biz/export/freeurls/export_urls.txt#1399> a schema:Product ;
    dct:identifier "1399" ;
    schema:brand <https://data.icecat.biz/export/freeurls/supplier_mapping.xml#1> ;
    schema:gtin13 "0088698629123",
        "0735029220800",
        "0777785507870",
        "0882780756328",
        "0886986291232",
        "4053162277090",
        "5051964081876",
        "5705965480557",
        "6092014024923",
        "9082014024907" ;
    schema:image <http://images.icecat.biz/img/gallery/img_1399_high_1496963303_5656_26625.jpg> ;
    schema:mpn "C4872A" ;
    skos:subject <https://data.icecat.biz/export/freeurls/categories.xml#377>,
        <https://data.icecat.biz/export/freeurls/categories.xml#44103105> ;
    foaf:homepage <http://prf.icecat.biz/index.cgi?product_id=1399;mi=start;smi=product;&shopname=openICEcat-url> 
.
```


## schema:Thing

Following properties/annotations supported for all object: 

- foaf:homepage with cardinality >= 0 strictly as Inverse Functional Property
- foaf:mbox with cardinality >= 0 strictly as Inverse Functional Property
- foaf:page with cardinality >= 0a web page related to the resource
- dct:identifier with cardinality <= 1 an unique identifier in the context of the subject namespace.
- owl:sameAs with cardinality >= 0,URL of a reference Web page that unambiguously indicates the item's identity.
- [schema:disambiguatingDescription](http://schema.org/disambiguatingDescription )with cardinality >= 0, A short description of the item used to disambiguate from other, similar items (e.g a category)
- skos:subject >=0 a link to a concept defined into a taxonomy schema described with skos
- [schema:name](http://schema.org/name )the preferred name for the individual, slimilar to skos:preferredLabel.
- [schema:alternateName](http://schema.org/alternateNamame )an alternative name for the individual, slimilar to skos:altLabel. This axiom applies:

		```
		CONSTRUCT { ?s schema:alternateName ?name }
		WHERE { ?s schema:name ?name}
		```


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

