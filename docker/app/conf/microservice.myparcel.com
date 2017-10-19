server {
    listen 443 ssl http2;
    listen [::]:443 ssl http2;
    server_name ${APP_DOMAIN};

    include snippets/snakeoil.conf;

    root /opt/microservice/public;
    index index.php;

    access_log /dev/stdout combined;
    error_log /dev/stderr warn;

    location / {
        try_files $uri $uri/ /index.php$is_args$args;
    }

    location ~ \.php$ {
        if ($request_method = 'OPTIONS') {
            access_log off;
        }
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/run/php/php7.1-fpm.sock;
    }
}
