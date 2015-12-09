#!/bin/sh
#
# Created by Peter Bin

# Install dependencies using Composer
curl -sS https://getcomposer.org/installer | php -- --install-dir=/tmp
/tmp/composer.phar install
rm -f /tmp/composer.phar

# Plugin
PLUGINPATH=/usr/local/directadmin/plugins/da_letsencrypt
cd ${PLUGINPATH}

chmod -R 755 ${PLUGINPATH}/admin
chown -R diradmin:diradmin ${PLUGINPATH}/admin

chmod -R 755 ${PLUGINPATH}/reseller
chown -R diradmin:diradmin ${PLUGINPATH}/reseller

chmod -R 755 ${PLUGINPATH}/user
chown -R diradmin:diradmin ${PLUGINPATH}/user

chmod -R 777 ${PLUGINPATH}/logs
chown -R diradmin:diradmin ${PLUGINPATH}/logs

chmod 644 ${PLUGINPATH}/plugin.conf
chown diradmin:diradmin ${PLUGINPATH}/plugin.conf

exit 0;