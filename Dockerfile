FROM phpdockerio/php:8.2-fpm
WORKDIR "/application"

# Fix debconf warnings upon build
ARG DEBIAN_FRONTEND=noninteractive

# Install selected extensions and other stuff
RUN \
    sed -i s@/archive.ubuntu.com/@/mirrors.tencent.com/@g /etc/apt/sources.list \
    && sed -i s@/security.ubuntu.com/@/mirrors.tencent.com/@g /etc/apt/sources.list \
    && apt-get update \
    && apt-get -y --no-install-recommends install php8.2-mysql php8.2-pgsql php8.2-redis php8.2-gd \
    && apt-get clean; rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/* \
    && composer config -g repo.packagist composer https://mirrors.aliyun.com/composer/

# Install git
RUN apt-get update \
    && apt-get -y install git \
    && apt-get clean; rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

# Install cron
RUN apt-get update \
    && apt-get -y install cron \
    && apt-get -y install vim \
    && apt-get clean; rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*
