#!/bin/bash

cd /var/www/enertec && sudo php artisan config:cache > /dev/null 2> /dev/null < /dev/null &
cd /var/www/enertec && sudo php artisan route:cache > /dev/null 2> /dev/null < /dev/null &
cd /var/www/enertec && sudo php artisan migrate > /dev/null 2> /dev/null < /dev/null &
cd /var/www/enertec && sudo laravel-echo-server start > /dev/null 2> /dev/null < /dev/null &
sudo pkill -9 -f queue:work
cd /var/www/enertec && sudo php artisan queue:work > /dev/null 2> /dev/null < /dev/null &

