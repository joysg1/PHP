<?php
// ==================== CONFIGURACIÓN ====================
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// ==================== COMUNICACIÓN CON PYTHON ====================
function ejecutarPython($comando, $datos = []) {
    $comando_data = json_encode([
        'comando' => $comando,
        'datos' => $datos
    ]);
    
    $descriptors = [
        0 => ['pipe', 'r'],
        1 => ['pipe', 'w'],
        2 => ['pipe', 'w']
    ];
    
    // Determinar qué script Python usar
    if ($comando === 'generar_todos' || strpos($comando, 'grafico_') === 0) {
        $python_script_path = __DIR__ . '/../python/graficos_continentes.py';
    } else {
        $python_script_path = __DIR__ . '/../python/database_continentes.py';
    }
    
    // Verificar que el archivo Python existe
    if (!file_exists($python_script_path)) {
        return ['error' => "Archivo Python no encontrado: $python_script_path"];
    }
    
    $python_commands = ['python3', 'python'];
    $process = null;
    $last_error = '';
    
    foreach ($python_commands as $cmd) {
        $full_command = "$cmd \"$python_script_path\"";
        $process = proc_open($full_command, $descriptors, $pipes);
        if (is_resource($process)) {
            break;
        }
        $last_error = "No se pudo ejecutar: $full_command";
    }
    
    if (!is_resource($process)) {
        return ['error' => "No se pudo ejecutar Python: $last_error"];
    }
    
    fwrite($pipes[0], $comando_data);
    fclose($pipes[0]);
    
    $output = stream_get_contents($pipes[1]);
    fclose($pipes[1]);
    
    $error = stream_get_contents($pipes[2]);
    fclose($pipes[2]);
    
    $return_code = proc_close($process);
    
    if ($return_code === 0) {
        $resultado = json_decode($output, true);
        if (json_last_error() === JSON_ERROR_NONE) {
            return $resultado;
        } else {
            return ['error' => 'Error decodificando JSON de Python: ' . json_last_error_msg() . ' - Output: ' . $output];
        }
    } else {
        return ['error' => "Error en ejecución Python (Code: $return_code): $error"];
    }
}

// ==================== FUNCIONES DE PRESENTACIÓN ====================
function formatearNumero($numero) {
    return number_format($numero);
}

function formatearArea($area_km2) {
    return number_format($area_km2) . ' km²';
}

function formatearDensidad($densidad) {
    return number_format($densidad, 2) . ' hab/km²';
}

function calcularDensidad($poblacion, $area) {
    return $area > 0 ? $poblacion / $area : 0;
}

