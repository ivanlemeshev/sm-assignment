FROM php:7.4-cli-alpine

RUN cd /usr/local/bin && curl -sS https://getcomposer.org/installer | php
RUN cd /usr/local/bin && mv composer.phar composer
RUN composer global require hirak/prestissimo
