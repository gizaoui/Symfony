# docker build -t myphpfpm .

FROM php:8.2-fpm

RUN apt-get -y update && apt-get install -y gcc make libc-dev libpq-dev wget zip git vim postgresql-client procps htop mlocate
RUN docker-php-ext-install pgsql pdo pdo_pgsql
# RUN pecl install apcu && docker-php-ext-enable apcu # Evite à recompilation des fichiers .php en bytecode
# /!\ RALENTISSEMENT SOUS DOCKER -> RUN pecl install xdebug && docker-php-ext-enable xdebug

RUN apt-get clean && rm -rf /var/lib/apt/lists/*
RUN cp /usr/local/etc/php/php.ini-production /usr/local/etc/php/php.ini
RUN echo "date.timezone=Europe/Paris" >> /usr/local/etc/php/php.ini && \
sed -i 's/^[ ;]*opcache.memory_consumption.*/opcache.memory_consumption=512/' /usr/local/etc/php/php.ini && \
sed -i 's/^[ ;]*opcache.max_accelerated_files.*/opcache.max_accelerated_files=5000/' /usr/local/etc/php/php.ini && \
sed -i 's/^[ ;]*opcache.validate_timestamps.*/opcache.validate_timestamps=1/' /usr/local/etc/php/php.ini && \
sed -i 's/^[ ;]*opcache.revalidate_freq.*/opcache.revalidate_freq=60/' /usr/local/etc/php/php.ini  && \
sed -i 's/^[ ;]*zend_extension=opcache/zend_extension=opcache/' /usr/local/etc/php/php.ini && \
sed -i 's/^[ ;]*memory_limit.*/memory_limit=-1/' /usr/local/etc/php/php.ini

# cat /usr/local/etc/php/php.ini | sed -e '/^;/d' -e '/^$/d' | grep opcache
# cat /usr/local/etc/php/php.ini | sed -e '/^;/d' -e '/^$/d' | grep memory

# Intall Composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && \
php composer-setup.php && php composer-setup.php --install-dir=/usr/local/bin --filename=composer && \
php -r "unlink('composer-setup.php');"

# Install Symfony
RUN curl -1sLf 'https://dl.cloudsmith.io/public/symfony/stable/setup.deb.sh' | bash && apt install symfony-cli


RUN useradd -u 1000 gizaoui && \
sed -i 's/^[# ]\+alias/alias/' ~/.bashrc && \
echo "alias c='cd /usr/share/nginx/html && chown -R gizaoui: simple-project && chmod -R 777 simple-project && cd simple-project && find . -name .gitignore | grep -v '^\.\/\.gitignore' | xargs rm -f && php bin/console cache:pool:clear cache.global_clearer --all && rm -fr var/cache/* && rm -fr var/log/* && cd /usr/share/nginx/html/simple-project'" >> ~/.bashrc && \
echo "alias cc='php bin/console cache:clear'" >> ~/.bashrc && \
echo "alias dr='php bin/console debug:router'" >> ~/.bashrc && \
echo "alias h='history'" >> ~/.bashrc && \
echo "alias mydb='PGPASSWORD='postgres' psql -h mypostgres -U postgres -d mydb'" >> ~/.bashrc && \
echo "alias mc='php bin/console make:controller '" >> ~/.bashrc && \
echo "alias s='source ~/.bashrc'" >> ~/.bashrc && \
echo "alias b='vim ~/.bashrc'" >> ~/.bashrc && \
echo "alias e='find . -type d -empty | xargs -I % sh -c \"touch %/.gitignore\"'" >> ~/.bashrc >> ~/.bashrc && \
echo "export PS1='\[\033[1;31m\]\u@`hostname -I | cut -d" " -f1`\[\033[00m\]:\[\033[0;37m\]\w\[\033[00m\] \$ '" >> ~/.bashrc && \
cat >> ~/.bashrc <<MAIL
sm() {
curl --url smtp://mymailpit:1025 --mail-from from@example.com --mail-rcpt to@example.com --upload-file - <<EOF
From: User Name <username@example.com>
To: John Doe <john@example.com>
Subject: You are awesome!
Content-Type: text/plain; charset="utf-8"
Content-Transfer-Encoding: quoted-printable
Content-Disposition: inline

Congrats for sending test email with Mailtrap on Linux!
EOF
}
MAIL

WORKDIR /usr/share/nginx/html
EXPOSE 9000
CMD ["php-fpm"]
