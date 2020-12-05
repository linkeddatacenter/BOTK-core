# Contributing to BOTK-core #

Contributions to BOTK-core are always welcome. You make our lives easier by
sending us your contributions through pull requests.

Pull requests for bug fixes must be based on the current stable branch whereas
pull requests for new features must be based on `master`.

Due to time constraints, we are not always able to respond as quickly as we
would like. Please do not take delays personal and feel free to remind us here,
on IRC, or on Gitter if you feel that we forgot to respond.

Please see http://help.github.com/pull-requests/.

We kindly ask you to add following sentence to your pull request:

“I hereby assign copyright in this code to the project, to be licensed under the same terms as the rest of the code.”

## Set-up of a local developmente workstation

The platform is shipped with a [Docker](https://docker.com) setup that makes it easy to get a containerized environment up and running. 
If you do not already have Docker on your computer, 
[it's the right time to install it](https://docs.docker.com/install/). 


## Running tests

Retrieve BOTK-core's dependencies using [Composer](http://getcomposer.org/):

	docker run --rm -ti -v $PWD/.:/app composer install
	docker run --rm -ti -v $PWD/.:/app composer update

Unit tests are performed through PHPUnit. To launch unit tests:

	docker run --rm -v $PWD/.:/app -w /app --entrypoint vendor/bin/phpunit php


## working in system test environment

run bash from the sdaas platform:

	docker run -d --name test -v $PWD/.:/workspace -p 8080:8080 linkeddatacenter/sdaas-ce:2.5.0
	docker exec -ti test bash

Install php 7.3  according with https://github.com/codecasts/php-alpine

	apk add --update curl ca-certificates
	curl https://dl.bintray.com/php-alpine/key/php-alpine.rsa.pub -o /etc/apk/keys/php-alpine.rsa.pub
	echo "https://dl.bintray.com/php-alpine/v3.8/php-7.3" >> /etc/apk/repositories
	apk add --update php php-mbstring php-json php-xml php-xmlreader
	ln -s /usr/bin/php7 /usr/bin/php

run unit tests:

	vendor/bin/phpunit

test the gateway standalone:

	tests/system/gateways/example1.php < tests/system/data/example1.csv
	
run system tests:

	sdaas -f tests/system/build.sdaas --reboot

free resources

	exit
	docker rm -f test


## Pull Request Process

1. Ensure any install or build dependencies are removed before the end of the layer when doing a 
   build.
2. Update the README.md with details of changes to the interface, this includes new environment 
   variables, exposed ports, useful file locations and container parameters.
3. Edit [unreleased] tag in CHANGELOG.md and save your changes, additions, fix and delete to what this version that this
   Pull Request would represent. The versioning scheme we use is [SemVer](http://semver.org/).
4. You may merge the Pull Request in once you have the sign-off of two other developers, or if you 
   do not have permission to do that, you may request the second reviewer to merge it for you.

We are trying to follow the [PHP-FIG](http://www.php-fig.org)'s standards, so
when you send us a pull request, be sure you are following them.

Please see http://help.github.com/pull-requests/.

