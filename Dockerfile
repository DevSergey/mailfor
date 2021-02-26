FROM php:7.4-apache
RUN apt-get update \
  && apt-get install -y --no-install-recommends zip libzip-dev libpq-dev \
  && docker-php-ext-configure zip --with-libzip \
  && docker-php-ext-install pdo bcmath ctype json zip pdo_pgsql
COPY --from=composer:1.9 /usr/bin/composer /usr/bin/composer
WORKDIR /var/www/html/
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf
RUN a2enmod rewrite
COPY . .
RUN composer install --no-interaction
RUN sed -ri 's/^www-data:x:82:82:/www-data:x:1000:50:/' /etc/passwd
RUN chown -R www-data:www-data /var/www/html
ENTRYPOINT ["bash", "entrypoint.sh"]
