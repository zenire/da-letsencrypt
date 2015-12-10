#!/bin/sh

echo "Clone git repo"
git clone -b gh-pages git@github.com:Petertjuh360/da-letsencrypt.git /tmp/repo

latest_version=$(php version.php)
current_version=$(cat /tmp/repo/versioning/version)

if [ $latest_version = $current_version ]; then
    echo "There is no need for a new version (latest $latest_version and current $current_version)."

    exit 0;
fi

echo "Downloading new version (from $latest_version to $current_version)"

echo "Downloading composer"
curl -sS https://getcomposer.org/installer | php -- --install-dir=/tmp

echo "Downloading latest archive"
curl -Ss -o /tmp/archive.tar.gz -L https://github.com/Petertjuh360/da-letsencrypt/archive/${latest_version}.tar.gz

echo "Unpacking archive"
mkdir /tmp/archive
tar -zxvf /tmp/archive.tar.gz -C /tmp/archive

echo "Moving files to right location"
folder=$(ls /tmp/archive)

mv /tmp/archive/${folder}/* /tmp/archive/
rm -fr /tmp/archive/${folder}

echo "Install composer"
cd /tmp/archive
/tmp/composer.phar install

echo "Packing files"
tar -zcvf /tmp/archive.tar.gz *

echo "Move archive to repo"
mv -f /tmp/archive.tar.gz /tmp/repo/versioning/download.tar.gz

echo "Update version file"
echo ${latest_version} > /tmp/repo/versioning/version

echo "Commit all changes made"
cd /tmp/repo

git add -A
git commit -m "Updated to latest version (${latest_version})"
git push

echo "Remove leftover stuff"
rm -fr /tmp/repo /tmp/archive /tmp/composer.phar