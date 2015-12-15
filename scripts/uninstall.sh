#!/bin/sh

#Remove plugin
rm -rf /usr/local/directadmin/plugins/da_letsencrypt

#Remove cronjob
rm -rf /etc/cron.d/letsencrypt

echo "Plugin is removed from DirectAdmin! Note: SSL certificates managed by this plugin isn\'t removed.";
exit 0;
