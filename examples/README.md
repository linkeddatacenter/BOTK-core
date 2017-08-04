BOTK-core examples
------------------

the SimpleCsvGateway class is an example of a simple gateway that reads csv file from STDIN and produces valid BOTK linked data in STDOUT.
N.B. the same dataset can be processed by different gateways to produce different data

Examples:

Datasets that contains LocalBusiness Entities:

```
php sample1.php < input/sample1.txt > output/sample1.ttl
php sample2.php < input/sample2.csv > output/sample2.ttl
php sample3.php < input/sample3.CSV > output/sample3.ttl
php sample4.php < input/sample4.csv > output/sample4.ttl
php sample5A.php < input/sample5.csv > output/sample5A.ttl
php sample5B.php < input/sample5.csv > output/sample5B.ttl
```

Datasets that contains both LocalBusiness and Person Entities:

```
php sample5A.php < input/sample5.csv > output/sample5A.ttl
php sample5B.php < input/sample5.csv > output/sample5B.ttl
```