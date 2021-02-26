#!/bin/bash
chown -R www-data:www-data storage/ bootstrap/
php artisan view:cache
php artisan config:cache
php artisan migrate --force
exec apache2-foreground
