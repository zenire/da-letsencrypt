#!/bin/sh

#Remove plugin
rm -rf /usr/local/directadmin/plugins/da_letsencrypt

#Remove cronjob
rm -rf /etc/cron.d/letsencrypt

echo "Plugin is removed from DirectAdmin! Note: SSL certificates managed by this plugin aren\'t removed. SSL certificates installed by this plugin will not be automatically renewed anymore. Check the expire date of the SSL certificates to prevent using an invalid SSL certificate after 90 days. Also you need to remove the cronjob installed by this plugin.";
exit 0;
