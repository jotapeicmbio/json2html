FROM php:8.2-cli

ARG uid
ARG user

RUN apt-get update && apt-get install -y \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libpq-dev \
    zip \
    unzip \
    zlib1g-dev \
    libmcrypt-dev \
    libpng-dev \
    libzip-dev \
    libsodium-dev \
    nodejs \
    npm

# Limpando cache do APT
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Instalando extensões PHP
RUN docker-php-ext-install pdo pdo_pgsql pgsql mbstring xml exif pcntl bcmath gd zip sodium

COPY --from=composer:2.5.4 /usr/bin/composer /usr/local/bin/composer

RUN useradd -G www-data,root -u $uid -d /home/$user $user
RUN mkdir -p /home/$user/.composer && \
    chown -R $user:$user /home/$user

ENV PATH="$PATH:~/.composer/vendor/bin"
USER $user