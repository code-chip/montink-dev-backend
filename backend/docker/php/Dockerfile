FROM php:8.3-apache

RUN docker-php-ext-install pdo pdo_mysql
COPY . /var/www/html/
WORKDIR /var/www/html/public

# Instala dependências básicas
RUN apt-get update && apt-get install -y \
    unzip \
    zip \
    curl \
    git \
    libzip-dev \
    && docker-php-ext-install zip pdo pdo_mysql

# Instala dependências e Xdebug
RUN apt-get install -y unzip \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug

# Habilita exibição de erros (opcional)
RUN echo "display_errors=On" >> /usr/local/etc/php/conf.d/docker-php.ini \
    && echo "error_reporting=E_ALL" >> /usr/local/etc/php/conf.d/docker-php.ini

# Cria arquivo de configuração do Xdebug
RUN echo "zend_extension=xdebug.so" > /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.mode=debug" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.start_with_request=yes" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.client_host=host.docker.internal" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.client_port=9003" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.discover_client_host=0" >> /usr/local/etc/php/conf.d/xdebug.ini    

# Instala Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Habilita mod_rewrite para Apache
RUN a2enmod rewrite

ARG UID=1000
ARG GID=1000
RUN groupadd -g ${GID} appuser && \
    useradd -u ${UID} -g ${GID} -m appuser

USER appuser
