#!/usr/bin/env bash

# set -e
set -x

cd package
composer install
php -d phar.readonly=off /usr/local/bin/phar-composer build
cd -
cd test
docker-compose build
docker-compose up -d
docker-compose run backup
rm -rf data-dst/test-backup
mkdir data-dst/test-backup
tar -zxvf  data-dst/test-backup.tar.gz -C data-dst/test-backup
rm -rf data-dst/test-backup.tar.gz
cd -

