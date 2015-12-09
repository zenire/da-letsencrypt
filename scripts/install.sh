#!/bin/sh
#
# Created by Peter Bin

PLUGINPATH=/usr/local/directadmin/plugins/da-letsencrypt
cd ${PLUGINPATH}

# Install dependencies using Composer
curl -sS https://getcomposer.org/installer >> /tmp/composer_installer
php /tmp/composer_installer --quiet --install-dir=/tmp
/tmp/composer.phar install -q
rm -f /tmp/composer.phar /tmp/installer

# Plugin
chmod -R 755 ${PLUGINPATH}/admin
chown -R diradmin:diradmin ${PLUGINPATH}/admin

chmod -R 755 ${PLUGINPATH}/reseller
chown -R diradmin:diradmin ${PLUGINPATH}/reseller

chmod -R 755 ${PLUGINPATH}/user
chown -R diradmin:diradmin ${PLUGINPATH}/user

chmod -R 755 ${PLUGINPATH}/hooks
chown -R diradmin:diradmin ${PLUGINPATH}/hooks

chmod -R 777 ${PLUGINPATH}/logs
chown -R diradmin:diradmin ${PLUGINPATH}/logs

chmod 644 ${PLUGINPATH}/plugin.conf
chown diradmin:diradmin ${PLUGINPATH}/plugin.conf

echo "Successfully installed Let's Encrypt plugin for DirectAdmin.";

exit 0;