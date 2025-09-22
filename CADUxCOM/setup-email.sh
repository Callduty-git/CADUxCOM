#!/bin/bash

echo "🔧 Configurando sistema de email para CADUxCOM"
echo "=============================================="

# Verificar si existe el archivo .env
if [ ! -f .env ]; then
    echo "❌ No se encontró el archivo .env"
    exit 1
fi

echo "📧 Configuración actual de email:"
echo "--------------------------------"
grep -E "MAIL_" .env

echo ""
echo "🔑 Para configurar Gmail correctamente:"
echo "1. Ve a tu cuenta de Google: caduxcom.store@gmail.com"
echo "2. Seguridad → Verificación en 2 pasos (actívala si no está)"
echo "3. Busca 'Contraseñas de aplicaciones'"
echo "4. Genera una nueva contraseña para 'Mail'"
echo "5. Copia la contraseña de 16 caracteres"
echo ""

read -p "¿Tienes la contraseña de aplicación de Gmail? (y/n): " -n 1 -r
echo ""

if [[ $REPLY =~ ^[Yy]$ ]]; then
    read -p "Pega la contraseña de aplicación aquí: " app_password
    
    # Actualizar el archivo .env
    sed -i '' "s/MAIL_PASSWORD=.*/MAIL_PASSWORD=$app_password/" .env
    sed -i '' "s/MAIL_MAILER=.*/MAIL_MAILER=smtp/" .env
    
    echo "✅ Configuración actualizada"
    
    # Limpiar caché
    echo "🔄 Limpiando caché de configuración..."
    php artisan config:clear
    php artisan config:cache
    
    echo "🧪 Probando envío de email..."
    php artisan test:email-notifications caduxcom.store@gmail.com
    
    echo ""
    echo "✅ ¡Configuración completada!"
    echo "📬 Revisa tu bandeja de entrada en caduxcom.store@gmail.com"
    
else
    echo "📋 Configuración manual:"
    echo "1. Edita tu archivo .env"
    echo "2. Cambia MAIL_PASSWORD por tu contraseña de aplicación"
    echo "3. Ejecuta: php artisan config:clear"
    echo "4. Ejecuta: php artisan test:email-notifications caduxcom.store@gmail.com"
fi

echo ""
echo "📁 Para ver las vistas previas de los emails:"
echo "php artisan email:preview"
echo "Luego abre: storage/app/email-previews/"



