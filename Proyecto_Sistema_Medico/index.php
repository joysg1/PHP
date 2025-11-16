<?php
// ==================== CONFIGURACIÓN Y DEBUG ====================
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
        0 => ['pipe', 'r'], // stdin
        1 => ['pipe', 'w'], // stdout
        2 => ['pipe', 'w']  // stderr
    ];
    
    $process = proc_open('python3 database.py', $descriptors, $pipes);
    
    if (is_resource($process)) {
        fwrite($pipes[0], $comando_data);
        fclose($pipes[0]);
        
        $output = stream_get_contents($pipes[1]);
        fclose($pipes[1]);
        
        $error = stream_get_contents($pipes[2]);
        fclose($pipes[2]);
        
        $return_code = proc_close($process);
        
        // Log para debug
        error_log("Python Command: $comando");
        error_log("Python Input: $comando_data");
        error_log("Python Output: $output");
        error_log("Python Error: $error");
        error_log("Python Return Code: $return_code");
        
        if ($return_code === 0) {
            $resultado = json_decode($output, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                return $resultado;
            } else {
                error_log("JSON decode error: " . json_last_error_msg());
                return ['error' => 'Error decodificando JSON de Python: ' . json_last_error_msg()];
            }
        } else {
            error_log("Python execution error: $error");
            return ['error' => "Error en ejecución Python (Code: $return_code): $error"];
        }
    }
    
    return ['error' => 'No se pudo ejecutar Python - proceso no creado'];
}

// ==================== FUNCIONES PROFESIONALES (CON PYTHON) ====================
function llamarProfesional($paciente, $sintoma, $especialidad) {
    error_log("Buscando profesional: $especialidad para $paciente");
    
    $profesional_data = ejecutarPython('get_profesional', ['especialidad' => $especialidad]);
    
    // Debug detallado
    error_log("Resultado búsqueda $especialidad: " . print_r($profesional_data, true));
    
    if (isset($profesional_data['error']) || empty($profesional_data) || !isset($profesional_data['nombre'])) {
        $error_msg = $profesional_data['error'] ?? 'Profesional no encontrado en la base de datos';
        return [
            'mensaje' => "❌ <strong>Profesional no disponible</strong> para <em>$paciente</em><br>
                         → Especialidad: $especialidad<br>
                         → Síntoma: $sintoma<br>
                         → <small style='color: red;'>Error: $error_msg</small>",
            'profesional' => "No disponible",
            'error' => true
        ];
    }
    
    $nombre = $profesional_data['nombre'];
    $telefono = $profesional_data['telefono'];
    $especialidad_real = $profesional_data['especialidad'];
    
    $acciones = [
        'Médico General' => 'Diagnóstico inicial y receta básica',
        'Cardióloga' => 'Electrocardiograma y evaluación cardiaca',
        'Traumatólogo' => 'Radiografía y inmovilización',
        'Psicóloga' => 'Sesión de terapia y ejercicios mentales',
        'Dermatóloga' => 'Análisis de piel y tratamiento tópico',
        'Nutriólogo' => 'Plan alimenticio y recomendaciones nutricionales',
        'Fisioterapeuta' => 'Ejercicios de rehabilitación y terapia física'
    ];
    
    $accion = $acciones[$especialidad_real] ?? 'Evaluación especializada';
    
    error_log("Profesional encontrado: $nombre ($especialidad_real)");
    
    return [
        'mensaje' => "✅ <strong>$nombre ($especialidad_real)</strong> atendiendo a <em>$paciente</em><br>
                     → Síntoma: $sintoma<br>
                     → Teléfono: $telefono<br>
                     → Acción: $accion",
        'profesional' => $nombre,
        'error' => false
    ];
}

