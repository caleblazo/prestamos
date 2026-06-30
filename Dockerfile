FROM php:8.2-apache

# Dependencias
RUN apt-get update && apt-get install -y \
    libssl-dev \
    libcurl4-openssl-dev \
    libpng-dev \
    libjpeg-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    curl \
    gnupg

# Node.js para compilar assets
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs

# Extensiones PHP
RUN docker-php-ext-install pdo pdo_mysql

# Zona horaria
RUN apt-get update && apt-get install -y tzdata \
    && ln -fs /usr/share/zoneinfo/America/Lima /etc/localtime \
    && dpkg-reconfigure -f noninteractive tzdata

# Apache
RUN a2enmod ssl
RUN a2enmod rewrite

# SSL
COPY ssl/default-ssl.conf /etc/apache2/sites-available/default-ssl.conf
COPY ssl/ca-cert.pem /var/www/html/ssl/ca-cert.pem
COPY ssl/apache-selfsigned.crt /etc/ssl/certs/apache-selfsigned.crt
COPY ssl/apache-selfsigned.key /etc/ssl/private/apache-selfsigned.key
RUN a2ensite default-ssl

# DocumentRoot para Laravel
RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf

WORKDIR /var/www/html

# Instalar dependencias de Laravel
COPY . /var/www/html
RUN npm install && npm run build
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80
EXPOSE 443

CMD ["apache2-foreground"]
