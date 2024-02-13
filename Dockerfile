# PHP Images can be found at https://hub.docker.com/_/php/
FROM php:8.2-alpine

# The application will be copied in /home/application and the original document root will be replaced in the apache configuration 
COPY . /home/application/ 

# Custom Document Root
ENV APACHE_DOCUMENT_ROOT /home/application/public

# Concatenated RUN commands
RUN apk add --update --no-cache openssh nano bash sudo
RUN apk add --update apache2 php-apache2 mariadb-client php-fileinfo php-mbstring php-session php-json php-pdo php-openssl php-tokenizer php-pdo php-pdo_mysql php-xml php-simplexml\
    && docker-php-ext-install pdo_mysql mysqli pdo \
    && chmod -R 777 /home/application/storage \
    && chown -R www-data:www-data /home/application \
    && mkdir -p /run/apache2 \
    && sed -i '/LoadModule rewrite_module/s/^#//g' /etc/apache2/httpd.conf \
    && sed -i '/LoadModule session_module/s/^#//g' /etc/apache2/httpd.conf \
    && sed -i '/extension=fileinfo/s/^;//g' /etc/php82/php.ini \
    && sed -ri -e 's!/var/www/localhost/htdocs!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/httpd.conf \
    && sed -i 's/AllowOverride\ None/AllowOverride\ All/g' /etc/apache2/httpd.conf \
    && rm  -rf /tmp/* /var/cache/apk/*
RUN cd /home/application && php artisan key:generate
RUN cd /home/application && php artisan migrate
RUN cd /home/application && php artisan storage:link
RUN chmod -R 777 /home/application/storage 
RUN chown -R www-data:www-data /home/application 

# Configure ssh and add user to sudo
RUN echo 'PasswordAuthentication yes' >> /etc/ssh/sshd_config
RUN adduser -h /home/application -s /bin/sh -D myuser
RUN echo -n 'myuser:inipasswordnya' | chpasswd
RUN echo '%wheel ALL=(ALL) ALL' > /etc/sudoers.d/wheel
RUN adduser myuser wheel
RUN ssh-keygen -A

# Start ssh and apache server
CMD rm -rf /run/apache2/* 
EXPOSE 22
COPY entrypoint.sh /
ENTRYPOINT [ "/entrypoint.sh" ]