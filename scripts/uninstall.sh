#!/bin/sh
#
#Created by Peter Bin

#Plugin
PLUGINPATH=/usr/local/directadmin/plugins/da_letsencrypt
rm -rf ${PLUGINPATH}

echo "Plugin is removed from DirectAdmin! Note: Lets Encrypt is not removed.";
exit 0;