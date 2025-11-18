<?php
// generate_images.php - Script para generar imágenes manualmente

// Incluir la configuración
require_once 'config.php';

function runImageGeneration() {
    echo "<!DOCTYPE html>
    <html>
    <head>
        <title>Generador de Imágenes - PlantCompare</title>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <style>
            * { margin: 0; padding: 0; box-sizing: border-box; }
            body { 
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
                margin: 0; 
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                min-height: 100vh;
                padding: 20px;
            }
            .container { 
                max-width: 900px; 
                margin: 0 auto; 
                background: white; 
                padding: 40px; 
                border-radius: 20px; 
                box-shadow: 0 20px 40px rgba(0,0,0,0.1);
                margin-top: 40px;
                margin-bottom: 40px;
            }
            .header { 
                text-align: center; 
                margin-bottom: 40px;
                background: linear-gradient(135deg, #10b981, #059669);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
            }
            .header h1 { 
                font-size: 2.5rem; 
                margin-bottom: 10px;
            }
            .header p { 
                font-size: 1.1rem; 
                color: #6b7280;
            }
            .success { 
                color: #10b981; 
                padding: 20px; 
                background: #f0fdf4; 
                border: 1px solid #10b981; 
                border-radius: 10px; 
                margin: 20px 0;
                border-left: 5px solid #10b981;
            }
            .error { 
                color: #ef4444; 
                padding: 20px; 
                background: #fef2f2; 
                border: 1px solid #ef4444; 
                border-radius: 10px; 
                margin: 20px 0;
                border-left: 5px solid #ef4444;
            }
            .info { 
                color: #6366f1; 
                padding: 20px; 
                background: #f0f9ff; 
                border: 1px solid #6366f1; 
                border-radius: 10px; 
                margin: 20px 0;
                border-left: 5px solid #6366f1;
            }
            .warning { 
                color: #f59e0b; 
                padding: 20px; 
                background: #fffbeb; 
                border: 1px solid #f59e0b; 
                border-radius: 10px; 
                margin: 20px 0;
                border-left: 5px solid #f59e0b;
            }
            .log { 
                background: #1f2937; 
                color: #e5e7eb; 
                padding: 20px; 
                border-radius: 10px; 
                margin-top: 20px; 
                font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace; 
                white-space: pre-wrap;
                font-size: 0.9rem;
                max-height: 400px;
                overflow-y: auto;
            }
            .btn { 
                background: linear-gradient(135deg, #10b981, #059669);
                color: white; 
                padding: 15px 30px; 
                border: none; 
                border-radius: 10px; 
                cursor: pointer; 
                text-decoration: none; 
                display: inline-block; 
                margin-top: 20px;
                font-size: 1rem;
                font-weight: 600;
                transition: transform 0.2s, box-shadow 0.2s;
            }
            .btn:hover {
                transform: translateY(-2px);
                box-shadow: 0 10px 20px rgba(16, 185, 129, 0.3);
            }
            .step { 
                display: flex; 
                align-items: center; 
                margin: 15px 0; 
                padding: 15px;
                background: #f8fafc;
                border-radius: 10px;
            }
            .step-icon { 
                width: 40px; 
                height: 40px; 
                background: #10b981; 
                color: white; 
                border-radius: 50%; 
                display: flex; 
                align-items: center; 
                justify-content: center; 
                margin-right: 15px;
                font-weight: bold;
            }
            .plant-list {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
                gap: 15px;
                margin: 20px 0;
            }
            .plant-item {
                padding: 15px;
                background: #f8fafc;
                border-radius: 10px;
                border-left: 4px solid #10b981;
            }
            .status-badge {
                display: inline-block;
                padding: 4px 12px;
                border-radius: 20px;
                font-size: 0.8rem;
                font-weight: 600;
                margin-left: 10px;
            }
            .status-success { background: #d1fae5; color: #065f46; }
            .status-error { background: #fee2e2; color: #991b1b; }
            .status-warning { background: #fef3c7; color: #92400e; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h1>🌿 Generador de Imágenes</h1>
                <p>PlantCompare - Sistema de Comparación de Plantas</p>
            </div>";
    
    // Paso 1: Verificar Python
    echo "<div class='step'>
            <div class='step-icon'>1</div>
            <div>
                <h3>Verificando Python...</h3>
                <p>Comprobando que Python esté instalado y disponible</p>
            </div>
          </div>";
    
    $pythonCheck = shell_exec('python3 --version 2>&1');
    if (empty($pythonCheck)) {
        $pythonCheck = shell_exec('python --version 2>&1');
    }
    
    if (strpos($pythonCheck, 'Python') === false) {
        echo "<div class='error'>
                <h3>❌ Python no encontrado</h3>
                <p>Python no está instalado o no está en el PATH del sistema.</p>
                <div class='warning'>
                    <h4>📋 Cómo instalar Python:</h4>
                    <p><strong>Linux (Ubuntu/Debian):</strong> <code>sudo apt update && sudo apt install python3 python3-pip</code></p>
                    <p><strong>Linux (CentOS/RHEL):</strong> <code>sudo yum install python3 python3-pip</code></p>
                    <p><strong>macOS:</strong> <code>brew install python</code></p>
                    <p><strong>Windows:</strong> Descargar desde <a href='https://python.org' target='_blank'>python.org</a></p>
                </div>
            </div>";
        echo "</div></body></html>";
        return;
    }
    
    echo "<div class='success'>
            <h3>✅ Python encontrado</h3>
            <p><strong>Versión:</strong> " . htmlspecialchars(trim($pythonCheck)) . "</p>
          </div>";
    
    // Paso 2: Verificar datos de plantas
    echo "<div class='step'>
            <div class='step-icon'>2</div>
            <div>
                <h3>Cargando datos de plantas...</h3>
                <p>Leyendo información del archivo JSON</p>
            </div>
          </div>";
    
    if (!file_exists(DATA_FILE)) {
        echo "<div class='error'>
                <h3>❌ Archivo de datos no encontrado</h3>
                <p>No se pudo encontrar: <code>" . DATA_FILE . "</code></p>
                <p>Se creará un archivo de ejemplo automáticamente.</p>
            </div>";
        // El archivo se creará automáticamente por initializeApplication()
    }
    
    $plantsData = file_get_contents(DATA_FILE);
    $plants = json_decode($plantsData, true);
    
    if (!$plants || !is_array($plants)) {
        echo "<div class='error'>
                <h3>❌ Error en el archivo de datos</h3>
                <p>El archivo JSON de plantas está vacío o tiene formato incorrecto.</p>
            </div>";
        echo "</div></body></html>";
        return;
    }
    
    echo "<div class='success'>
            <h3>✅ Datos cargados correctamente</h3>
            <p><strong>Plantas encontradas:</strong> " . count($plants) . "</p>
            <div class='plant-list'>";
    
    foreach ($plants as $plant) {
        echo "<div class='plant-item'>
                <strong>{$plant['name']}</strong> 
                <span class='status-badge status-success'>ID: {$plant['id']}</span>
              </div>";
    }
    
    echo "</div></div>";
    
    // Paso 3: Generar imágenes
    echo "<div class='step'>
            <div class='step-icon'>3</div>
            <div>
                <h3>Generando imágenes...</h3>
                <p>Ejecutando script de Python para crear imágenes</p>
            </div>
          </div>";
    
    // Crear directorio de imágenes si no existe
    if (!is_dir(PLANT_IMAGES_DIR)) {
        mkdir(PLANT_IMAGES_DIR, 0777, true);
        echo "<div class='info'><p>📁 Directorio creado: <code>" . PLANT_IMAGES_DIR . "</code></p></div>";
    }
    
    // Generar archivo temporal con datos de plantas
    $tempFile = tempnam(sys_get_temp_dir(), 'plants_');
    file_put_contents($tempFile, json_encode($plants, JSON_UNESCAPED_UNICODE));
    
    // Ejecutar Python
    $pythonScript = realpath(PYTHON_IMAGE_SCRIPT);
    if (!$pythonScript || !file_exists($pythonScript)) {
        echo "<div class='error'>
                <h3>❌ Script de Python no encontrado</h3>
                <p>No se pudo encontrar: <code>" . PYTHON_IMAGE_SCRIPT . "</code></p>
                <p>Asegúrate de que el archivo exista en la ubicación correcta.</p>
            </div>";
        unlink($tempFile);
        echo "</div></body></html>";
        return;
    }
    
    $pythonScriptEscaped = escapeshellarg($pythonScript);
    $tempFileEscaped = escapeshellarg($tempFile);
    $outputDir = escapeshellarg(PLANT_IMAGES_DIR);
    
    $command = "python {$pythonScriptEscaped} {$tempFileEscaped} {$outputDir} 2>&1";
    $output = shell_exec($command);
    
    // Verificar si las imágenes se generaron
    $imagesGenerated = [];
    $imagesFailed = [];
    
    foreach ($plants as $plant) {
        $expectedImage = PLANT_IMAGES_DIR . "plant_{$plant['id']}.png";
        if (file_exists($expectedImage)) {
            $imagesGenerated[] = $plant;
        } else {
            $imagesFailed[] = $plant;
        }
    }
    
    // Limpiar archivo temporal
    unlink($tempFile);
    
    echo "<div class='log'><strong>Salida del script Python:</strong>\n" . htmlspecialchars($output ?: "No hubo salida o no se pudo ejecutar") . "</div>";
    
    // Resultado final
    if (count($imagesGenerated) === count($plants)) {
        echo "<div class='success'>
                <h3>🎉 ¡Éxito completo!</h3>
                <p><strong>Todas las imágenes se generaron correctamente:</strong> " . count($imagesGenerated) . "/" . count($plants) . "</p>
                <div class='plant-list'>";
        
        foreach ($imagesGenerated as $plant) {
            echo "<div class='plant-item'>
                    <strong>{$plant['name']}</strong> 
                    <span class='status-badge status-success'>✅ Generada</span>
                  </div>";
        }
        
        echo "</div>
                <p>Las imágenes se guardaron en: <code>" . PLANT_IMAGES_DIR . "</code></p>
            </div>
            <div style='text-align: center;'>
                <a href='index.php' class='btn'>🚀 Ir al Comparador de Plantas</a>
            </div>";
    } else {
        echo "<div class='" . (count($imagesGenerated) > 0 ? 'warning' : 'error') . "'>
                <h3>" . (count($imagesGenerated) > 0 ? '⚠️ Resultado parcial' : '❌ Error en la generación') . "</h3>
                <p><strong>Imágenes generadas:</strong> " . count($imagesGenerated) . "/" . count($plants) . "</p>";
        
        if (count($imagesGenerated) > 0) {
            echo "<p><strong>✅ Imágenes exitosas:</strong></p>
                  <div class='plant-list'>";
            foreach ($imagesGenerated as $plant) {
                echo "<div class='plant-item'>
                        <strong>{$plant['name']}</strong>
                        <span class='status-badge status-success'>✅ Lista</span>
                      </div>";
            }
            echo "</div>";
        }
        
        if (count($imagesFailed) > 0) {
            echo "<p><strong>❌ Imágenes fallidas:</strong></p>
                  <div class='plant-list'>";
            foreach ($imagesFailed as $plant) {
                echo "<div class='plant-item'>
                        <strong>{$plant['name']}</strong>
                        <span class='status-badge status-error'>❌ Error</span>
                      </div>";
            }
            echo "</div>";
        }
        
        echo "<div class='info'>
                <h4>🔧 Solución de problemas:</h4>
                <p>1. <strong>Instalar dependencias de Python:</strong> <code>pip install matplotlib numpy Pillow</code></p>
                <p>2. <strong>Verificar permisos:</strong> Asegúrate de que PHP tenga permisos para escribir en el directorio de imágenes</p>
                <p>3. <strong>Revisar la salida de Python</strong> arriba para más detalles del error</p>
                <p>Las plantas sin imagen usarán una imagen por defecto.</p>
            </div>
            </div>
            <div style='text-align: center;'>
                <a href='index.php' class='btn'>🚀 Ir al Comparador de Plantas</a>
            </div>";
    }
    
    echo "</div></body></html>";
}

// Ejecutar la generación
runImageGeneration();
?>