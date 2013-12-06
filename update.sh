#!/bin/bash

( cd vendor/atk4/atk4/; git pull )
( cd vendor/atk4/atk-default-theme/; git pull )

# Update dependencies
if [ -f composer.json ]; then
   [ -f composer.phar ] || curl -sS https://getcomposer.org/installer | php
   php composer.phar update
fi

# git@github.com:atk4/atk4-ide.git
