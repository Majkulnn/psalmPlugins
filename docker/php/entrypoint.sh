#!/bin/bash

set -e

mkdir -p \
    /var/cache/composer \
    /var/cache/psalm \
    /var/cache/phpunit \
    /var/cache/php-cs-fixer

chown -R appuser:"$(id -gn appuser)" /var/cache

exec gosu appuser "$@"