FROM php:8.2-apache

# Instalar dependencias del sistema y extensiones de PHP necesarias
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    zip \
    unzip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd mysqli pdo pdo_mysql zip \
    && a2enmod rewrite

# Configurar Apache
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf
WORKDIR /var/www/html

# Copiar el código del proyecto
COPY . /var/www/html/

# Ajustar permisos para Apache
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Exponer el puerto estándar HTTP
EXPOSE 80

CMD ["apache2-foreground"]
