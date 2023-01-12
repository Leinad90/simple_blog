FROM nginx:latest   
COPY ./default.conf /etc/nginx/conf.d/default.conf
RUN composer install -d /var/www/html
