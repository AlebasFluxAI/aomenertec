#!/bin/bash

cd /var/www/enertec && sudo composer install --verbose --prefer-dist --no-progress --no-interaction --no-dev --optimize-autoloader --no-suggest --apcu-autoloader

