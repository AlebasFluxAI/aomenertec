#!/bin/bash

cd /var/www/enertec && sudo composer2 install --verbose --prefer-dist --no-progress --no-interaction --no-dev --optimize-autoloader --no-suggest --apcu-autoloader
cd /var/www/enertec && sudo php artisan config:cache
cd /var/www/enertec && sudo php artisan route:cache
cd /var/www/enertec && sudo php artisan migrate
