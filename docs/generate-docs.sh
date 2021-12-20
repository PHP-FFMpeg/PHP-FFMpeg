#!/bin/bash

set -e

SELF_DIR=$(dirname $0)
cd ${SELF_DIR}

# Show the version to inform users of the script
php ../vendor/code-lts/doctum/bin/doctum.php --version
php ../vendor/code-lts/doctum/bin/doctum.php update --force --ignore-parse-errors doctum.php -v