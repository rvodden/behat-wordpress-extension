#!/bin/bash
set -uao pipefail

EXPECTED_SIGNATURE=$(wget -q -O - https://composer.github.io/installer.sig)

php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"

ACTUAL_SIGNATURE=$(php -r "echo hash_file('SHA384', 'composer-setup.php');")

if [ "${EXPECTED_SIGNATURE}" != "${ACTUAL_SIGNATURE}" ]
then
    >&2 echo 'ERROR: Invalid composer installer signature'
    rm composer-setup.php
    exit 1
fi

php composer-setup.php
RESULT=${?}
rm composer-setup.php
mv composer.phar composer
exit $RESULT
