# Let's Encrypt plugin for DA
Welcome to this repository of an unofficial Let's Encrypt plugin for DirectAdmin. With this plugin it should become very easy and fast to request and automatically install and renew certificates of Let's Encrypt for your domain managed by DirectAdmin.

## This plugin is in active development. We would like you to help us with the plugin! Create issues, help with the code or improve the wiki. Thank you for helping!

## Get started
However the plugin is not recommended for production use yet, we want you to offer the possibility to use this plugin. Run the following commands via your terminal or SSH. **You must have Git installed for this way to get started!**

### Install
`cd /usr/local/directadmin/plugins`  
`git clone https://github.com/Petertjuh360/da-letsencrypt.git da-letsencrypt`  
`cd ./da-letsencrypt/`  
`chown diradmin:diradmin -hR ../da-letsencrypt/`  
`sh ./scripts/install.sh`  
`composer install`  
`chown diradmin:diradmin -hR ../da-letsencrypt/` 
Change `active=no` and `installed=no` to `active=yes` and `installed=yes` in `plugin.conf`.  

### Update
`cd /usr/local/directadmin/plugins/da-letsencrypt`  
`git pull`  
`composer update`  
