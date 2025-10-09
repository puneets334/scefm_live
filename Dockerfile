########################################################
FROM php:8.4-apache AS app-php

EXPOSE 80
RUN apt-get update && apt-get install -y apt-utils cron curl nano poppler-utils qpdf libpq-dev libxml2-dev zlib1g-dev libzip-dev \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libonig-dev libapache2-mod-security2
RUN docker-php-ext-install -j$(nproc) zip intl gd mbstring
RUN docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install mysqli pgsql pdo_mysql pdo_pgsql soap

RUN cd /usr/local/etc/php/conf.d/ && \
    echo 'memory_limit = -1' >> /usr/local/etc/php/conf.d/docker-php-memlimit.ini

RUN cd /usr/local/etc/php/conf.d/ && \
    echo 'post_max_size = 50M' >> /usr/local/etc/php/conf.d/docker-php-memlimit.ini

    RUN cd /usr/local/etc/php/conf.d/ && \
    echo 'upload_max_filesize = 50M' >> /usr/local/etc/php/conf.d/docker-php-memlimit.ini

COPY sysconf/docker/apache2/000-default.conf /etc/apache2/sites-available/
COPY sysconf/docker/apache2/ports.conf /etc/apache2/

COPY . /var/www/html/
#RUN  mkdir -p /var/www/html/uploaded_documents && chown -R www-data:www-data /var/www/html && chmod -R 500 /var/www/html && chmod -R 777 /var/www/html/writable && chmod -R 777 /var/www/html/public/index.php  && chmod -R 777 /var/www/html/writable/ && chmod -R 777 /var/www/html/uploaded_documents && chmod -R 777 /mnt/icmis_doc/uploaded_documents

RUN  chown -R www-data:www-data /var/www/html && chmod -R 500 /var/www/html && chmod -R 777 /var/www/html/writable && chmod -R 777 /var/www/html/public/index.php  && chmod -R 777 /var/www/html/writable/
RUN rm -rf /var/www/html/.git/
RUN a2enmod rewrite headers proxy
ENV APACHE_RUN_USER www-data
ENV APACHE_RUN_GROUP www-data
RUN service apache2 restart


# Judge Appointment System '
#Step 1:
# sudo docker build -t scefm30_velocis .
#sudo docker run --name=scefm30_velocis -dit --restart unless-stopped -p 86:80 -v $(pwd):/var/www/html/  scefm30_velocis:latest
#sudo docker run --name=scefm30_velocis -dit --restart unless-stopped -p 90:80 -v $(pwd):/var/www/html/  scefm30_velocis:latest
#Docker run command (Only dev/local env) -> sudo docker run --name=scefm30_velocis -dit --restart unless-stopped -p 86:80 -v $(pwd):/var/www/html -v /mnt/efiling_near/uploaded_docs:/var/www/html/public/uploaded_docs scefm30_velocis:latest
#Step 3:
# sudo docker exec -it scefm30_velocis /bin/bash
#Step 4:
#root@aedf3a0e087d:/var/www/html# chmod -R 777 ./writable
#root@aedf3a0e087d:/var/www/html# chmod -R 777 ./writable/session/
#root@aedf3a0e087d:/var/www/html# chmod -R 777 ./uploaded_documents
#Step 5:
#root@aedf3a0e087d:/var/www/html# ln -s /mnt/icmis_doc/uploaded_documents ~ ./uploaded_documents

