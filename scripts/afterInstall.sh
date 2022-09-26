#!/bin/bash

cd /var/www/enertec && sudo nohup php artisan config:cache
cd /var/www/enertec && sudo nohup php artisan route:cache
cd /var/www/enertec && sudo nohup php artisan migrate