// ==================== OPERACIONES PRINCIPALES ====================
function ejecutarOperacion($operacion, $parametro = null) {
    $resultado = '';
    
    switch($operacion) {
        case 'mostrar_todos':
            $continentes = ejecutarPython('get_continentes');
            if (isset($continentes['error'])) {
                return "<div class='error'>❌ Error: {$continentes['error']}</div>";
            }
            
            $resultado = "<h4>🌍 Todos los Continentes:</h4>";
            $resultado .= "<div class='continentes-grid'>";
            foreach($continentes as $clave => $datos) {
                $densidad = calcularDensidad($datos['poblacion'], $datos['area_km2']);
                $resultado .= "
                    <div class='continente-card'>
                        <h3>{$datos['nombre_completo']}</h3>
                        <div class='continente-stats'>
                            <div>👥 " . formatearNumero($datos['poblacion']) . " hab.</div>
                            <div>🗺️ " . formatearArea($datos['area_km2']) . "</div>
                            <div>🏛️ {$datos['paises_count']} países</div>
                            <div>📊 " . formatearDensidad($densidad) . "</div>
                        </div>
                        <div class='curiosidad'>{$datos['curiosidades'][0]}</div>
                    </div>
                ";
            }
            $resultado .= "</div>";
            break;
            
        case 'info_detallada':
            if (!$parametro) {
                return "<div class='error'>❌ Selecciona un continente</div>";
            }
            
            $continente = ejecutarPython('get_continente', ['nombre' => $parametro]);
            if (isset($continente['error']) || empty($continente)) {
                return "<div class='error'>❌ Continente no encontrado</div>";
            }
            
            $densidad = calcularDensidad($continente['poblacion'], $continente['area_km2']);
            
            $resultado = "<div class='continente-detalle'>";
            $resultado .= "<h3>🌍 {$continente['nombre_completo']}</h3>";
            
            $resultado .= "<div class='stats-grid'>";
            $resultado .= "<div class='stat'><strong>👥 Población:</strong> " . formatearNumero($continente['poblacion']) . "</div>";
            $resultado .= "<div class='stat'><strong>🗺️ Área:</strong> " . formatearArea($continente['area_km2']) . "</div>";
            $resultado .= "<div class='stat'><strong>🏛️ Países:</strong> {$continente['paises_count']}</div>";
            $resultado .= "<div class='stat'><strong>📊 Densidad:</strong> " . formatearDensidad($densidad) . "</div>";
            $resultado .= "<div class='stat'><strong>🗣️ Idiomas:</strong> " . implode(', ', $continente['idiomas_principales']) . "</div>";
            $resultado .= "<div class='stat'><strong>⛰️ Punto más alto:</strong> {$continente['punto_mas_alto']['nombre']} ({$continente['punto_mas_alto']['altura_m']} m)</div>";
            $resultado .= "<div class='stat'><strong>🌡️ Clima:</strong> {$continente['clima']}</div>";
            $resultado .= "<div class='stat'><strong>💰 Moneda común:</strong> " . ($continente['moneda_comun'] ? 'Sí' : 'No') . "</div>";
            $resultado .= "</div>";
            
            // Países principales
            $resultado .= "<h4>🇺🇳 Países Principales:</h4>";
            $resultado .= "<div class='paises-grid'>";
            foreach(array_slice($continente['paises'], 0, 5) as $pais) {
                $resultado .= "
                    <div class='pais-card'>
                        <strong>{$pais['nombre']}</strong>
                        <div>👥 " . formatearNumero($pais['poblacion']) . "</div>
                        <div>🏛️ {$pais['capital']}</div>
                    </div>
                ";
            }
            $resultado .= "</div>";
            
            // Curiosidades
            $resultado .= "<h4>💡 Curiosidades:</h4>";
            $resultado .= "<ul>";
            foreach($continente['curiosidades'] as $curiosidad) {
                $resultado .= "<li>{$curiosidad}</li>";
            }
            $resultado .= "</ul>";
            
            $resultado .= "</div>";
            break;
            
        case 'estadisticas_globales':
            $estadisticas = ejecutarPython('get_estadisticas_globales');
            if (isset($estadisticas['error'])) {
                return "<div class='error'>❌ Error: {$estadisticas['error']}</div>";
            }
            
            $resultado = "<h4>📈 Estadísticas Mundiales:</h4>";
            $resultado .= "<div class='stats-grid global-stats'>";
            $resultado .= "<div class='stat-card'><div class='stat-number'>" . formatearNumero($estadisticas['poblacion_total']) . "</div><div class='stat-label'>👥 Población Total</div></div>";
            $resultado .= "<div class='stat-card'><div class='stat-number'>" . formatearArea($estadisticas['area_total']) . "</div><div class='stat-label'>🗺️ Área Total</div></div>";
            $resultado .= "<div class='stat-card'><div class='stat-number'>{$estadisticas['total_continentes']}</div><div class='stat-label'>🌍 Continentes</div></div>";
            $resultado .= "<div class='stat-card'><div class='stat-number'>{$estadisticas['total_paises']}</div><div class='stat-label'>🏛️ Países</div></div>";
            $resultado .= "<div class='stat-card'><div class='stat-number'>" . formatearDensidad($estadisticas['densidad_promedio']) . "</div><div class='stat-label'>📊 Densidad Promedio</div></div>";
            $resultado .= "</div>";
            
            $resultado .= "<div class='ranking-section'>";
            $resultado .= "<h4>🏆 Rankings:</h4>";
            $resultado .= "<div class='ranking-grid'>";
            $resultado .= "<div class='ranking-item'>🥇 <strong>Más poblado:</strong> {$estadisticas['continente_mas_poblado']['nombre']} (" . formatearNumero($estadisticas['continente_mas_poblado']['datos']['poblacion']) . " hab.)</div>";
            $resultado .= "<div class='ranking-item'>🥈 <strong>Más grande:</strong> {$estadisticas['continente_mas_grande']['nombre']} (" . formatearArea($estadisticas['continente_mas_grande']['datos']['area_km2']) . ")</div>";
            $resultado .= "<div class='ranking-item'>🥉 <strong>Más denso:</strong> {$estadisticas['continente_mas_denso']}</div>";
            $resultado .= "<div class='ranking-item'>📉 <strong>Menos poblado:</strong> {$estadisticas['continente_menos_poblado']}</div>";
            $resultado .= "</div>";
            $resultado .= "</div>";
            break;
            
        case 'buscar_pais':
            $pais_buscado = $parametro ?: 'Brasil';
            
            $resultado_busqueda = ejecutarPython('buscar_pais', ['nombre' => $pais_buscado]);
            if (isset($resultado_busqueda['error']) || empty($resultado_busqueda)) {
                return "<div class='error'>❌ País '{$pais_buscado}' no encontrado</div>";
            }
            
            $pais = $resultado_busqueda['pais'];
            $continente = $resultado_busqueda['continente'];
            $datos_continente = $resultado_busqueda['datos_continente'];
            
            $resultado = "<div class='pais-encontrado'>";
            $resultado .= "<h3>📍 {$pais['nombre']}</h3>";
            $resultado .= "<div class='pais-info'>";
            $resultado .= "<p><strong>🌍 Continente:</strong> {$datos_continente['nombre_completo']}</p>";
            $resultado .= "<p><strong>👥 Población:</strong> " . formatearNumero($pais['poblacion']) . " habitantes</p>";
            $resultado .= "<p><strong>🏛️ Capital:</strong> {$pais['capital']}</p>";
            $resultado .= "<p><strong>🗣️ Idiomas principales:</strong> " . implode(', ', $datos_continente['idiomas_principales']) . "</p>";
            $resultado .= "<p><strong>🌡️ Clima típico:</strong> {$datos_continente['clima']}</p>";
            $resultado .= "</div>";
            $resultado .= "</div>";
            break;
            
        case 'comparar_continentes':
            $continentes = ejecutarPython('get_continentes');
            if (isset($continentes['error'])) {
                return "<div class='error'>❌ Error: {$continentes['error']}</div>";
            }
            
            $resultado = "<h4>📊 Comparativa de Continentes:</h4>";
            $resultado .= "<div class='comparison-table'>";
            $resultado .= "<table>";
            $resultado .= "<thead><tr><th>Continente</th><th>👥 Población</th><th>🗺️ Área</th><th>📊 Densidad</th><th>🏛️ Países</th></tr></thead>";
            $resultado .= "<tbody>";
            foreach($continentes as $clave => $datos) {
                $densidad = calcularDensidad($datos['poblacion'], $datos['area_km2']);
                $resultado .= "<tr>";
                $resultado .= "<td><strong>{$datos['nombre_completo']}</strong></td>";
                $resultado .= "<td>" . formatearNumero($datos['poblacion']) . "</td>";
                $resultado .= "<td>" . formatearArea($datos['area_km2']) . "</td>";
                $resultado .= "<td>" . formatearDensidad($densidad) . "</td>";
                $resultado .= "<td>{$datos['paises_count']}</td>";
                $resultado .= "</tr>";
            }
            $resultado .= "</tbody>";
            $resultado .= "</table>";
            $resultado .= "</div>";
            break;
            
        case 'generar_graficos':
            // Generar gráficos en tiempo real
            $graficos = ejecutarPython('generar_todos');
            
            if (isset($graficos['error'])) {
                $resultado = "<div class='error'>❌ Error generando gráficos: {$graficos['error']}</div>";
                
                // Información de debug adicional
                $resultado .= "<div class='debug-info' style='background: #f8f9fa; padding: 15px; border-radius: 5px; margin-top: 10px;'>";
                $resultado .= "<h5>🔧 Información de Debug:</h5>";
                $resultado .= "<p><strong>Archivo de gráficos:</strong> " . (file_exists(__DIR__ . '/../python/graficos_continentes.py') ? '✅ Existe' : '❌ No existe') . "</p>";
                $resultado .= "<p><strong>Archivo de datos:</strong> " . (file_exists(__DIR__ . '/../python/continentes_data.json') ? '✅ Existe' : '❌ No existe') . "</p>";
                $resultado .= "<p><strong>Error detallado:</strong> {$graficos['error']}</p>";
                $resultado .= "</div>";
            } else {
                $resultado = "<h4>📈 Gráficos Generados en Tiempo Real</h4>";
                
                $nombres_graficos = [
                    'poblacion' => '🌍 Población por Continente',
                    'area' => '🗺️ Área por Continente', 
                    'densidad' => '📊 Densidad Poblacional',
                    'comparativo' => '📈 Comparativa Completa',
                    'paises_top' => '🏆 Top 10 Países Más Poblados'
                ];
                
                $graficos_generados = 0;
                foreach($nombres_graficos as $clave => $nombre) {
                    if (isset($graficos[$clave]) && !empty($graficos[$clave])) {
                        $resultado .= "
                            <div class='chart-container'>
                                <h5>{$nombre}</h5>
                                <img src='data:image/png;base64,{$graficos[$clave]}' 
                                     alt='{$nombre}' 
                                     class='chart-image'
                                     style='max-width: 100%; height: auto; border: 1px solid #ddd; border-radius: 8px;'>
                                <div style='margin-top: 10px; text-align: center;'>
                                    <small>Gráfico generado con Python + Matplotlib + Seaborn</small>
                                </div>
                            </div>
                        ";
                        $graficos_generados++;
                    } else {
                        $resultado .= "<div class='error' style='margin: 10px 0;'>Gráfico '{$nombre}' no disponible</div>";
                    }
                }
                
                if ($graficos_generados === 0) {
                    $resultado .= "<div class='error'>No se pudieron generar los gráficos. Verifica que las librerías de Python estén instaladas.</div>";
                } else {
                    $resultado .= "<div class='success' style='margin-top: 20px;'>✅ Se generaron {$graficos_generados} gráficos exitosamente</div>";
                }
            }
            break;
            
        default:
            $resultado = "<div class='error'>❌ Operación no reconocida</div>";
    }
    
    return $resultado;
}