// ==================== FUNCIÓN PRINCIPAL DE EVALUACIÓN ====================
function evaluarPaciente($nombre, $afeccion, $sintomas) {
    error_log("Evaluando paciente: $nombre con afección: $afeccion");
    
    $resultado = "<div class='resultado-paciente'>";
    $resultado .= "<h3>🩺 Diagnóstico para: $nombre</h3>";
    $resultado .= "<p><strong>Afección:</strong> $afeccion</p>";
    $resultado .= "<p><strong>Síntomas:</strong> $sintomas</p>";
    $resultado .= "<div class='profesionales-llamados'>";
    
    $profesionalesLlamados = [];
    $mensajes = [];
    $errores = 0;
    
    // Lógica de asignación de profesionales - CORREGIDA
    $asignaciones = [
        'problema cardiaco' => ['Médico General', 'Cardióloga'],
        'fractura o lesión' => ['Médico General', 'Traumatólogo', 'Fisioterapeuta'],
        'ansiedad o estrés' => ['Psicóloga', 'Médico General'],
        'problema de piel' => ['Dermatóloga'],
        'obesidad o nutrición' => ['Nutriólogo', 'Médico General'],
        'dolor muscular' => ['Médico General', 'Fisioterapeuta'],
        'chequeo general' => ['Médico General', 'Nutriólogo']
    ];
    
    $afeccion_key = strtolower(trim($afeccion));
    error_log("Buscando asignaciones para: '$afeccion_key'");
    
    // Verificar que la afección existe en las asignaciones
    if (!isset($asignaciones[$afeccion_key])) {
        $resultado .= "<div class='error'>⚠️ Afección '$afeccion' no reconocida. Usando médico general por defecto.</div>";
        $especialidades = ['Médico General'];
        error_log("Afección no reconocida, usando médico general por defecto");
    } else {
        $especialidades = $asignaciones[$afeccion_key];
        error_log("Especialidades asignadas: " . implode(', ', $especialidades));
    }
    
    foreach ($especialidades as $especialidad) {
        $sintoma_especifico = ($especialidad == 'Nutriólogo' && $afeccion_key == 'chequeo general') 
                            ? "Chequeo nutricional" 
                            : $sintomas;
        
        $resultadoFunc = llamarProfesional($nombre, $sintoma_especifico, $especialidad);
        $mensajes[] = $resultadoFunc['mensaje'];
        
        if (isset($resultadoFunc['error']) && $resultadoFunc['error']) {
            $errores++;
            $profesionalesLlamados[] = "No disponible";
        } else {
            $profesionalesLlamados[] = $resultadoFunc['profesional'];
        }
    }
    
    // Mostrar mensajes
    foreach ($mensajes as $mensaje) {
        $resultado .= "<div class='profesional-card'>$mensaje</div>";
    }
    
    // Resumen de la consulta
    if ($errores > 0) {
        $resultado .= "<div class='error'>⚠️ Se encontraron $errores error(es) al asignar profesionales</div>";
    }
    
    $resultado .= "</div></div>";
    
    // Guardar en base de datos Python
    $datos_guardar = [
        'nombre_paciente' => $nombre,
        'afeccion' => $afeccion,
        'sintomas' => $sintomas,
        'profesionales' => implode(', ', array_filter($profesionalesLlamados))
    ];
    
    error_log("Guardando diagnóstico en Python: " . print_r($datos_guardar, true));
    $guardado = ejecutarPython('save_diagnostico', $datos_guardar);
    
    if (isset($guardado['error'])) {
        $resultado .= "<div class='error'>⚠️ Error al guardar en base de datos: " . $guardado['error'] . "</div>";
        error_log("Error guardando diagnóstico: " . $guardado['error']);
    } else {
        error_log("Diagnóstico guardado exitosamente: ID " . ($guardado['id'] ?? 'N/A'));
    }
    
    return $resultado;
}

// ==================== PROCESAMIENTO PRINCIPAL ====================
$resultadoDiagnostico = "";
$python_funciona = true;
$python_detalle = "Verificando...";

// Verificar si Python está funcionando
error_log("=== INICIANDO SISTEMA ===");
$test_python = ejecutarPython('get_profesionales');

if (isset($test_python['error'])) {
    $python_funciona = false;
    $python_detalle = "Error: " . $test_python['error'];
    $resultadoDiagnostico = "<div class='error'>❌ Error de Python: " . $test_python['error'] . "</div>";
    error_log("Python no funciona: " . $test_python['error']);
} else {
    $python_detalle = "✅ Conectado - " . count($test_python) . " profesionales cargados";
    error_log("Python funciona correctamente - " . count($test_python) . " profesionales");
}

$historial = [];
if ($python_funciona) {
    $historial_data = ejecutarPython('get_diagnosticos');
    if (!isset($historial_data['error'])) {
        $historial = $historial_data;
    }
}

