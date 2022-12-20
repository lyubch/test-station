#!/usr/bin/env bash

#== Import script args ==

timezone=$1
domain=$2

#== Bash helpers ==

function info {
  echo " "
  echo "--> $1"
  echo " "
}

#== Provision script ==

info "Provision-script user: `whoami`"

export DEBIAN_FRONTEND=noninteractive

info "Configure timezone"
timedatectl set-timezone ${timezone} --no-ask-password

info "Prepare configuration for MySQL"
debconf-set-selections <<< "mysql-community-server mysql-community-server/root-pass password \"''\""
debconf-set-selections <<< "mysql-community-server mysql-community-server/re-root-pass password \"''\""
echo "Done!"

info "Prepare configuration for PhpMyAdmin"
sudo debconf-set-selections <<< "phpmyadmin phpmyadmin/dbconfig-install boolean true"
sudo debconf-set-selections <<< "phpmyadmin phpmyadmin/app-password-confirm password \"''\""
sudo debconf-set-selections <<< "phpmyadmin phpmyadmin/mysql/admin-pass password \"''\""
sudo debconf-set-selections <<< "phpmyadmin phpmyadmin/mysql/app-pass password \"''\""
sudo debconf-set-selections <<< "phpmyadmin phpmyadmin/reconfigure-webserver multiselect apache2"
echo "Done!"

info "Add external repositories"
#https://tecadmin.net/manually-upgrade-phpmyadmin-ubuntu/
#https://askubuntu.com/questions/947805/how-to-upgrade-phpmyadmin-revisited
#https://www.vultr.com/docs/upgrade-to-the-latest-phpmyadmin-on-ubuntu-18-04/
add-apt-repository -y ppa:nijel/phpmyadmin
add-apt-repository -y ppa:ondrej/php
apt-get update
apt-get upgrade -y

info "Install additional software"
apt-get install -y apache2
apt-get install -y mysql-server-5.7
apt-get install -y phpmyadmin
apt-get install -y php5.6 php5.6-cli php5.6-common php5.6-mysql php5.6-curl php5.6-gd libpcre3-dev php5.6-json php5.6-mbstring php5.6-dom php5.6-zip unzip
apt-get install -y php7.0 php7.0-cli php7.0-common php7.0-mysql php7.0-curl php7.0-gd php7.0-json php7.0-mbstring php7.0-dom php7.0-zip
apt-get install -y php7.1 php7.1-cli php7.1-common php7.1-mysql php7.1-curl php7.1-gd php7.1-json php7.1-mbstring php7.1-dom php7.1-zip
apt-get install -y php7.2 php7.2-cli php7.2-common php7.2-mysql php7.2-curl php7.2-gd php7.2-json php7.2-mbstring php7.2-dom php7.2-zip
apt-get install -y php7.3 php7.3-cli php7.3-common php7.3-mysql php7.3-curl php7.3-gd php7.3-json php7.3-mbstring php7.3-dom php7.3-zip
apt-get install -y php7.4 php7.4-cli php7.4-common php7.4-mysql php7.4-curl php7.4-gd php7.4-json php7.4-mbstring php7.4-dom php7.4-zip
apt-get install -y php8.0 php8.0-cli php8.0-common php8.0-mysql php8.0-curl php8.0-gd php8.0-mbstring php8.0-dom php8.0-zip
apt-get install -y php8.2 php8.2-cli php8.2-common php8.2-mysql php8.2-curl php8.2-gd php8.2-mbstring php8.2-dom php8.2-zip
curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

info "Configure Apache2"
a2enmod rewrite
sed -i 's/export APACHE_RUN_USER=www-data/export APACHE_RUN_USER=vagrant/g' /etc/apache2/envvars
sed -i 's/export APACHE_RUN_GROUP=www-data/export APACHE_RUN_GROUP=vagrant/g' /etc/apache2/envvars
echo "Done!"

info "Enabling site configuration"
ln -s /app/vagrant/apache2/app.conf /etc/apache2/sites-enabled/app.conf
sed -i "s/<domain>/$domain/g" /etc/apache2/sites-enabled/app.conf
echo "Done!"

info "Configure MySQL"
sed -i "s/.*bind-address.*/bind-address = 0.0.0.0/" /etc/mysql/mysql.conf.d/mysqld.cnf
sed -i "s/\[mysqld\]/\[mysqld\]\nlower_case_table_names = 1/g" /etc/mysql/mysql.conf.d/mysqld.cnf
mysql -uroot <<< "CREATE USER 'root'@'%' IDENTIFIED BY ''"
mysql -uroot <<< "GRANT ALL PRIVILEGES ON *.* TO 'root'@'%'"
mysql -uroot <<< "DROP USER 'root'@'localhost'"
mysql -uroot <<< "FLUSH PRIVILEGES"
echo "Done!"

info "Configure PhpMyAdmin"
sed -i "s/\/\/ \$cfg\['Servers'\]\[\$i\]\['AllowNoPassword'\]/\$cfg\['Servers'\]\[\$i\]\['AllowNoPassword'\]/g" /etc/phpmyadmin/config.inc.php
sed -i "s/\$cfg\['SaveDir'\] = ''\;/\$cfg\['SaveDir'\] = ''\;\n\$cfg\['LoginCookieValidity'\] = 172800\;/g" /etc/phpmyadmin/config.inc.php
sed -i "s/session.gc_maxlifetime = 1440/session.gc_maxlifetime = 172800/g" /etc/php/5.6/apache2/php.ini
echo "Done!"

info "Initailize databases for MySQL"
mysql -uroot <<< "CREATE DATABASE test_station"
echo "Done!"

info "Restart web-server"
service apache2 restart
