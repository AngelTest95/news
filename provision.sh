#!/bin/bash
domain=$(echo "$1")
webroot=$(echo "$2")
timezone=$(echo "$3")

export DEBIAN_FRONTEND=noninteractive

# Configure timezone
timedatectl set-timezone ${timezone} --no-ask-password

# Prepare root password for MySQL
debconf-set-selections <<< "mysql-community-server mysql-community-server/root-pass password \"''\""
debconf-set-selections <<< "mysql-community-server mysql-community-server/re-root-pass password \"''\""

# Update OS software
apt-get update
apt-get upgrade -y
apt-get dist-upgrade -y
apt-get autoremove -y

# Install software
apt-get install nginx mysql-server php-fpm php-mysql php7.0-curl php7.0-cli php7.0-intl php7.0-gd php7.0-zip php7.0-bcmath php7.0-fpm php7.0-mbstring php7.0-bz2 php7.0-mcrypt php7.0-xml php-xdebug php7.0-soap redis-server unzip unrar htop build-essential -y

# PHP configuration
sed -i "s/;cgi.fix_pathinfo=1/cgi.fix_pathinfo=0/g" /etc/php/7.0/fpm/php.ini
sed -i "s/post_max_size = 8M/post_max_size = 128M/g" /etc/php/7.0/fpm/php.ini
sed -i "s/upload_max_filesize = 2M/upload_max_filesize = 128M/g" /etc/php/7.0/fpm/php.ini

# Configure MySQL
sed -i "s/.*bind-address.*/bind-address = 0.0.0.0/" /etc/mysql/mysql.conf.d/mysqld.cnf
mysql -uroot <<< "CREATE USER 'root'@'%' IDENTIFIED BY ''"
mysql -uroot <<< "GRANT ALL PRIVILEGES ON *.* TO 'root'@'%' WITH GRANT OPTION"
mysql -uroot <<< "DROP USER 'root'@'localhost'"
mysql -uroot <<< "FLUSH PRIVILEGES"

systemctl restart mysql

# Configure Xdebug
cat << EOF > /etc/php/7.0/mods-available/xdebug.ini
zend_extension=xdebug.so
xdebug.remote_enable=1
xdebug.remote_connect_back=1
xdebug.remote_port=9000
EOF

# Install MailHog
wget https://github.com/mailhog/MailHog/releases/download/v1.0.0/MailHog_linux_amd64
mv MailHog_linux_amd64 /usr/local/bin/mailhog
chmod +x /usr/local/bin/mailhog
sed -i "s/;sendmail_path =/sendmail_path='\/usr\/local\/bin\/mailhog sendmail foo@example.com'/g" /etc/php/7.0/fpm/php.ini

systemctl restart php7.0-fpm

# Install phpMyAdmin
wget https://files.phpmyadmin.net/phpMyAdmin/4.7.9/phpMyAdmin-4.7.9-all-languages.zip
unzip phpMyAdmin-4.7.9-all-languages.zip
rm phpMyAdmin-4.7.9-all-languages.zip
mv phpMyAdmin-4.7.9-all-languages /var/www/phpmyadmin
cp /var/www/phpmyadmin/config.sample.inc.php /var/www/phpmyadmin/config.inc.php
sed -i "s/\$cfg\['Servers'\]\[\$i\]\['auth_type'\] = 'cookie';/\$cfg\['Servers'\]\[\$i\]\['auth_type'\] = 'config'; \$cfg\['Servers'\]\[\$i\]\['user'\] = 'root'; \$cfg\['Servers'\]\[\$i\]\['password'\] = '';/g" /var/www/phpmyadmin/config.inc.php
sed -i "s/\$cfg\['Servers'\]\[\$i\]\['AllowNoPassword'\] = false;/\$cfg\['Servers'\]\[\$i\]\['AllowNoPassword'\] = true;/g" /var/www/phpmyadmin/config.inc.php

mysql -uroot < /var/www/phpmyadmin/sql/create_tables.sql
mysql -uroot -e 'GRANT SELECT, INSERT, DELETE, UPDATE ON phpmyadmin.* TO 'pma'@'localhost' IDENTIFIED BY "pmapass"'
mysql -uroot -e 'FLUSH PRIVILEGES'

sed -i "s/\/\/ \$cfg\['Servers'\]\[\$i\]/\$cfg\['Servers'\]\[\$i\]/g" /var/www/phpmyadmin/config.inc.php
sed -i "s/\$cfg\['Servers'\]\[\$i\]\['controlhost'\]/\/\/ \$cfg\['Servers'\]\[\$i\]\['controlhost'\]/g" /var/www/phpmyadmin/config.inc.php
sed -i "s/\$cfg\['Servers'\]\[\$i\]\['controlport'\]/\/\/ \$cfg\['Servers'\]\[\$i\]\['controlport'\]/g" /var/www/phpmyadmin/config.inc.php

# Install Composer
curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

cat << EOF >> /home/vagrant/.profile
export PATH="$PATH:/home/vagrant/.config/composer/vendor/bin"
EOF

# Configure NGINX
sed -i "s/# server_names_hash_bucket_size 64/server_names_hash_bucket_size 64/g" /etc/nginx/nginx.conf

# Domain server block
cat << EOF > /etc/nginx/sites-available/${domain}
server {
    listen 80;
    listen [::]:80;

    client_max_body_size 128M;

    root /vagrant/${webroot};
    index index.php index.html index.htm index.nginx-debian.html;

    server_name ${domain} www.${domain};

    location / {
        try_files \$uri \$uri/ /index.php\$is_args\$args;
    }

    location ~ \.php\$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/run/php/php7.0-fpm.sock;
    }

    location ~ /\.(ht|svn|git) {
       deny all;
    }
}
EOF

ln -s /etc/nginx/sites-available/${domain} /etc/nginx/sites-enabled/

# phpMyAdmin server block
cat << EOF > /etc/nginx/sites-available/phpmyadmin.local
server {
    listen 80;
    listen [::]:80;

    client_max_body_size 128M;

    root /var/www/phpmyadmin;
    index index.php index.html index.htm index.nginx-debian.html;

    server_name phpmyadmin.local www.phpmyadmin.local;

    location / {
        try_files \$uri \$uri/ /index.php\$is_args\$args;
    }

    location ~ \.php\$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/run/php/php7.0-fpm.sock;
    }

    location ~ /\.(ht|svn|git) {
       deny all;
    }
}
EOF

ln -s /etc/nginx/sites-available/phpmyadmin.local /etc/nginx/sites-enabled/

# MailHog proxy server block
cat << EOF > /etc/nginx/sites-available/mailhog.local
server {
    listen 80;
    listen [::]:80;

    server_name mailhog.local www.mailhog.local;

    location / {
        proxy_pass http://localhost:8025;
        proxy_http_version 1.1;
        proxy_set_header Upgrade \$http_upgrade;
        proxy_set_header Connection "upgrade";
    }
}
EOF

ln -s /etc/nginx/sites-available/mailhog.local /etc/nginx/sites-enabled/

systemctl restart nginx
