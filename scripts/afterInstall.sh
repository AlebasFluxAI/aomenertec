#!/bin/bash

cd /var/www/enertec && sudo php artisan config:cache
cd /var/www/enertec && sudo php artisan route:cache
cd /var/www/enertec && sudo php artisan migrate
sudo * * * * * cd /var/www/enertec php artisan schedule:run >> /dev/null 2>&1  > /dev/null 2> /dev/null < /dev/null &
sudo /var/www/enertec laravel-echo-server start > /dev/null 2> /dev/null < /dev/null &

