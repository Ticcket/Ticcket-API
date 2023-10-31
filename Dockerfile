FROM composer:latest

COPY . /app/

WORKDIR /app/

RUN composer install

RUN docker-php-ext-install mysqli pdo pdo_mysql

EXPOSE 8000

RUN chmod 777 ./init.sh

CMD [ "./init.sh" ]
