#!/bin/bash

cd /var/www/enertec && sudo nohup php artisan config:cache > /dev/null 2> /dev/null < /dev/null &
cd /var/www/enertec && sudo nohup php artisan route:cache > /dev/null 2> /dev/null < /dev/null &
cd /var/www/enertec && sudo nohup php artisan migrate > /dev/null 2> /dev/null < /dev/null &
cd /var/www/enertec && sudo nohup laravel-echo-server start > /dev/null 2> /dev/null < /dev/null &
cd /var/www/enertec && sudo nohup php artisan queue:listen > /dev/null 2> /dev/null < /dev/null &
cd /var/www/enertec/script && sudo nohup python3 receiveMqttEvent.py > /dev/null 2> /dev/null < /dev/null &
cd /var/www/enertec/script && sudo nohup python3 receiveMqttRealTimeEvent.py.py > /dev/null 2> /dev/null < /dev/null &

