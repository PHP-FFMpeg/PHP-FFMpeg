#!/bin/sh -l

cd /php-ffmpeg || exit 1

composer install --no-interaction --prefer-dist

vendor/bin/phpunit

exit 0
