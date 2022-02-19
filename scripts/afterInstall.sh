#!/bin/bash

cd /var/www/enertec && sudo php artisan config:cache
cd /var/www/enertec && sudo php artisan route:cache
cd /var/www/enertec && sudo php artisan migrate
cd /var/www/enertec && sudo php artisan schedule:work&
