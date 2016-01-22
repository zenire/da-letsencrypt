# Let's Encrypt plugin for DA
Welcome to this repository of an unofficial Let's Encrypt plugin for DirectAdmin. With this plugin it should become very easy and fast to request and automatically install and renew certificates of Let's Encrypt for your domain managed by DirectAdmin.

## This plugin is in active development. We would like you to help us with the plugin! Create issues, help with the code or improve the wiki. Thank you for helping!

## Get started
However the plugin is not recommended for production use yet, we want you to offer the possibility to test this plugin. This is for development and testing propose only. If you're testing this plugin and submit an issue, please provide more debug information, so we're able to solve this issue. Run the following commands via your terminal or SSH. **You must have Git AND Composer installed and working for this way to get started!**

## Requirements
- DirectAdmin 1.49.2 and up (less also supported, but you won't receive a [notification](https://www.directadmin.com/features.php?id=1829).
- PHP 5.5 and up
- Login Key (recommended)

### Install
#### Install Composer
Skip this step if you already have Composer installed.  
```
curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer
```
#### Install Plugin
```
cd /usr/local/directadmin/plugins
git clone https://github.com/Petertjuh360/da-letsencrypt.git da-letsencrypt
cd ./da-letsencrypt/
chown diradmin:diradmin -hR ../da-letsencrypt/
sh ./scripts/install.sh
composer install
chown diradmin:diradmin -hR ../da-letsencrypt/
```
Change `active=no` and `installed=no` to `active=yes` and `installed=yes` in `plugin.conf`.  

### Update
```
cd /usr/local/directadmin/plugins/da-letsencrypt
git pull
composer update
```
