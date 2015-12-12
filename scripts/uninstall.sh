#!/bin/sh
#
#Created by Peter Bin

#Plugin
PLUGINPATH=/usr/local/directadmin/plugins/da_letsencrypt
rm -rf ${PLUGINPATH}

echo "Plugin is removed from DirectAdmin! Note: SSL certificates managed by this plugin isn\'t removed.";
exit 0;