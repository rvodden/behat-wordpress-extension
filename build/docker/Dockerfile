FROM library/wordpress:php7.1

RUN useradd -c 'PHP user' -m -d /home/php -s /bin/sh php

RUN apt-get update 
RUN apt-get install -y netcat mysql-client zip unzip
RUN apt-get install -y strace
# RUN ./composer require wp-cli/cp-cli

RUN curl https://raw.githubusercontent.com/composer/getcomposer.org/master/web/installer | php -- && mv composer.phar /usr/local/bin/composer
RUN yes | pecl install xdebug \
    && echo "zend_extension=$(find /usr/local/lib/php/extensions/ -name xdebug.so)" > /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.remote_enable=on" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.remote_autostart=off" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.remote_log=/tmp/xdebug.log" >> /usr/local/etc/php/conf.d/xdebug.ini


ADD ./composer.json /wordhat/composer.json
WORKDIR /wordhat
RUN chown -R php /wordhat /usr/src/wordpress

USER php
RUN composer install --no-interaction --prefer-dist --no-progress

ADD ./ /wordhat
USER root
RUN chown -R php /wordhat

USER php
ENTRYPOINT /wordhat/build/docker/docker-entrypoint.sh
