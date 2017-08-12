# #!/usr/bin/env bash
set -e

# require rapper installed:
# sudo apt-get install raptor2-utils

php examples/sample1.php < examples/input/sample1.txt > examples/output/sample1.ttl
rapper -i turtle -c  examples/output/sample1.ttl

php examples/sample2.php < examples/input/sample2.csv > examples/output/sample2.ttl
rapper -i turtle -c  examples/output/sample2.ttl

php examples/sample3.php < examples/input/sample3.CSV > examples/output/sample3.ttl
rapper -i turtle -c  examples/output/sample3.ttl

php examples/sample4.php < examples/input/sample4.csv > examples/output/sample4.ttl
rapper -i turtle -c  examples/output/sample4.ttl

php examples/sample5A.php < examples/input/sample5.csv > examples/output/sample5A.ttl
rapper -i turtle -c  examples/output/sample5A.ttl

php examples/sample5B.php < examples/input/sample5.csv > examples/output/sample5B.ttl
rapper -i turtle -c  examples/output/sample5B.ttl

php examples/sample6.php < examples/input/sample6.txt > examples/output/sample6.ttl
rapper -i turtle -c  examples/output/sample6.ttl


echo "OK: functional tests for examples completed without errors"