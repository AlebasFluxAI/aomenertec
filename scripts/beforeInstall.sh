#!/bin/bash
cd /var/www/enertec && sudo composer install --ignore-platform-req=ext-rdkafka --verbose --prefer-dist --no-progress --no-interaction --no-dev --optimize-autoloader --no-suggest --apcu-autoloader



