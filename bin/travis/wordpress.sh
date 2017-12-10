#!/bin/bash

# Exit if anything fails AND echo each command before executing.
# http://www.peterbe.com/plog/set-ex
set -ex

if [ $# -lt 3 ]; then
  echo "usage: $0 <db-name> <db-user> <db-pass> [db-host] [wp-version]"
  exit 1
fi

DB_NAME=$1
DB_USER=$2
DB_PASS=$3
DB_HOST=${4-localhost}
WP_VERSION=${5-latest}
WH_WORDPRESS_DIR=${WH_WORDPRESS_DIR-/tmp/wordpress}

# Download.
mkdir -p $WH_WORDPRESS_DIR
vendor/bin/wp core download --force --version=$WP_VERSION --path=$WH_WORDPRESS_DIR

# Create config.
rm -f ${WH_WORDPRESS_DIR}wp-config.php
vendor/bin/wp core config --path=$WH_WORDPRESS_DIR --dbname=$DB_NAME --dbuser=$DB_USER --dbpass=$DB_PASS --dbhost=$DB_HOST

# Install.
vendor/bin/wp db create --path=$WH_WORDPRESS_DIR
vendor/bin/wp core install --path=$WH_WORDPRESS_DIR --url="wordpress.dev:8080" --title="wordpress.dev" --admin_user="admin" --admin_password="password" --admin_email="admin@example.com"

# Sensible defaults.
vendor/bin/wp theme activate --path=$WH_WORDPRESS_DIR twentyseventeen
vendor/bin/wp rewrite structure --path=$WH_WORDPRESS_DIR '/%year%/%monthnum%/%postname%/'

for sidebar in $(vendor/bin/wp sidebar list --path=$WH_WORDPRESS_DIR --format=ids); do
  for widget in $(vendor/bin/wp widget list $sidebar --path=$WH_WORDPRESS_DIR --format=ids); do
    vendor/bin/wp widget delete --path=$WH_WORDPRESS_DIR $widget
  done;
done;
