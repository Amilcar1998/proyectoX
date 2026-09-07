#!/bin/bash
set -e

# Purgar cualquier módulo MPM duplicado (como mpm_event o mpm_worker)
rm -f /etc/apache2/mods-enabled/mpm_event.load /etc/apache2/mods-enabled/mpm_event.conf
rm -f /etc/apache2/mods-enabled/mpm_worker.load /etc/apache2/mods-enabled/mpm_worker.conf

# Garantizar que únicamente mpm_prefork esté activo
if [ ! -f /etc/apache2/mods-enabled/mpm_prefork.load ]; then
    ln -sf /etc/apache2/mods-available/mpm_prefork.load /etc/apache2/mods-enabled/mpm_prefork.load
fi
if [ -f /etc/apache2/mods-available/mpm_prefork.conf ] && [ ! -f /etc/apache2/mods-enabled/mpm_prefork.conf ]; then
    ln -sf /etc/apache2/mods-available/mpm_prefork.conf /etc/apache2/mods-enabled/mpm_prefork.conf
fi

# Configurar el puerto dinámico asignado por Railway ($PORT)
PORT="${PORT:-80}"
sed -i "s/Listen .*/Listen ${PORT}/" /etc/apache2/ports.conf
sed -i "s/<VirtualHost \*:[0-9]*>/<VirtualHost *:${PORT}>/" /etc/apache2/sites-available/000-default.conf

# Pasar todas las variables de entorno del contenedor a Apache y PHP
> /etc/apache2/conf-enabled/railway-env.conf
env | while IFS='=' read -r key val; do
    if [ -n "$key" ]; then
        echo "PassEnv $key" >> /etc/apache2/conf-enabled/railway-env.conf
        echo "export $key=\"\$$key\"" >> /etc/apache2/envvars
    fi
done

echo "=== Arrancando Apache en el puerto ${PORT} con mpm_prefork ==="

exec "$@"
