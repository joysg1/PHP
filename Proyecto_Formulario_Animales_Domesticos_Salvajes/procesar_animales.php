<?php
require_once 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" || isset($_GET['action'])) {
    
    // Manejar exportación de reporte
    if (isset($_GET['action']) && $_GET['action'] === 'export') {
        exportarReporte();
        exit;
    }
    
    // Procesar formulario
    procesarFormulario();
} else {
    header("Location: formulario_animales.php");
    exit();
}

function procesarFormulario() {
    $db = new JSONDatabase();
    
    // Validar y sanitizar datos
    $nombre = trim(htmlspecialchars($_POST['nombre']));
    $tipo = $_POST['tipo'];
    $especie = trim(htmlspecialchars($_POST['especie']));
    $edad = intval($_POST['edad']);
    $habitat = trim(htmlspecialchars($_POST['habitat']));
    $caracteristicas = isset($_POST['caracteristicas']) ? $_POST['caracteristicas'] : [];
    
    // Validaciones básicas
    if (empty($nombre) || empty($tipo) || empty($especie)) {
        mostrarError("Todos los campos obligatorios deben ser completados.");
        return;
    }
    
    if ($edad < 0 || $edad > 100) {
        mostrarError("La edad debe estar entre 0 y 100 años.");
        return;
    }
    
    // Preparar datos del animal
    $animal_data = [
        'nombre' => $nombre,
        'tipo' => $tipo,
        'especie' => $especie,
        'edad' => $edad,
        'habitat' => $habitat,
        'caracteristicas' => $caracteristicas
    ];
    
    // Insertar en la base de datos
    if ($db->insertAnimal($animal_data)) {
        mostrarExito($animal_data, $db);
    } else {
        mostrarError("Error al guardar en la base de datos JSON.");
    }
}

function exportarReporte() {
    $db = new JSONDatabase();
    $animales = $db->getAllAnimals();
    
    // Generar archivo de reporte
    $reporte_content = generarReporteAnimales($animales);
    $filename = "reporte_animales_" . date('Y-m-d_H-i-s') . ".txt";
    
    // Guardar archivo temporal
    $temp_file = sys_get_temp_dir() . '/' . $filename;
    file_put_contents($temp_file, $reporte_content);
    
    // Descargar archivo
    header('Content-Type: text/plain');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Content-Length: ' . filesize($temp_file));
    readfile($temp_file);
    
    // Limpiar archivo temporal
    unlink($temp_file);
    exit;
}

