#!/bin/sh
set -eu

if [ ! -f /var/www/html/index.php ]; then
  echo "HOPP WordPress bootstrap: populating core files into /var/www/html"
  tar cf - --one-file-system -C /usr/src/wordpress . | tar xf - -C /var/www/html
  chown -R www-data:www-data /var/www/html
fi

if [ -d /usr/local/share/hopp/mu-plugins ]; then
  mkdir -p /var/www/html/wp-content/mu-plugins

  for file in /usr/local/share/hopp/mu-plugins/*.php; do
    [ -e "$file" ] || continue
    cp "$file" /var/www/html/wp-content/mu-plugins/
    chown www-data:www-data "/var/www/html/wp-content/mu-plugins/$(basename "$file")"
  done
fi

mkdir -p /var/www/html/wp-content/uploads
chown -R www-data:www-data /var/www/html/wp-content/uploads

php /usr/local/share/hopp/apply-aba-payway-patch.php

exec docker-entrypoint.sh apache2-foreground
