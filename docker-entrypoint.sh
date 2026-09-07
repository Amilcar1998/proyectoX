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

# Generar archivo PHP de entorno dinámico al iniciar el contenedor
cat << 'EOF' > /var/www/html/db/env_runtime.php
<?php
EOF

# Pasar variables de entorno a PHP y Apache
> /etc/apache2/conf-enabled/railway-env.conf
env | while IFS='=' read -r key val; do
    if echo "$key" | grep -qE '^[a-zA-Z_][a-zA-Z0-9_]*$'; then
        echo "PassEnv $key" >> /etc/apache2/conf-enabled/railway-env.conf
        # Escapar comillas dobles y diagonales invertidas para PHP seguro
        safe_val=$(printf '%s' "$val" | sed 's/\\/\\\\/g; s/"/\\"/g')
        echo "putenv(\"$key=$safe_val\"); \$_ENV['$key'] = \"$safe_val\"; \$_SERVER['$key'] = \"$safe_val\";" >> /var/www/html/db/env_runtime.php
    fi
done

echo "=== Arrancando Apache en el puerto ${PORT} con mpm_prefork ==="

exec "$@"
