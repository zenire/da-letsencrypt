#!/bin/sh

curl -sS https://getcomposer.org/installer | php -- --install-dir=/tmp

php cron.php -c php.ini

# Commit all stuff