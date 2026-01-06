#!/bin/sh

# ถ้า Railway ส่ง PORT มา
if [ -n "$PORT" ]; then
  echo "Listen $PORT" > /etc/apache2/ports.conf
  sed -i "s/:80/:$PORT/g" /etc/apache2/sites-enabled/000-default.conf
fi

exec apache2-foreground