// ==================== PROCESAMIENTO PRINCIPAL ====================
$resultado = '';
$operacion_seleccionada = '';
$continente_seleccionado = '';
$pais_buscado = '';
$python_funciona = true;
$python_detalle = '';

// Verificar Python y rutas
$python_script_path = __DIR__ . '/../python/database_continentes.py';
$python_graficos_path = __DIR__ . '/../python/graficos_continentes.py';

if (!file_exists($python_script_path)) {
    $python_funciona = false;
    $python_detalle = "Archivo Python no encontrado: $python_script_path";
} else {
    $test_python = ejecutarPython('get_continentes');
    if (isset($test_python['error'])) {
        $python_funciona = false;
        $python_detalle = $test_python['error'];
        $resultado = "<div class='error'>❌ Error de Python: {$test_python['error']}</div>";
    } else {
        $python_detalle = "✅ Conectado - " . count($test_python) . " continentes cargados";
    }
}

if ($_POST && $python_funciona) {
    $operacion_seleccionada = $_POST['operacion'] ?? '';
    $continente_seleccionado = $_POST['continente'] ?? '';
    $pais_buscado = $_POST['pais'] ?? '';
    
    if (!empty($operacion_seleccionada)) {
        $parametro = '';
        if ($operacion_seleccionada === 'info_detallada') {
            $parametro = $continente_seleccionado;
        } elseif ($operacion_seleccionada === 'buscar_pais') {
            $parametro = $pais_buscado;
        }
        
        $resultado = ejecutarOperacion($operacion_seleccionada, $parametro);
    }
}

