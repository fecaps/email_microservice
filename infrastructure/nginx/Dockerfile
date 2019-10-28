# Nginx Web Server
FROM nginx:1.15

# Copy Nginx config file
ADD ./infrastructure/nginx/default.conf /etc/nginx/conf.d/default.conf

# Copy public folder
COPY ./public /var/www/html/email_microservice/public
