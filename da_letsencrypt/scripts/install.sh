#!/bin/sh
#
#Created by Peter Bin

#Clone Let's Encrypt Git and install Let's Encrypt
git clone https://github.com/letsencrypt/letsencrypt ~/letsencrypt
~/letsencrypt/letsencrypt-auto

#Plugin
PLUGINPATH=/usr/local/directadmin/plugins/da_letsencrypt
cd ${PLUGINPATH}
chmod -R 755 ${PLUGINPATH}/admin
chown -R diradmin:diradmin ${PLUGINPATH}/admin

chmod -R 755 ${PLUGINPATH}/reseller
chown -R diradmin:diradmin ${PLUGINPATH}/reseller

chmod -R 755 ${PLUGINPATH}/user
chown -R diradmin:diradmin ${PLUGINPATH}/user

chmod 644 ${PLUGINPATH}/plugin.conf
chown diradmin:diradmin ${PLUGINPATH}/plugin.conf

exit 0;