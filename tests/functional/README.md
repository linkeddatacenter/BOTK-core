BOTK-core examples
------------------

the SimpleCsvGateway class is an example of a simple gateway that reads csv file from STDIN and produces valid BOTK linked data in STDOUT.
N.B. the same dataset can be processed by different gateways to extract different concepts:


Usage example:

```
php sample1.php < input/sample1.txt > output/sample1.ttl
php sample5A.php < input/sample5.csv > output/sample5A.ttl
php sample5B.php < input/sample5.csv > output/sample5B.ttl
```

See [examples.sh script](examples.sh) for more info