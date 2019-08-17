FROM php:7.2.21-fpm-alpine3.10
# FROM axot/php:7.2.21-fpm-alpine10-debug

RUN set -ex \
  && apk add --no-cache --virtual=.build-deps zlib-dev autoconf gcc g++ make \
  && _pecl_install_nproc () {                  \
  pkg=$1;                                      \
  shift;                                       \
  pecl install --onlyreqdeps --nobuild $pkg;   \
  cd "$(pecl config-get temp_dir)/${pkg%-*}";  \
  phpize;                                      \
  ./configure "$@";                            \
  make -j $(nproc) && make install;            \
  cd -;                                        \
  }                                            \
  && pecl install apcu igbinary msgpack xdebug \
  && _pecl_install_nproc redis --enable-redis-igbinary --enable-redis-msgpack \
  && docker-php-ext-install sysvshm opcache \
  && docker-php-ext-enable apcu xdebug redis igbinary msgpack \
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
