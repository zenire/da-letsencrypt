#!/bin/sh

curl -sS https://getcomposer.org/installer | php -- --install-dir=/tmp

git clone -b gh-pages git@github.com:Petertjuh360/da-letsencrypt.git /tmp/repo

php cron.php -c php.ini

cd /tmp/repo

git add -A
git commit -m "Updated to new version"
git push

rm -fr /tmp/repo