if ($_POST && $python_funciona) {
    $nombre = htmlspecialchars($_POST['nombre'] ?? '');
    $afeccion = htmlspecialchars($_POST['afeccion'] ?? '');
    $sintomas = htmlspecialchars($_POST['sintomas'] ?? '');
    
    if (!empty($nombre) && !empty($afeccion)) {
        $resultadoDiagnostico = evaluarPaciente($nombre, $afeccion, $sintomas);
        // Actualizar historial después de guardar
        $historial_actualizado = ejecutarPython('get_diagnosticos');
        if (!isset($historial_actualizado['error'])) {
            $historial = $historial_actualizado;
        }
    } else {
        $resultadoDiagnostico = "<div class='error'>❌ Por favor, complete todos los campos obligatorios</div>";
    }
}

// Verificar archivos JSON
$archivo_profesionales = file_exists('profesionales.json') ? filesize('profesionales.json') : 0;
$archivo_diagnosticos = file_exists('diagnosticos.json') ? filesize('diagnosticos.json') : 0;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema Médico - PHP + Python</title>
    <style>
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
        }
        .container {
            max-width: 1200px;
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
        }
        .form-section {
            padding: 30px;
            background: #f8f9fa;
        }
        .result-section {
            padding: 30px;
            background: white;
        }
        .historial-section {
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
        input, select, textarea {
            width: 100%;
            padding: 12px;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.3s;
        }
        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: #667eea;
        }
        button {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 18px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s;
        }
        button:hover:not(:disabled) {
            transform: translateY(-2px);
        }
        button:disabled {
            background: #6c757d;
            cursor: not-allowed;
        }
        .resultado-paciente {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            border-left: 5px solid #667eea;
        }
        .profesionales-llamados {
            margin-top: 20px;
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
        .profesional-card {
            background: white;
            padding: 15px;
            margin: 10px 0;
            border-radius: 8px;
            border-left: 4px solid #667eea;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .profesional-card .error {
            border-left-color: #e74c3c;
            background: #f8d7da;
        }
        .historial-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        .historial-table th,
        .historial-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        .historial-table th {
            background: #34495e;
            color: white;
        }
        .historial-table tr:hover {
            background: #f5f6fa;
        }
        .info-box {
            background: #e8f4fd;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .status-ok {
            color: #27ae60;
            font-weight: bold;
        }
        .status-error {
            color: #e74c3c;
            font-weight: bold;
        }
        .status-warning {
            color: #f39c12;
            font-weight: bold;
        }
        .tech-badge {
            display: inline-block;
            background: #3498db;
            color: white;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            margin: 0 5px;
        }
        .debug-info {
            background: #2c3e50;
            color: white;
            padding: 10px;
            border-radius: 5px;
            font-family: monospace;
            font-size: 12px;
            margin: 5px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🏥 Sistema Médico Integrado</h1>
            <p>
                <span class="tech-badge">PHP</span> + 
                <span class="tech-badge">Python</span> + 
                <span class="tech-badge">JSON</span>
            </p>
        </div>
        
        <div class="content">
            <div class="form-section">
                <h2>📋 Formulario del Paciente</h2>
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="nombre">Nombre del Paciente:</label>
                        <input type="text" id="nombre" name="nombre" required 
                               placeholder="Ej: Juan Pérez" value="<?php echo $_POST['nombre'] ?? ''; ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="afeccion">Tipo de Afección:</label>
                        <select id="afeccion" name="afeccion" required>
                            <option value="">-- Seleccione una afección --</option>
                            <option value="Problema cardiaco" <?php echo ($_POST['afeccion'] ?? '') == 'Problema cardiaco' ? 'selected' : ''; ?>>❤️ Problema Cardiaco</option>
                            <option value="Fractura o lesión" <?php echo ($_POST['afeccion'] ?? '') == 'Fractura o lesión' ? 'selected' : ''; ?>>🦴 Fractura o Lesión</option>
                            <option value="Ansiedad o estrés" <?php echo ($_POST['afeccion'] ?? '') == 'Ansiedad o estrés' ? 'selected' : ''; ?>>🧠 Ansiedad o Estrés</option>
                            <option value="Problema de piel" <?php echo ($_POST['afeccion'] ?? '') == 'Problema de piel' ? 'selected' : ''; ?>>🔬 Problema de Piel</option>
                            <option value="Obesidad o nutrición" <?php echo ($_POST['afeccion'] ?? '') == 'Obesidad o nutrición' ? 'selected' : ''; ?>>🍎 Obesidad o Nutrición</option>
                            <option value="Dolor muscular" <?php echo ($_POST['afeccion'] ?? '') == 'Dolor muscular' ? 'selected' : ''; ?>>💪 Dolor Muscular</option>
                            <option value="Chequeo general" <?php echo ($_POST['afeccion'] ?? '') == 'Chequeo general' ? 'selected' : ''; ?>>🩺 Chequeo General</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="sintomas">Descripción de Síntomas:</label>
                        <textarea id="sintomas" name="sintomas" rows="4" 
                                  placeholder="Describa sus síntomas en detalle..."><?php echo $_POST['sintomas'] ?? ''; ?></textarea>
                    </div>
                    
                    <button type="submit" <?php echo !$python_funciona ? 'disabled' : ''; ?>>
                        <?php echo $python_funciona ? '🐍 Guardar con Python' : '❌ Python No Disponible'; ?>
                    </button>
                </form>
                
                <div class="info-box">
                    <h3>🔧 Estado del Sistema</h3>
                    <p><strong>PHP:</strong> <span class="status-ok">✅ Interfaz Web</span></p>
                    <p><strong>Python:</strong> 
                        <span class="<?php echo $python_funciona ? 'status-ok' : 'status-error'; ?>">
                            <?php echo $python_detalle; ?>
                        </span>
                    </p>
                    <p><strong>Archivo Profesionales:</strong> 
                        <span class="<?php echo $archivo_profesionales > 100 ? 'status-ok' : 'status-error'; ?>">
                            <?php echo $archivo_profesionales > 100 ? '✅ ' . $archivo_profesionales . ' bytes' : '❌ Vacío/No existe'; ?>
                        </span>
                    </p>
                    <p><strong>Archivo Diagnósticos:</strong> 
                        <span class="<?php echo $archivo_diagnosticos > 0 ? 'status-ok' : 'status-warning'; ?>">
                            <?php echo $archivo_diagnosticos > 0 ? '✅ ' . $archivo_diagnosticos . ' bytes' : '⚠️ Sin datos aún'; ?>
                        </span>
                    </p>
                    
                    <?php if (!$python_funciona): ?>
                    <div class="error">
                        <strong>Solucionar Problemas:</strong><br>
                        1. Verifica que Python3 esté instalado<br>
                        2. Ejecuta: <code>python3 --version</code><br>
                        3. Verifica permisos de archivos<br>
                        4. Revisa los logs de error
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="result-section">
                <h2>📊 Resultado del Diagnóstico</h2>
                <?php if ($resultadoDiagnostico): ?>
                    <?php echo $resultadoDiagnostico; ?>
                <?php else: ?>
                    <div style="text-align: center; padding: 40px; color: #7f8c8d;">
                        <p>👆 Complete el formulario para ver el diagnóstico</p>
                        <p><small>Los datos se gestionan con Python y se almacenan en JSON</small></p>
                        <?php if (!$python_funciona): ?>
                        <div class="error">
                            ⚠️ El sistema no puede funcionar sin Python<br>
                            Por favor, soluciona los problemas de conexión primero.
                        </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="historial-section">
                <h2>📋 Historial de Diagnósticos (Últimos 10)</h2>
                <?php if (is_array($historial) && !empty($historial) && !isset($historial['error'])): ?>
                    <table class="historial-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Paciente</th>
                                <th>Afección</th>
                                <th>Profesionales</th>
                                <th>Fecha</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach (array_reverse($historial) as $registro): ?>
                            <tr>
                                <td><?php echo $registro['id']; ?></td>
                                <td><?php echo htmlspecialchars($registro['nombre_paciente']); ?></td>
                                <td><?php echo htmlspecialchars($registro['afeccion']); ?></td>
                                <td><?php echo htmlspecialchars($registro['profesionales']); ?></td>
                                <td><?php echo $registro['fecha_creacion']; ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p style="text-align: center; padding: 20px; color: #7f8c8d;">
                        <?php if (isset($historial['error'])): ?>
                            <span class="error">Error cargando historial: <?php echo $historial['error']; ?></span>
                        <?php else: ?>
                            No hay diagnósticos registrados aún.
                        <?php endif; ?>
                    </p>
                <?php endif; ?>
                
                <div class="info-box">
                    <h4>📝 Debug Information</h4>
                    <div class="debug-info">
                        PHP Version: <?php echo phpversion(); ?><br>
                        Python Available: <?php echo $python_funciona ? 'Yes' : 'No'; ?><br>
                        Professionals File: <?php echo $archivo_profesionales; ?> bytes<br>
                        Diagnostics File: <?php echo $archivo_diagnosticos; ?> bytes<br>
                        Total Records: <?php echo count($historial); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>