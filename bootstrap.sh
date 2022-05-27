#!/usr/bin/env bash

apt-get update
apt-get install -y php7.4-mysql php7.4-xml php7.4-gd php7.4-mbstring php7.4-zip mysql-server composer npm 
cd /vagrant
rm package-lock.json
mysql -u root -p -e 'CREATE DATABASE swap;'
mysql -u root  -e "ALTER USER 'root'@'localhost' IDENTIFIED WITH mysql_native_password BY 'swap';"
composer install
php artisan key:generate
php artisan migrate
php artisan db:seed

