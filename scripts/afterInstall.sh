#!/bin/bash

cd /var/www/enertec && sudo nohup php artisan config:cache
cd /var/www/enertec && sudo nohup php artisan route:cache
cd /var/www/enertec && sudo nohup php artisan migrate
cd /var/www/enertec/storage/logs && sudo chmod 777 laravel.log
