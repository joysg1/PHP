#!/bin/bash
echo "🌍 Configurando Sistema de Continentes..."

# Crear directorios
mkdir -p php python data data/graficos assets/{css,js,img}

# Instalar dependencias Python
echo "📦 Instalando dependencias Python..."
pip3 install -r python/requirements.txt

# Dar permisos
chmod +x python/database_continentes.py
chmod +x python/graficos_continentes.py

echo "✅ Configuración completada!"
echo "🚀 Para ejecutar: php -S localhost:8000 -t php/"
