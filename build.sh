#!/bin/bash

/usr/bin/env php composer.phar install --dev
/usr/bin/env php vendor/bin/php-cs-fixer fix src --level=all
/usr/bin/env php vendor/bin/php-cs-fixer fix tests --level=all
/usr/bin/env php vendor/bin/sami.php update sami_configuration.php -v

