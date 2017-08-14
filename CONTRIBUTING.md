# Contributing to BOTK-core #

Contributions to BOTK-core are always welcome. You make our lives easier by
sending us your contributions through pull requests.

Pull requests for bug fixes must be based on the current stable branch whereas
pull requests for new features must be based on `master`.

Due to time constraints, we are not always able to respond as quickly as we
would like. Please do not take delays personal and feel free to remind us here,
on IRC, or on Gitter if you feel that we forgot to respond.

## Set-up of a local workstation

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

All BOTK-core code is shared in /vagrant inside virtual host.

Retrieve BOTK-core's dependencies using [Composer](http://getcomposer.org/):

```shell
cd /vagrant
composer install	
```

Unit tests are performed through PHPUnit. To launch unit tests:

```shell
./vendor/bin/phpunit
```

Functional tests are performed through simple bash scrips. To launch functional tests:

```shell
./tests/functional/examples.sh #  This creates alos output files in examples/output dir
```

Free testenv resources with:

```shell
vagrant destroy
```


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

