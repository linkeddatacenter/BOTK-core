#!/usr/bin/env bash

echo "Installing php 7.3 (https://github.com/codecasts/php-alpine)"
apk add --update curl ca-certificates
curl https://dl.bintray.com/php-alpine/key/php-alpine.rsa.pub -o /etc/apk/keys/php-alpine.rsa.pub
echo "https://dl.bintray.com/php-alpine/v3.8/php-7.3" >> /etc/apk/repositories
apk add --update php php-mbstring php-json php-xml php-xmlreader
ln -s /usr/bin/php7 /usr/bin/php


/sdaas-start
sdaas -f tests/system/build.sdaas --reboot
/sdaas-stop