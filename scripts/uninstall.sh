#!/bin/sh
#
#Created by Peter Bin

#Plugin
PLUGINPATH=/usr/local/directadmin/plugins/da_letsencrypt
rm -rf ${PLUGINPATH}

# Delete cronjob
rm -fr /etc/cron.d/letsencrypt

echo "Plugin is removed from DirectAdmin! Note: SSL certificates managed by this plugin isn\'t removed.";
exit 0;