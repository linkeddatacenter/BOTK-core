# BOTK\Core
[![Build Status](https://img.shields.io/travis/linkeddatacenter/BOTK-core.svg?style=flat-square)](http://travis-ci.org/linkeddatacenter/BOTK-core)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/linkeddatacenter/BOTK-core.svg?style=flat-square)](https://scrutinizer-ci.com/g/linkeddatacenter/BOTK-core)
[![Latest Version](https://img.shields.io/packagist/v/botk/core.svg?style=flat-square)](https://packagist.org/packages/botk/core)
[![Total Downloads](https://img.shields.io/packagist/dt/botk/core.svg?style=flat-square)](https://packagist.org/packages/botk/core)
[![License](https://img.shields.io/packagist/l/botk/core.svg?style=flat-square)](https://packagist.org/packages/botk/core)

Super lightweight classes and ontologies for developing smart gateways to populate a business knowlege base.


## Installation

The package is available on [Packagist](https://packagist.org/packages/botk/core).
You can install it using [Composer](http://getcomposer.org).

Add following dependance to **composer.json** file in your project root:

```
    {
        "require": {
            "botk/core": "~6.1",
        }
    }
```

## Usage

This package provides some quink and dirty tools to map raw data to [BOTK language profile](vocabularies),

See [examples](examples/) directory.

##Contributing to BOTK-core

Contributions to BOTK-core are always welcome. You make our lives easier by
sending us your contributions through pull requests.

Pull requests for bug fixes must be based on the current stable branch whereas
pull requests for new features must be based on `master`.

Due to time constraints, we are not always able to respond as quickly as we
would like. Please do not take delays personal and feel free to remind us here,
on IRC, or on Gitter if you feel that we forgot to respond.

##Set-up of a local workstation

Before begin be sure to have:

  - a virus free workstation with a fresh OS (windows, MAC, Linux)
  - at least 512K Ram required to run the whole integration testing environment
  - a processor with virtualization support
  - an editor of your choice able read unix-style line endings docs (i.e. notepad++)
 
Local workstation installation process:

  - install [GIT](http://git-scm.com/). Select â€œcheckout as is , commit Unix-style line endingsâ€. If your workstation is windows based and you to want to use pageant for authentication, in windows use putty plint interface as ssh proxy or reconfigure GIT to use ssh tool if needed.
  - install [Vagrant](https://www.vagrantup.com/)
  - install [Virtualbox](https://www.virtualbox.org/)

You are free to optionally install your preferred language ide (aptana, eclipse, other)


## Using BOTK-core from a git checkout

The following commands can be used to perform the initial checkout from a bash shell:

```shell
git clone https://github.com/linkeddatacenter/BOTK-core.git
cd BOTK-core
```

## Developing code and unit tests

Vagrant and virtualbox will setup a complete integration test environment.

To create and login into integrate testing environment, just type:

```shell
vagrant up
vagrant ssh
```
Note that on first execution the install script will ask for LinkedData.Center endpoint and credentials to use.
The APIs will be available at http://localhost:8080/

All BOTK-core code is shared in /opt/BOTK-core inside virtual host.

Retrieve BOTK-core's dependencies using [Composer](http://getcomposer.org/):

```shell
cd /opt/BOTK-core
composer install	
```

Unit tests are performed through PHPUnit. To launch unit tests:

```shell
./vendor/bin/phpunit --coverage-html test/unit/report
```


Free testenv resources with:

```shell
vagrant destroy --force
```


## Standards

We are trying to follow the [PHP-FIG](http://www.php-fig.org)'s standards, so
when you send us a pull request, be sure you are following them.

Please see http://help.github.com/pull-requests/.



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
