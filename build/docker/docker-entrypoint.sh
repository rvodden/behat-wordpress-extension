#!/bin/bash
set -x
# wait for mysql to come up
while ! mysqladmin ping -h"db" --silent; do
    echo "Waiting for mysql..."
    sleep 5
done

# now wait for selenium (assume if the port is open then selenium is up)

until nc -z -v -w30 selenium 4444
do
  echo "Waiting for selenium..."
  # wait for 5 seconds before check again
  sleep 5
done

vendor/bin/wp config create --path='/usr/src/wordpress' --dbhost='db' --dbname='wordpress' --dbuser="${WORDPRESS_DB_USER}" --dbpass="${WORDPRESS_DB_PASSWORD}"
vendor/bin/wp core install --path='/usr/src/wordpress' --url='http://wordpress/' --title="Test Installation" --admin_user="admin" --admin_password="password" --admin_email="admin@example.com"
vendor/bin/behat --format progress --config ./build/docker/behat.yml

