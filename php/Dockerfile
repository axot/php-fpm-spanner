FROM axot/php:7.2.21-fpm-alpine10-debug

RUN apk add --no-cache --virtual=.build-deps zlib-dev autoconf gcc g++ make \
  && pecl install grpc protobuf opencensus-alpha apcu igbinary xdebug \
  && docker-php-ext-install sysvshm opcache \
  && docker-php-ext-enable grpc protobuf opencensus apcu igbinary xdebug \
  && apk del .build-deps \
  && apk del *-dev

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY composer.json /tmp/composer.json
COPY composer.lock /tmp/composer.lock
ENV COMPOSER_ALLOW_SUPERUSER 1
RUN composer install --no-scripts --no-autoloader -d /tmp
RUN apk add --no-cache gdb musl-dev musl-dbg

COPY www.conf /usr/local/etc/php-fpm.d/www.conf
COPY . /src
WORKDIR /src

RUN mv -n /tmp/vendor ./ \
  && composer dump-autoload
