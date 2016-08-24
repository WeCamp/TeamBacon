FROM php:7.0.6-apache

RUN apt-get update && apt-get install -y zlib1g-dev libicu-dev libpq-dev \
    && docker-php-ext-install opcache \
    && docker-php-ext-install intl \
    && docker-php-ext-install mbstring \
    && docker-php-ext-install pdo_mysql \
    && docker-php-ext-install bcmath \
    && docker-php-ext-install pdo_pgsql \
    && pecl install apcu-5.1.5 \ && docker-php-ext-enable apcu

RUN a2enmod rewrite
RUN a2enmod headers

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    php -r "if (hash_file('SHA384', 'composer-setup.php') === 'e115a8dc7871f15d853148a7fbac7da27d6c0030b848d9b3dc09e2a0388afed865e6a3d6b3c0fad45c48e2b5fc1196ae') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;" \
    php composer-setup.php \
    php -r "unlink('composer-setup.php');" \

ADD ./vhost.conf /etc/apache2/sites-enabled/000-default.conf

EXPOSE 80

CMD /usr/sbin/apache2ctl -D FOREGROUND
