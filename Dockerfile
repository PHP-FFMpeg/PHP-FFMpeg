FROM alpine:3.14

COPY docker/entrypoint.sh /entrypoint.sh

RUN apk add --no-cache composer php7-dom php7-xml php7-xmlwriter php7-pdo php7-tokenizer ffmpeg \
 && chmod +x /entrypoint.sh

COPY src /php-ffmpeg/src
COPY tests /php-ffmpeg/tests
COPY composer.json /php-ffmpeg/composer.json
COPY phpunit.xml.dist /php-ffmpeg/phpunit.xml.dist

VOLUME /php-ffmpeg/vendor

ENTRYPOINT ["/entrypoint.sh"]
