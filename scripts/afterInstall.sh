#!/bin/bash

cd /var/www/enertec && sudo nohup php artisan config:cache > /dev/null 2> /dev/null < /dev/null &
cd /var/www/enertec && sudo nohup php artisan route:cache > /dev/null 2> /dev/null < /dev/null &
cd /var/www/enertec && sudo nohup php artisan migrate > /dev/null 2> /dev/null < /dev/null &
