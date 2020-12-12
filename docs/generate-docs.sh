#!/bin/bash

set -e

SELF_DIR=$(dirname $0)
cd ${SELF_DIR}

rm -f doctum.phar
rm -f doctum.phar.sha256

# Download the latest (5.1.x) release if the file does not exist
# Remove it to update your phar
curl -o doctum.phar https://doctum.long-term.support/releases/5.1/doctum.phar
curl -o doctum.phar.sha256 https://doctum.long-term.support/releases/5.1/doctum.phar.sha256

sha256sum --strict --check doctum.phar.sha256
rm doctum.phar.sha256

# You can fetch the latest (5.1.x) version code here:
# https://doctum.long-term.support/releases/5.1/VERSION


# Show the version to inform users of the script
php doctum.phar --version
php doctum.phar update --force --ignore-parse-errors doctum.php -v
