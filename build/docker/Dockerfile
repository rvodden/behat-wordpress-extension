FROM library/wordpress:php7.2

RUN useradd -c 'PHP user' -m -d /home/php -s /bin/sh php

ADD ./ /wordhat
RUN chown php /wordhat
WORKDIR /wordhat

RUN apt-get update && apt-get install -y zip unzip composer php7.0-curl php7.0-mbstring php7.0-xml
# RUN ./composer require wp-cli/cp-cli
USER php
RUN composer install --no-interaction --prefer-dist --no-progress
RUN vendor/bin/wp core config --dbname=wordpress --dbuser=root --dbpass=password

ENTRYPOINT docker-entrypoint.sh

