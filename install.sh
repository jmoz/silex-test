#!/bin/bash
cd `dirname $0`

if [[ ! -f composer.phar ]]; then
	echo "*** curl composer and install"
	curl -s http://getcomposer.org/installer | php
else
	echo "*** composer self-update"
	php composer.phar self-update
fi

echo "*** composer install"
php composer.phar -o install