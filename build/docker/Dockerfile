FROM library/wordpress:php7.1

# change the port to 8080 so we don't have to run as root locally
RUN sed -i '1 s/\*\:80/\*\:8080/' /etc/apache2/sites-available/000-default.conf
RUN sed -i 's/Listen 80/Listen 8080/' /etc/apache2/ports.conf
EXPOSE 8080

RUN apt-get update 
RUN apt-get install -y netcat mysql-client zip unzip
RUN apt-get install -y strace
# RUN ./composer require wp-cli/cp-cli

RUN curl https://raw.githubusercontent.com/composer/getcomposer.org/master/web/installer | php -- && mv composer.phar /usr/local/bin/composer
RUN yes | pecl install xdebug \
    && echo "zend_extension=$(find /usr/local/lib/php/extensions/ -name xdebug.so)" > /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.remote_enable=on" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.remote_autostart=off" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.remote_log=/tmp/xdebug.log" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && touch /tmp/xdebug.log && chmod a+rw /tmp/xdebug.log

ADD ./composer.json /wordhat/composer.json
WORKDIR /wordhat
RUN chown -R www-data /wordhat /usr/src/wordpress

USER www-data
RUN composer install --no-interaction --prefer-dist --no-progress

ADD ./ /wordhat
USER root
RUN chown -R www-data /wordhat && chmod u+x build/docker/docker-entrypoint.sh build/docker/run-tests.sh
USER www-data
ENTRYPOINT [ "build/docker/docker-entrypoint.sh" ]
