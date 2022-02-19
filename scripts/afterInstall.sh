#!/bin/bash

cd /var/www/enertec && sudo composer install
cd /var/www/enertec && sudo php artisan config:cache
cd /var/www/enertec && sudo php artisan route:cache
cd /var/www/enertec && sudo php artisan migrate