// Obtener lista de continentes para el select
$continentes_lista = [];
if ($python_funciona) {
    $continentes_data = ejecutarPython('get_continentes');
    if (!isset($continentes_data['error'])) {
        $continentes_lista = $continentes_data;
    }
}

// Verificar archivos de datos
$archivo_continentes = file_exists(__DIR__ . '/../python/continentes_data.json') ? 
    filesize(__DIR__ . '/../python/continentes_data.json') : 0;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🌍 Sistema de Continentes - PHP + Python</title>
    <style>
        /* [Todo el CSS se mantiene igual] */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
            color: #333;
        }
        .container {
            max-width: 1400px;
            margin: 0 auto;
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }
        .header {
            background: #2c3e50;
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin-bottom: 10px;
            font-size: 2.5em;
        }
        .content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0;
            min-height: 600px;
        }
        .form-section {
            padding: 30px;
            background: #f8f9fa;
        }
        .result-section {
            padding: 30px;
            background: white;
            overflow-y: auto;
        }
        .info-section {
            grid-column: 1 / -1;
            padding: 30px;
            background: #f1f2f6;
            border-top: 2px solid #ddd;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #2c3e50;
        }
        select, button, input {
            width: 100%;
            padding: 12px;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            font-size: 16px;
            transition: all 0.3s;
        }
        select:focus, input:focus {
            outline: none;
            border-color: #667eea;
        }
        button {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            border: none;
            font-size: 18px;
            font-weight: 600;
            cursor: pointer;
            margin-top: 10px;
        }
        button:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        button:disabled {
            background: #6c757d;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }
        .result-box {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            border-left: 5px solid #667eea;
            margin-top: 20px;
            max-height: 500px;
            overflow-y: auto;
        }
        .error {
            background: #ffeaa7;
            color: #d63031;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
            margin: 10px 0;
            border-left: 4px solid #e74c3c;
        }
        .success {
            background: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 8px;
            margin: 10px 0;
            border-left: 4px solid #28a745;
        }
        
        .continentes-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin: 20px 0;
        }
        .continente-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            border-left: 5px solid #3498db;
            transition: transform 0.3s;
        }
        .continente-card:hover {
            transform: translateY(-5px);
        }
        .continente-card h3 {
            color: #2c3e50;
            margin-bottom: 15px;
            border-bottom: 2px solid #ecf0f1;
            padding-bottom: 10px;
        }
        .continente-stats {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            margin-bottom: 15px;
        }
        .continente-stats div {
            background: #f8f9fa;
            padding: 8px;
            border-radius: 5px;
            font-size: 14px;
        }
        .curiosidad {
            background: #e8f4fd;
            padding: 10px;
            border-radius: 5px;
            font-style: italic;
            font-size: 14px;
        }
        
        .continente-detalle {
            background: white;
            padding: 20px;
            border-radius: 10px;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin: 20px 0;
        }
        .stat {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            border-left: 4px solid #3498db;
        }
        .paises-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 15px;
            margin: 15px 0;
        }
        .pais-card {
            background: white;
            padding: 15px;
            border-radius: 8px;
            border: 1px solid #e9ecef;
            text-align: center;
        }
        
        .global-stats {
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        }
        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .stat-number {
            font-size: 24px;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 5px;
        }
        .stat-label {
            font-size: 14px;
            color: #666;
        }
        .ranking-section {
            margin-top: 30px;
        }
        .ranking-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 15px;
            margin-top: 15px;
        }
        .ranking-item {
            background: white;
            padding: 15px;
            border-radius: 8px;
            border-left: 4px solid #f39c12;
        }
        
        .comparison-table {
            overflow-x: auto;
            margin: 20px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background: #34495e;
            color: white;
        }
        tr:hover {
            background: #f5f6fa;
        }
        
        .chart-container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            margin: 20px 0;
            text-align: center;
            border: 1px solid #e9ecef;
        }
        .chart-image {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        
        .operations-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 15px;
            margin-top: 15px;
        }
        .operation-card {
            background: white;
            padding: 15px;
            border-radius: 8px;
            border-left: 4px solid #27ae60;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            cursor: pointer;
            transition: all 0.3s;
        }
        .operation-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        .operation-card h4 {
            margin-bottom: 8px;
            color: #2c3e50;
        }
        .operation-card p {
            font-size: 14px;
            color: #666;
            margin: 0;
        }
        
        .info-box {
            background: #e8f4fd;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        
        .system-status {
            padding: 10px;
            border-radius: 5px;
            margin: 10px 0;
            font-size: 14px;
        }
        .status-ok {
            background: #d4edda;
            color: #155724;
            border-left: 4px solid #28a745;
        }
        .status-error {
            background: #f8d7da;
            color: #721c24;
            border-left: 4px solid #dc3545;
        }
        .status-warning {
            background: #fff3cd;
            color: #856404;
            border-left: 4px solid #ffc107;
        }
        
        .debug-info {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin: 10px 0;
            border-left: 4px solid #17a2b8;
        }
        
        @media (max-width: 768px) {
            .content {
                grid-template-columns: 1fr;
            }
            .continentes-grid {
                grid-template-columns: 1fr;
            }
            .header h1 {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🌍 Sistema de Continentes - PHP + Python</h1>
            <p>PHP + Python + Datos Geográficos Reales</p>
        </div>
        
        <div class="content">
            <div class="form-section">
                <h2>🧭 Operaciones Geográficas</h2>
                
                <!-- Estado del Sistema -->
                <div class="system-status <?php echo $python_funciona ? 'status-ok' : 'status-error'; ?>">
                    <strong>Estado del Sistema:</strong> <?php echo $python_detalle; ?>
                    <?php if ($archivo_continentes > 0): ?>
                        <br><small>Archivo de datos: <?php echo number_format($archivo_continentes); ?> bytes</small>
                    <?php endif; ?>
                    <?php if (file_exists(__DIR__ . '/../python/graficos_continentes.py')): ?>
                        <br><small>✅ Módulo de gráficos disponible</small>
                    <?php else: ?>
                        <br><small>❌ Módulo de gráficos no encontrado</small>
                    <?php endif; ?>
                </div>
                
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="operacion">Selecciona una Operación:</label>
                        <select id="operacion" name="operacion" required onchange="toggleContinenteSelect()">
                            <option value="">-- Elige una operación --</option>
                            <option value="mostrar_todos" <?php echo $operacion_seleccionada == 'mostrar_todos' ? 'selected' : ''; ?>>🌍 Mostrar todos los continentes</option>
                            <option value="info_detallada" <?php echo $operacion_seleccionada == 'info_detallada' ? 'selected' : ''; ?>>🔍 Información detallada</option>
                            <option value="estadisticas_globales" <?php echo $operacion_seleccionada == 'estadisticas_globales' ? 'selected' : ''; ?>>📈 Estadísticas globales</div>
                            <option value="buscar_pais" <?php echo $operacion_seleccionada == 'buscar_pais' ? 'selected' : ''; ?>>📍 Buscar país</option>
                            <option value="comparar_continentes" <?php echo $operacion_seleccionada == 'comparar_continentes' ? 'selected' : ''; ?>>📊 Comparar continentes</option>
                            <option value="generar_graficos" <?php echo $operacion_seleccionada == 'generar_graficos' ? 'selected' : ''; ?>>📈 Generar gráficos</option>
                        </select>
                    </div>
                    
                    <div class="form-group" id="continente-group" style="display: none;">
                        <label for="continente">Selecciona un Continente:</label>
                        <select id="continente" name="continente">
                            <option value="">-- Elige un continente --</option>
                            <?php foreach($continentes_lista as $clave => $datos): ?>
                                <option value="<?php echo $clave; ?>" <?php echo $continente_seleccionado == $clave ? 'selected' : ''; ?>>
                                    <?php echo $datos['nombre_completo']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="form-group" id="pais-group" style="display: none;">
                        <label for="pais">Nombre del País:</label>
                        <input type="text" id="pais" name="pais" placeholder="Ej: Brasil, Argentina, España..." value="<?php echo htmlspecialchars($pais_buscado); ?>">
                    </div>
                    
                    <button type="submit" <?php echo !$python_funciona ? 'disabled' : ''; ?>>
                        <?php echo $python_funciona ? '🚀 Ejecutar Operación' : '❌ Python No Disponible'; ?>
                    </button>
                </form>
                
                <div class="info-box">
                    <h3>📚 Operaciones Disponibles</h3>
                    <div class="operations-grid">
                        <div class="operation-card" onclick="selectOperation('mostrar_todos')">
                            <h4>🌍 Vista General</h4>
                            <p>Mostrar todos los continentes con datos básicos</p>
                        </div>
                        <div class="operation-card" onclick="selectOperation('info_detallada')">
                            <h4>🔍 Detalles</h4>
                            <p>Información completa de un continente específico</p>
                        </div>
                        <div class="operation-card" onclick="selectOperation('estadisticas_globales')">
                            <h4>📈 Estadísticas</h4>
                            <p>Estadísticas mundiales y rankings</p>
                        </div>
                        <div class="operation-card" onclick="selectOperation('buscar_pais')">
                            <h4>📍 Buscar País</h4>
                            <p>Encontrar información de un país específico</p>
                        </div>
                        <div class="operation-card" onclick="selectOperation('comparar_continentes')">
                            <h4>📊 Comparar</h4>
                            <p>Tabla comparativa entre continentes</p>
                        </div>
                        <div class="operation-card" onclick="selectOperation('generar_graficos')">
                            <h4>📈 Gráficos</h4>
                            <p>Visualizaciones con Seaborn/Matplotlib</p>
                        </div>
                    </div>
                </div>
                
                <?php if (!$python_funciona): ?>
                <div class="error">
                    <h4>⚠️ Problemas de Configuración</h4>
                    <p>Python no está disponible. Verifica:</p>
                    <ul>
                        <li>Python 3 instalado: <code>python3 --version</code></li>
                        <li>Librerías: <code>pip install seaborn matplotlib pandas numpy</code></li>
                        <li>Estructura de directorios correcta</li>
                        <li>Archivo: <code>python/database_continentes.py</code> existe</li>
                        <li>Archivo: <code>python/graficos_continentes.py</code> existe</li>
                    </ul>
                    <p><strong>Error detallado:</strong> <?php echo $python_detalle; ?></p>
                </div>
                <?php endif; ?>
            </div>
            
            <div class="result-section">
                <h2>📊 Resultados</h2>
                <?php if ($resultado): ?>
                    <div class="result-box">
                        <h3>Operación: <?php echo ucfirst(str_replace('_', ' ', $operacion_seleccionada)); ?></h3>
                        <?php echo $resultado; ?>
                    </div>
                <?php else: ?>
                    <div style="text-align: center; padding: 40px; color: #7f8c8d;">
                        <p>👆 Selecciona una operación para explorar los datos continentales</p>
                        <p><small>Datos geográficos precisos con información actualizada</small></p>
                        <?php if (!$python_funciona): ?>
                        <div class="error">
                            ⚠️ El sistema no puede funcionar sin Python<br>
                            Por favor, soluciona los problemas de conexión primero.
                        </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="info-section">
            <h2>🌐 Acerca del Sistema</h2>
            
            <?php if ($python_funciona): ?>
            <?php 
                $estadisticas = ejecutarPython('get_estadisticas_globales');
                if (!isset($estadisticas['error'])):
            ?>
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-number"><?php echo $estadisticas['total_continentes']; ?></div>
                    <div class="stat-label">Continentes</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?php echo $estadisticas['total_paises']; ?></div>
                    <div class="stat-label">Países</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?php echo formatearNumero($estadisticas['poblacion_total']); ?></div>
                    <div class="stat-label">Habitantes</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?php echo formatearArea($estadisticas['area_total']); ?></div>
                    <div class='stat-label'>Área Total</div>
                </div>
            </div>
            <?php endif; ?>
            <?php endif; ?>
            
            <div class="info-box">
                <h3>🛠️ Tecnologías Utilizadas</h3>
                <div class="stats-grid">
                    <div class="stat">
                        <strong>PHP</strong><br>Interfaz web y lógica de presentación
                    </div>
                    <div class="stat">
                        <strong>Python</strong><br>Procesamiento de datos y análisis
                    </div>
                    <div class="stat">
                        <strong>Seaborn/Matplotlib</strong><br>Visualización de datos
                    </div>
                    <div class="stat">
                        <strong>JSON</strong><br>Almacenamiento de datos estructurados
                    </div>
                </div>
                
                <div style="margin-top: 15px; padding: 15px; background: white; border-radius: 5px;">
                    <h4>📁 Estructura del Proyecto</h4>
                    <pre style="background: #2c3e50; color: white; padding: 15px; border-radius: 5px; font-size: 12px;">
sistema_continentes/
├── php/
│   └── continentes.php              (este archivo)
├── python/
│   ├── database_continentes.py      (procesamiento de datos)
│   └── graficos_continentes.py      (generación de gráficos)
└── continentes_data.json            (datos automáticos)
                    </pre>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleContinenteSelect() {
            const operacion = document.getElementById('operacion').value;
            const continenteGroup = document.getElementById('continente-group');
            const paisGroup = document.getElementById('pais-group');
            
            if (operacion === 'info_detallada') {
                continenteGroup.style.display = 'block';
                paisGroup.style.display = 'none';
            } else if (operacion === 'buscar_pais') {
                continenteGroup.style.display = 'none';
                paisGroup.style.display = 'block';
            } else {
                continenteGroup.style.display = 'none';
                paisGroup.style.display = 'none';
            }
        }
        
        function selectOperation(operation) {
            const operationMap = {
                'Vista General': 'mostrar_todos',
                'Detalles': 'info_detallada',
                'Estadísticas': 'estadisticas_globales',
                'Buscar País': 'buscar_pais',
                'Comparar': 'comparar_continentes',
                'Gráficos': 'generar_graficos'
            };
            
            document.getElementById('operacion').value = operationMap[operation] || operation;
            toggleContinenteSelect();
            
            // Efecto visual de selección
            document.querySelectorAll('.operation-card').forEach(card => {
                card.style.borderLeftColor = '#27ae60';
                card.style.transform = 'translateY(0)';
            });
            
            const selectedCard = Array.from(document.querySelectorAll('.operation-card')).find(card => 
                card.querySelector('h4').textContent === operation
            );
            
            if (selectedCard) {
                selectedCard.style.borderLeftColor = '#e74c3c';
                selectedCard.style.transform = 'translateY(-5px)';
            }
        }
        
        // Inicializar estado del formulario
        document.addEventListener('DOMContentLoaded', function() {
            toggleContinenteSelect();
            
            // Añadir interactividad a las tarjetas de operación
            document.querySelectorAll('.operation-card').forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.boxShadow = '0 8px 25px rgba(0,0,0,0.15)';
                });
                
                card.addEventListener('mouseleave', function() {
                    if (this.style.borderLeftColor !== 'rgb(231, 76, 60)') {
                        this.style.boxShadow = '0 2px 5px rgba(0,0,0,0.1)';
                    }
                });
            });
        });
    </script>
</body>
</html>