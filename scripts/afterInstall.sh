#!/bin/bash

cd /var/www/enertec && sudo php artisan config:cache
cd /var/www/enertec && sudo php artisan route:cache
cd /var/www/enertec && sudo php artisan migrate
sudo /var/www/enertec php artisan schedule:work  > /dev/null 2> /dev/null < /dev/null &
