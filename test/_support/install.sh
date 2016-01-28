#!/usr/bin/env /bin/bash

PROJECT="BOTK-core"
REPO="https://github.com/linkeddatacenter/BOTK-core.git" 
# Install apache 2 and php  on an ubunto box
apt-get update
apt-get -y install git subversion apache2 php5-common php5-curl libapache2-mod-php5 php5-cli curl


# Clone  project in /opt (or use vagrant)
if [ ! -d "/opt/$PROJECT" ]; then 
	if [ -d /vagrant ]; then
		ln -s /vagrant "/opt/$PROJECT"
	else 
		cd /opt; clone $REPO
	fi
fi


#install and run composer
curl -sS https://getcomposer.org/installer | sudo php -- --install-dir=/usr/local/bin --filename=composer
if [ ! -f "/opt/$PROJECT/composer.lock" ]; then 
	cd "/opt/$PROJECT"; composer install
fi


# Change apache 2 web document root to /opt/botk-core/web 
cp "/opt/$PROJECT/test/_support/$PROJECT.apache.conf" /etc/apache2/sites-available
a2dissite 000-default.conf
a2ensite "$PROJECT.apache.conf"
service apache2 restart