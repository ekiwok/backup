FROM php:7.2-cli-alpine3.7

RUN apk add libzip-dev mariadb-client --update-cache && \
    docker-php-ext-install zip

COPY package/wp-backup.phar /usr/local/bin/wp-backup.phar
RUN chmod +x /usr/local/bin/wp-backup.phar

CMD /usr/local/bin/wp-backup.phar wp-backup -vvv