function mostrarExito($animal_data, $db) {
    $stats = $db->getAnimalStats();
    $total_animales = $stats['total'];
    
    echo "<!DOCTYPE html>
    <html lang='es'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Registro Exitoso</title>
        <link href='https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap' rel='stylesheet'>
        <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css'>
        <style>
            * { margin: 0; padding: 0; box-sizing: border-box; }
            body { 
                font-family: 'Poppins', sans-serif; 
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                min-height: 100vh; 
                display: flex; 
                align-items: center; 
                justify-content: center;
                padding: 20px;
            }
            .success-container { 
                background: white; 
                border-radius: 20px; 
                padding: 40px; 
                text-align: center; 
                box-shadow: 0 20px 40px rgba(0,0,0,0.1);
                max-width: 600px;
                width: 100%;
            }
            .success-icon { 
                font-size: 4rem; 
                color: #4CAF50;
                margin-bottom: 20px;
            }
            .animal-details {
                background: #f8f9fa;
                border-radius: 15px;
                padding: 20px;
                margin: 20px 0;
                text-align: left;
            }
            .animal-details h3 {
                color: #333;
                margin-bottom: 15px;
                border-bottom: 2px solid #4CAF50;
                padding-bottom: 10px;
            }
            .detail-row {
                display: flex;
                justify-content: space-between;
                margin-bottom: 8px;
                padding: 5px 0;
            }
            .detail-label {
                font-weight: 600;
                color: #555;
            }
            .detail-value {
                color: #333;
            }
            .stats-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
                gap: 15px;
                margin: 20px 0;
            }
            .stat-item {
                background: linear-gradient(135deg, #667eea, #764ba2);
                color: white;
                padding: 15px;
                border-radius: 10px;
                text-align: center;
            }
            .stat-number {
                font-size: 1.5rem;
                font-weight: bold;
            }
            .btn { 
                display: inline-block; 
                background: linear-gradient(135deg, #667eea, #764ba2); 
                color: white; 
                padding: 12px 25px; 
                border-radius: 10px; 
                text-decoration: none; 
                font-weight: 600; 
                margin: 5px;
                transition: all 0.3s ease;
            }
            .btn:hover { 
                transform: translateY(-2px); 
                box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
            }
            .btn-secondary { 
                background: #6c757d; 
            }
            .characteristics {
                display: flex;
                flex-wrap: wrap;
                gap: 8px;
                margin-top: 10px;
            }
            .char-badge {
                background: #4CAF50;
                color: white;
                padding: 4px 12px;
                border-radius: 15px;
                font-size: 0.8rem;
            }
        </style>
    </head>
    <body>
        <div class='success-container'>
            <div class='success-icon'>
                <i class='fas fa-check-circle'></i>
            </div>
            <h1>¡Animal Registrado Exitosamente!</h1>
            <p>El animal ha sido guardado en la base de datos JSON</p>
            
            <div class='animal-details'>
                <h3>📋 Detalles del Animal Registrado</h3>
                <div class='detail-row'>
                    <span class='detail-label'>Nombre:</span>
                    <span class='detail-value'>{$animal_data['nombre']}</span>
                </div>
                <div class='detail-row'>
                    <span class='detail-label'>Tipo:</span>
                    <span class='detail-value'>" . ucfirst($animal_data['tipo']) . "</span>
                </div>
                <div class='detail-row'>
                    <span class='detail-label'>Especie:</span>
                    <span class='detail-value'>{$animal_data['especie']}</span>
                </div>
                <div class='detail-row'>
                    <span class='detail-label'>Edad:</span>
                    <span class='detail-value'>{$animal_data['edad']} años</span>
                </div>
                <div class='detail-row'>
                    <span class='detail-label'>Hábitat:</span>
                    <span class='detail-value'>{$animal_data['habitat']}</span>
                </div>
                <div class='detail-row'>
                    <span class='detail-label'>Características:</span>
                    <span class='detail-value'>
                        <div class='characteristics'>";
    
    if (!empty($animal_data['caracteristicas'])) {
        foreach ($animal_data['caracteristicas'] as $caracteristica) {
            echo "<span class='char-badge'>" . ucfirst($caracteristica) . "</span>";
        }
    } else {
        echo "<span style='color: #666;'>Ninguna característica seleccionada</span>";
    }
    
    echo "              </div>
                    </span>
                </div>
            </div>
            
            <div class='stats-grid'>
                <div class='stat-item'>
                    <div class='stat-number'>{$stats['total']}</div>
                    <div>Total Animales</div>
                </div>
                <div class='stat-item'>
                    <div class='stat-number'>{$stats['domesticos']}</div>
                    <div>Domésticos</div>
                </div>
                <div class='stat-item'>
                    <div class='stat-number'>{$stats['salvajes']}</div>
                    <div>Salvajes</div>
                </div>
                <div class='stat-item'>
                    <div class='stat-number'>" . count($stats['por_caracteristica']) . "</div>
                    <div>Características</div>
                </div>
            </div>
            
            <div>
                <a href='formulario_animales.php' class='btn'>
                    <i class='fas fa-plus'></i> Registrar Otro Animal
                </a>
                <a href='ver_animales.php' class='btn btn-secondary'>
                    <i class='fas fa-database'></i> Ver Base de Datos
                </a>
                <a href='procesar_animales.php?action=export' class='btn'>
                    <i class='fas fa-download'></i> Exportar Reporte
                </a>
            </div>
        </div>
    </body>
    </html>";
}

function mostrarError($mensaje) {
    echo "<!DOCTYPE html>
    <html lang='es'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Error en Registro</title>
        <link href='https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap' rel='stylesheet'>
        <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css'>
        <style>
            * { margin: 0; padding: 0; box-sizing: border-box; }
            body { 
                font-family: 'Poppins', sans-serif; 
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                min-height: 100vh; 
                display: flex; 
                align-items: center; 
                justify-content: center;
                padding: 20px;
            }
            .error-container { 
                background: white; 
                border-radius: 20px; 
                padding: 40px; 
                text-align: center; 
                box-shadow: 0 20px 40px rgba(0,0,0,0.1);
                max-width: 500px;
                width: 100%;
            }
            .error-icon { 
                font-size: 4rem; 
                color: #f44336;
                margin-bottom: 20px;
            }
            .btn { 
                display: inline-block; 
                background: linear-gradient(135deg, #667eea, #764ba2); 
                color: white; 
                padding: 12px 25px; 
                border-radius: 10px; 
                text-decoration: none; 
                font-weight: 600; 
                margin-top: 20px;
            }
        </style>
    </head>
    <body>
        <div class='error-container'>
            <div class='error-icon'>
                <i class='fas fa-exclamation-circle'></i>
            </div>
            <h1>Error en el Registro</h1>
            <p>{$mensaje}</p>
            <a href='formulario_animales.php' class='btn'>Volver al Formulario</a>
        </div>
    </body>
    </html>";
}
?>