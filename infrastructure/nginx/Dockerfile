# Nginx Web Server
FROM nginx:1.15

# Web Server Workdir
WORKDIR /var/www/html/email_microservice

# Copy Nginx config file
ADD ./infrastructure/nginx/default.conf /etc/nginx/conf.d/default.conf

# Copy public folder
COPY ./public /var/www/html/email_microservice/public
