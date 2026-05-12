#!/bin/bash
set -e

echo "==> Instalando Composer..."
curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

echo "==> Configurando Xdebug..."
cat > /usr/local/etc/php/conf.d/xdebug.ini << EOF
[xdebug]
xdebug.mode=develop,debug
xdebug.start_with_request=yes
xdebug.client_host=host.docker.internal
xdebug.client_port=9003
xdebug.log=/tmp/xdebug.log
EOF

echo "==> Descargando CodeIgniter 3.1.13..."
if [ ! -d "/var/www/html/app/system" ]; then
  curl -L https://github.com/bcit-ci/CodeIgniter/archive/refs/tags/3.1.13.zip -o /tmp/ci.zip
  unzip /tmp/ci.zip -d /tmp/ci_extracted
  cp -r /tmp/ci_extracted/CodeIgniter-3.1.13/. /var/www/html/app/
  rm -rf /tmp/ci.zip /tmp/ci_extracted
  echo "==> CodeIgniter 3.1.13 instalado en /var/www/html/app"
fi

echo "✅ Setup completo. Abre http://localhost:8080"