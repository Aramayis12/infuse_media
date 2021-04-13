FROM php:7.2-fpm

# jre installation require man folder to exist on the server
RUN mkdir -p /usr/share/man/man1

COPY ./sources.list /etc/apt/sourcee.list

# Install modules
RUN apt-get update && apt-get install -y \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libicu-dev \
        wget \
        default-jre \
        telnet \
        git \
            --no-install-recommends

RUN docker-php-ext-install zip intl mbstring pdo_mysql exif \
    && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
    && docker-php-ext-install gd

# for mysqli if you want
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli
