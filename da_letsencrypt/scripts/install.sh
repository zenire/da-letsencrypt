#!/bin/sh
#
#Created by Peter Bin

#Plugin
PLUGINPATH=/usr/local/directadmin/plugins/da_letsencrypt
cd ${PLUGINPATH}

chmod -R 755 ${PLUGINPATH}/admin
chown -R diradmin:diradmin ${PLUGINPATH}/admin

chmod -R 755 ${PLUGINPATH}/reseller
chown -R diradmin:diradmin ${PLUGINPATH}/reseller

chmod -R 755 ${PLUGINPATH}/user
chown -R diradmin:diradmin ${PLUGINPATH}/user

chmod -R 755 ${PLUGINPATH}/hooks
chown -R diradmin:diradmin ${PLUGINPATH}/hooks

chmod -R 755 ${PLUGINPATH}/logs
chown -R diradmin:diradmin ${PLUGINPATH}/logs

chmod 644 ${PLUGINPATH}/plugin.conf
chown diradmin:diradmin ${PLUGINPATH}/plugin.conf

exit 0;