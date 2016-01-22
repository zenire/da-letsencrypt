#!/bin/sh
#
# Created by Peter Bin

PLUGINPATH=/usr/local/directadmin/plugins/da-letsencrypt
cd ${PLUGINPATH}

# Install cronjob
echo "30 1 * * * root /usr/local/bin/php /usr/local/directadmin/plugins/da-letsencrypt/scripts/cron.php" > /etc/cron.d/letsencrypt

# Plugin
chmod -R 755 ${PLUGINPATH}
chown -R diradmin:diradmin ${PLUGINPATH}

chmod -R 777 ${PLUGINPATH}/logs
chown -R diradmin:diradmin ${PLUGINPATH}/logs

chmod 644 ${PLUGINPATH}/plugin.conf
chown diradmin:diradmin ${PLUGINPATH}/plugin.conf

chmod 666 ${PLUGINPATH}/config.conf
chown diradmin:diradmin ${PLUGINPATH}/config.conf

echo "Successfully installed Let's Encrypt plugin to DirectAdmin.";

exit 0;
