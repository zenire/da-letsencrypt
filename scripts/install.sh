#!/bin/sh

# GO to pluginpath
PLUGINPATH=/usr/local/directadmin/plugins/da-letsencrypt
cd ${PLUGINPATH}

# Install cronjob
echo "30 1 * * * root /usr/local/bin/php /usr/local/directadmin/plugins/da-letsencrypt/scripts/cron.php" > /etc/cron.d/letsencrypt

# Set the correct permissions
chmod -R 755 ${PLUGINPATH}
chown -R diradmin:diradmin ${PLUGINPATH}

chmod -R 777 ${PLUGINPATH}/logs
chown -R diradmin:diradmin ${PLUGINPATH}/logs

chmod 644 ${PLUGINPATH}/plugin.conf
chown diradmin:diradmin ${PLUGINPATH}/plugin.conf

chmod 666 ${PLUGINPATH}/config.conf
chown diradmin:diradmin ${PLUGINPATH}/config.conf

# Show success
echo "Successfully installed Let's Encrypt plugin to DirectAdmin, please go to the Let's Encrypt page in DirectAdmin to finish the configuration.";
exit 0;
