#!/bin/sh
set -eu

if [ ! -f /var/www/html/index.php ]; then
  echo "HOPP WordPress bootstrap: populating core files into /var/www/html"
  tar cf - --one-file-system -C /usr/src/wordpress . | tar xf - -C /var/www/html
  chown -R www-data:www-data /var/www/html
fi

php /usr/local/share/hopp/apply-aba-payway-patch.php

exec docker-entrypoint.sh apache2-foreground
