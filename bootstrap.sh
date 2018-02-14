#!/usr/bin/env bash

# get root access
sudo su

apt-add-repository ppa:ondrej/php
sudo apt-key adv --recv-keys --keyserver hkp://keyserver.ubuntu.com:80 0xF1656F24C74CD1D8
sudo sh -c "echo 'deb https://mirrors.evowise.com/mariadb/repo/10.2/ubuntu '$(lsb_release -cs)' main' > /etc/apt/sources.list.d/MariaDB102.list"
apt-get update -y && apt-get upgrade -y

# install git
apt-get install -y git

# install zip
apt-get install -y zip

# install nginx
apt-get install -y nginx
service nginx start

# install node
curl -sL https://deb.nodesource.com/setup_9.x | sudo -E bash -
sudo apt-get install -y nodejs

# install php
apt-get install -y php7.1 php7.1-cli php7.1-fpm php7.1-mysql php7.1-curl php7.1-dev php7.1-mcrypt php7.1-sqlite3 php7.1-mbstring php7.1-xml php7.1-zip
sed -i.bak 's/^;cgi.fix_pathinfo.*$/cgi.fix_pathinfo = 1/g' /etc/php/7.1/fpm/php.ini
sed -i 's/user = www-data/user = vagrant/g' /etc/php/7.1/fpm/pool.d/www.conf
sed -i 's/group = www-data/group = vagrant/g' /etc/php/7.1/fpm/pool.d/www.conf
service php7.1-fpm restart

# install composer globally
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php -r "if (hash_file('SHA384', 'composer-setup.php') === '544e09ee996cdf60ece3804abc52599c22b1f40f4323403c44d44fdfdd586475ca9813a858088ffbc1f233e9b180f061') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
php composer-setup.php
php -r "unlink('composer-setup.php');"
mv composer.phar /usr/local/bin/composer

# install mariadb
export DEBIAN_FRONTEND=noninteractive
debconf-set-selections <<< 'mariadb-server-10.2 mysql-server/root_password password password'
debconf-set-selections <<< 'mariadb-server-10.2 mysql-server/root_password_again password password'
apt-get install -y --force-yes mariadb-server

# set up nginx server
# Configure host
cat << 'EOF' > /etc/nginx/sites-available/default
server {
    server_name rengform.test www.rengform.test;
    root /srv/public;

    location / {
        try_files $uri /index.php$is_args$args;
    }

    location ~ ^/index\.php(/|$) {
        fastcgi_pass unix:/var/run/php/php7.1-fpm.sock;
        fastcgi_split_path_info ^(.+\.php)(/.*)$;
        include fastcgi_params;

        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        fastcgi_param DOCUMENT_ROOT $realpath_root;
        internal;
    }

    location ~ \.php$ {
        return 404;
    }

    error_log /var/log/nginx/project_error.log;
    access_log /var/log/nginx/project_access.log;
}
EOF

service nginx restart
service php7.1-fpm restart

# set locale
export LC_ALL=C
