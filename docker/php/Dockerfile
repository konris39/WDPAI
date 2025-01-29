FROM php:7.4.3-fpm-alpine3.11

RUN apk add --no-cache --virtual .build-deps gcc libpng-dev libjpeg-turbo-dev freetype-dev \
    && apk add --no-cache postgresql-dev \
    && docker-php-ext-install pdo_pgsql pgsql \
    && apk del .build-deps

COPY . /app

VOLUME [ "/app" ]

EXPOSE 9000

CMD ["php-fpm"]