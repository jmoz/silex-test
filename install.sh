#!/bin/bash
cd `dirname $0`

if [[ ! -f composer.phar ]]; then
	curl -s http://getcomposer.org/installer | php
else
	php composer.phar self-update
fi

php composer.phar -o install