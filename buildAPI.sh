#!/bin/bash

rm -Rf docs/source/API/API
/usr/bin/env php vendor/bin/sami.php update sami_configuration.php
sh -c "cd docs && make clean && make html"
