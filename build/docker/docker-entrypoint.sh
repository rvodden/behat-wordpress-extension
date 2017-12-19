#!/bin/bash
set -eux

WH_WORDPRESS_DIR=/usr/src/wordpress

# wait for mysql to come up

while ! mysqladmin ping -h "db" --silent; do
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

vendor/bin/wp config create --path="${WH_WORDPRESS_DIR}" --dbhost='db' --dbname='wordpress' --dbuser="${WORDPRESS_DB_USER}" --dbpass="${WORDPRESS_DB_PASSWORD}"
vendor/bin/wp core install --path="${WH_WORDPRESS_DIR}" --url='http://wordpress:8080/' --title="Wordhat Wordpress Install" --admin_user="admin" --admin_password="password" --admin_email="admin@example.com"
vendor/bin/wp theme activate --path="${WH_WORDPRESS_DIR}" twentyseventeen
vendor/bin/wp rewrite structure --path="${WH_WORDPRESS_DIR}" '/%year%/%monthnum%/%postname%/'

for sidebar in $(vendor/bin/wp sidebar list --path="${WH_WORDPRESS_DIR}" --format=ids); do
  for widget in $(vendor/bin/wp widget list $sidebar --path="${WH_WORDPRESS_DIR}" --format=ids); do
    vendor/bin/wp widget delete --path="${WH_WORDPRESS_DIR}" $widget
  done;
done;

cd /var/www/html
exec /usr/local/bin/docker-entrypoint.sh apache2-foreground
