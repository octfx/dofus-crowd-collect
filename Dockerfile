FROM php:7.4-apache

# Arguments defined in docker-compose.yml
ARG user
ARG uid

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install mbstring exif pcntl bcmath gd opcache

# OPCache settings
RUN echo '\
opcache.enable=1\n\
opcache.memory_consumption=256\n\
opcache.interned_strings_buffer=16\n\
opcache.max_accelerated_files=16000\n\
opcache.validate_timestamps=0\n\
opcache.load_comments=Off\n\
opcache.save_comments=1\n\
opcache.fast_shutdown=0\n\
' >> /usr/local/etc/php/conf.d/docker-php-ext-opcache.ini

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

COPY composer.json composer.lock ./

RUN /usr/bin/composer install --no-dev \
   --ignore-platform-reqs \
   --no-ansi \
   --no-autoloader \
   --no-interaction \
   --no-scripts

COPY . ./

RUN /usr/bin/composer dump-autoload --optimize --classmap-authoritative

RUN chown -R www-data:www-data /var/www/html && \
    chmod u+w /var/www/html/storage && \
    chmod g+w /var/www/html/storage && \
    a2enmod rewrite

CMD ["/usr/local/bin/start"]
