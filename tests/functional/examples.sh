# #!/usr/bin/env bash
# require rapper installed:
# sudo apt-get install raptor2-utils

function sampletest {
	php $1.php < input/$1.csv > output/$1.ttl
	parsed="$( rapper -i turtle -c  output/$1.ttl 2>&1)"
	if [[ $parsed =~ "$2 triples" ]]; then
		echo "test $1 OK"
	else 
		echo "test $1 fail with $parsed while expected $2 triples."
	fi
}

sampletest sample1 289
sampletest sample2 274
sampletest sample3 171
sampletest sample4 249
sampletest sample5 746
sampletest sample6 90
sampletest sample7 1810

echo "OK: functional tests for examples completed without errors"