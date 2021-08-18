FROM php:7.4.22-fpm
MAINTAINER xup6m6fu04
WORKDIR /var/www/html
COPY . .
RUN apt-get update \
    && apt-get install -y libzip-dev net-tools netcat nginx cron sudo vim software-properties-common npm \
    && docker-php-ext-install sockets mysqli pdo pdo_mysql zip \
    && php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php -r "if (hash_file('sha384', 'composer-setup.php') === '756890a4488ce9024fc62c56153228907f1545c228516cbf63f885e036d37e9a59d27d63f46af1d4d07ee0f76181c7d3') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;" \
    && php composer-setup.php --install-dir=/usr/bin --filename=composer \
    && php -r "unlink('composer-setup.php');" \
    && echo "security.limit_extensions =" >> /usr/local/etc/php-fpm.d/www.conf \
    && composer install \
    && rm -rf /var/lib/apt/lists/* /etc/nginx/sites-enabled/default \
    && mv build/nginx/default.conf /etc/nginx/conf.d/default.conf \
    && chmod 777 -R bootstrap storage \
    && npm install npm@latest -g \
    && npm install n -g \
    && n latest

EXPOSE 9000/tcp
CMD /usr/local/sbin/php-fpm
# CMD ["./build/run.sh"]
