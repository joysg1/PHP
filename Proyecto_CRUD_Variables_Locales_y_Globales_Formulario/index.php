<?php
// Iniciar sesión para variables globales de sesión
session_start();

// Base de datos JSON
$db_file = 'datos_usuarios.json';

// ====================
// VARIABLES GLOBALES
// ====================
$GLOBALS['config'] = [
    'max_edad' => 120,
    'min_edad' => 1,
    'empresa' => 'Tech Solutions Inc.'
];

// ====================
// FUNCIONES DE BASE DE DATOS
// ====================

/**
 * Función con variables LOCALES - Cargar datos JSON
 */
function cargarDatos() {
    global $db_file; // Variable global accedida con 'global'
    
    // Variables locales a la función
    $datos = [];
    $json_data = '';
    
    if (file_exists($db_file)) {
        $json_data = file_get_contents($db_file);
        $datos = json_decode($json_data, true) ?? [];
    }
    
    return $datos;
}

/**
 * Función con variables LOCALES - Guardar datos JSON
 */
function guardarDatos($datos) {
    global $db_file;
    
    // Variables locales
    $json_data = json_encode($datos, JSON_PRETTY_PRINT);
    $resultado = file_put_contents($db_file, $json_data);
    
    return $resultado !== false;
}

/**
 * Función con variables LOCALES - Buscar usuario por ID
 */
function buscarUsuarioPorId($id) {
    $usuarios = cargarDatos();
    
    foreach ($usuarios as $index => $usuario) {
        if ($usuario['id'] === $id) {
            return ['usuario' => $usuario, 'index' => $index];
        }
    }
    
    return null;
}

/**
 * Función con variables LOCALES - Eliminar usuario
 */
function eliminarUsuario($id) {
    $usuarios = cargarDatos();
    $usuario_encontrado = false;
    
    foreach ($usuarios as $index => $usuario) {
        if ($usuario['id'] === $id) {
            unset($usuarios[$index]);
            $usuario_encontrado = true;
            break;
        }
    }
    
    if ($usuario_encontrado) {
        $usuarios = array_values($usuarios); // Reindexar array
        return guardarDatos($usuarios);
    }
    
    return false;
}

/**
 * Función con variables LOCALES - Actualizar usuario
 */
function actualizarUsuario($id, $datos_actualizados) {
    $usuarios = cargarDatos();
    $actualizado = false;
    
    foreach ($usuarios as $index => &$usuario) {
        if ($usuario['id'] === $id) {
            $usuario = array_merge($usuario, $datos_actualizados);
            $actualizado = true;
            break;
        }
    }
    
    if ($actualizado) {
        return guardarDatos($usuarios);
    }
    
    return false;
}

// ====================
// PROCESAMIENTO DE ACCIONES
// ====================

// Variables locales del script principal
$action = $_POST['action'] ?? $_GET['action'] ?? '';
$id = $_POST['id'] ?? $_GET['id'] ?? '';

// Procesar diferentes acciones
switch ($action) {
    case 'create':
        // Crear nuevo usuario
        $nombre = $_POST['nombre'] ?? '';
        $edad = $_POST['edad'] ?? '';
        $ciudad = $_POST['ciudad'] ?? '';
        $profesion = $_POST['profesion'] ?? '';
        
        if (!empty($nombre) && !empty($edad)) {
            $nuevo_usuario = [
                'id' => uniqid(),
                'nombre' => $nombre,
                'edad' => intval($edad),
                'ciudad' => $ciudad,
                'profesion' => $profesion,
                'fecha_registro' => date('Y-m-d H:i:s'),
                'fecha_actualizacion' => date('Y-m-d H:i:s')
            ];
            
            $datos = cargarDatos();
            $datos[] = $nuevo_usuario;
            
            if (guardarDatos($datos)) {
                $_SESSION['mensaje'] = "✅ Usuario registrado exitosamente!";
                $_SESSION['tipo_mensaje'] = 'success';
            } else {
                $_SESSION['mensaje'] = "❌ Error al guardar el usuario";
                $_SESSION['tipo_mensaje'] = 'error';
            }
        }
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
        
    case 'edit':
        // Mostrar formulario de edición
        $usuario_editar = buscarUsuarioPorId($id);
        if ($usuario_editar) {
            $usuario = $usuario_editar['usuario'];
        }
        break;
        
    case 'update':
        // Actualizar usuario
        $nombre = $_POST['nombre'] ?? '';
        $edad = $_POST['edad'] ?? '';
        $ciudad = $_POST['ciudad'] ?? '';
        $profesion = $_POST['profesion'] ?? '';
        
        if (!empty($nombre) && !empty($edad)) {
            $datos_actualizados = [
                'nombre' => $nombre,
                'edad' => intval($edad),
                'ciudad' => $ciudad,
                'profesion' => $profesion,
                'fecha_actualizacion' => date('Y-m-d H:i:s')
            ];
            
            if (actualizarUsuario($id, $datos_actualizados)) {
                $_SESSION['mensaje'] = "✅ Usuario actualizado exitosamente!";
                $_SESSION['tipo_mensaje'] = 'success';
            } else {
                $_SESSION['mensaje'] = "❌ Error al actualizar el usuario";
                $_SESSION['tipo_mensaje'] = 'error';
            }
        }
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
        
    case 'delete':
        // Eliminar usuario
        if (eliminarUsuario($id)) {
            $_SESSION['mensaje'] = "✅ Usuario eliminado exitosamente!";
            $_SESSION['tipo_mensaje'] = 'success';
        } else {
            $_SESSION['mensaje'] = "❌ Error al eliminar el usuario";
            $_SESSION['tipo_mensaje'] = 'error';
        }
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
}

// Cargar todos los usuarios para mostrar
$usuarios = cargarDatos();

// Variables para el formulario de edición
$modo_edicion = isset($usuario);
$titulo_formulario = $modo_edicion ? "✏️ Editar Usuario" : "📝 Registrar Nuevo Usuario";
$texto_boton = $modo_edicion ? "💾 Actualizar Usuario" : "💾 Guardar Usuario";
$action_form = $modo_edicion ? "update" : "create";
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema CRUD Usuarios - PHP Profesional</title>
    <style>
        :root {
            --primary: #6366f1;
            --primary-dark: #4f46e5;
            --secondary: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --dark: #1f2937;
            --darker: #111827;
            --light: #f3f4f6;
            --gray: #6b7280;
            --success: #10b981;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, var(--darker), var(--dark));
            color: var(--light);
            min-height: 100vh;
            line-height: 1.6;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 40px;
            padding: 30px 0;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 15px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .header h1 {
            font-size: 2.5rem;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 10px;
        }
        
        .grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-bottom: 40px;
        }
        
        @media (max-width: 768px) {
            .grid {
                grid-template-columns: 1fr;
            }
        }
        
        .card {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 15px;
            padding: 30px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
        }
        
        .card h2 {
            color: var(--primary);
            margin-bottom: 20px;
            font-size: 1.5rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            color: var(--gray);
            font-weight: 500;
        }
        
        input, select {
            width: 100%;
            padding: 12px 15px;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 8px;
            color: var(--light);
            font-size: 1rem;
            transition: all 0.3s ease;
        }
        
        input:focus, select:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.2);
        }
        
        .btn {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 8px;
            font-size: 0.9rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }
        
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(99, 102, 241, 0.3);
        }
        
        .btn-warning {
            background: linear-gradient(135deg, var(--warning), #d97706);
        }
        
        .btn-danger {
            background: linear-gradient(135deg, var(--danger), #dc2626);
        }
        
        .btn-success {
            background: linear-gradient(135deg, var(--success), #059669);
        }
        
        .btn-secondary {
            background: linear-gradient(135deg, var(--gray), #4b5563);
        }

        .btn-sm {
            padding: 8px 15px;
            font-size: 0.8rem;
        }

        .action-buttons {
            display: flex;
            gap: 8px;
            margin-top: 15px;
            flex-wrap: wrap;
        }
        
        .users-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        
        .user-card {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 12px;
            padding: 20px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
            position: relative;
        }
        
        .user-card:hover {
            background: rgba(255, 255, 255, 0.08);
            transform: translateY(-3px);
        }
        
        .user-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 15px;
        }
        
        .user-name {
            font-weight: 600;
            color: var(--primary);
            font-size: 1.1rem;
            flex: 1;
        }
        
        .user-age {
            background: var(--primary);
            color: white;
            padding: 4px 8px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            margin-left: 10px;
        }
        
        .user-detail {
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 8px;
            color: var(--gray);
            font-size: 0.9rem;
        }
        
        .user-id {
            font-family: 'Courier New', monospace;
            font-size: 0.7rem;
            color: var(--gray);
            background: rgba(255, 255, 255, 0.05);
            padding: 4px 8px;
            border-radius: 4px;
            margin-top: 10px;
        }
        
        .stats {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 12px;
            padding: 20px;
            text-align: center;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 5px;
        }
        
        .stat-label {
            color: var(--gray);
            font-size: 0.9rem;
        }
        
        .alert {
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            border-left: 4px solid var(--primary);
            background: rgba(99, 102, 241, 0.1);
        }
        
        .alert-success {
            border-left-color: var(--success);
            background: rgba(16, 185, 129, 0.1);
        }
        
        .alert-error {
            border-left-color: var(--danger);
            background: rgba(239, 68, 68, 0.1);
        }
        
        .code-block {
            background: rgba(0, 0, 0, 0.3);
            border-radius: 8px;
            padding: 15px;
            font-family: 'Courier New', monospace;
            font-size: 0.9rem;
            margin: 10px 0;
            border: 1px solid rgba(255, 255, 255, 0.1);
            line-height: 1.4;
        }
        
        .var-local {
            color: #f59e0b;
        }
        
        .var-global {
            color: #10b981;
        }
        
        .var-session {
            color: #8b5cf6;
        }
        
        .footer {
            text-align: center;
            margin-top: 50px;
            padding: 20px;
            color: var(--gray);
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .form-actions {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }

        .cancel-btn {
            background: transparent;
            border: 1px solid var(--gray);
            color: var(--gray);
        }

        .cancel-btn:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.8);
            backdrop-filter: blur(5px);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }

        .modal {
            background: var(--darker);
            border-radius: 15px;
            padding: 30px;
            max-width: 500px;
            width: 90%;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .modal h3 {
            color: var(--primary);
            margin-bottom: 15px;
        }

        .modal-actions {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🚀 Sistema CRUD Usuarios PHP</h1>
            <p>Gestión completa con variables locales, globales y base de datos JSON</p>
        </div>

        <?php if (isset($_SESSION['mensaje'])): ?>
            <div class="alert alert-<?php echo $_SESSION['tipo_mensaje'] === 'error' ? 'error' : 'success'; ?>">
                <?php echo $_SESSION['mensaje']; 
                unset($_SESSION['mensaje']);
                unset($_SESSION['tipo_mensaje']); ?>
            </div>
        <?php endif; ?>

        <div class="stats">
            <div class="stat-card">
                <div class="stat-number"><?php echo count($usuarios); ?></div>
                <div class="stat-label">Total Usuarios</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">
                    <?php 
                    $edades = array_column($usuarios, 'edad');
                    echo count($edades) > 0 ? round(array_sum($edades) / count($edades), 1) : 0;
                    ?>
                </div>
                <div class="stat-label">Edad Promedio</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">
                    <?php
                    $ciudades = array_column($usuarios, 'ciudad');
                    echo count(array_unique($ciudades));
                    ?>
                </div>
                <div class="stat-label">Ciudades Únicas</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">
                    <?php
                    $profesiones = array_column($usuarios, 'profesion');
                    echo count(array_unique($profesiones));
                    ?>
                </div>
                <div class="stat-label">Profesiones</div>
            </div>
        </div>

        <div class="grid">
            <!-- Formulario de Registro/Edición -->
            <div class="card">
                <h2><?php echo $titulo_formulario; ?></h2>
                <form method="POST" action="">
                    <input type="hidden" name="action" value="<?php echo $action_form; ?>">
                    <?php if ($modo_edicion): ?>
                        <input type="hidden" name="id" value="<?php echo $usuario['id']; ?>">
                    <?php endif; ?>
                    
                    <div class="form-group">
                        <label for="nombre">Nombre Completo</label>
                        <input type="text" id="nombre" name="nombre" required 
                               value="<?php echo $modo_edicion ? htmlspecialchars($usuario['nombre']) : ''; ?>"
                               placeholder="Ej: Juan Pérez">
                    </div>
                    
                    <div class="form-group">
                        <label for="edad">Edad</label>
                        <input type="number" id="edad" name="edad" required 
                               min="<?php echo $GLOBALS['config']['min_edad']; ?>"
                               max="<?php echo $GLOBALS['config']['max_edad']; ?>"
                               value="<?php echo $modo_edicion ? $usuario['edad'] : ''; ?>"
                               placeholder="Ej: 25">
                    </div>
                    
                    <div class="form-group">
                        <label for="ciudad">Ciudad</label>
                        <input type="text" id="ciudad" name="ciudad" required 
                               value="<?php echo $modo_edicion ? htmlspecialchars($usuario['ciudad']) : ''; ?>"
                               placeholder="Ej: Madrid">
                    </div>
                    
                    <div class="form-group">
                        <label for="profesion">Profesión</label>
                        <select id="profesion" name="profesion" required>
                            <option value="">Selecciona una profesión</option>
                            <option value="Desarrollador" <?php echo ($modo_edicion && $usuario['profesion'] === 'Desarrollador') ? 'selected' : ''; ?>>Desarrollador</option>
                            <option value="Diseñador" <?php echo ($modo_edicion && $usuario['profesion'] === 'Diseñador') ? 'selected' : ''; ?>>Diseñador</option>
                            <option value="Gerente" <?php echo ($modo_edicion && $usuario['profesion'] === 'Gerente') ? 'selected' : ''; ?>>Gerente</option>
                            <option value="Analista" <?php echo ($modo_edicion && $usuario['profesion'] === 'Analista') ? 'selected' : ''; ?>>Analista</option>
                            <option value="Consultor" <?php echo ($modo_edicion && $usuario['profesion'] === 'Consultor') ? 'selected' : ''; ?>>Consultor</option>
                            <option value="Estudiante" <?php echo ($modo_edicion && $usuario['profesion'] === 'Estudiante') ? 'selected' : ''; ?>>Estudiante</option>
                            <option value="Otro" <?php echo ($modo_edicion && $usuario['profesion'] === 'Otro') ? 'selected' : ''; ?>>Otro</option>
                        </select>
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" class="btn btn-success">
                            <?php echo $texto_boton; ?>
                        </button>
                        
                        <?php if ($modo_edicion): ?>
                            <a href="<?php echo $_SERVER['PHP_SELF']; ?>" class="btn cancel-btn">
                                ❌ Cancelar
                            </a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>

            <!-- Información de Variables -->
            <div class="card">
                <h2>🔧 Variables PHP en Acción</h2>
                
                <div class="code-block">
                    <span class="var-global">// Variable GLOBAL de configuración</span><br>
                    $GLOBALS['config'] = [<br>
                    &nbsp;&nbsp;'max_edad' => 120,<br>
                    &nbsp;&nbsp;'empresa' => 'Tech Solutions Inc.'<br>
                    ];
                </div>
                
                <div class="code-block">
                    <span class="var-local">// Variables LOCALES en función</span><br>
                    function buscarUsuarioPorId(<span class="var-local">$id</span>) {<br>
                    &nbsp;&nbsp;<span class="var-local">$usuarios</span> = cargarDatos();<br>
                    &nbsp;&nbsp;foreach (<span class="var-local">$usuarios</span> as <span class="var-local">$index</span> => <span class="var-local">$usuario</span>) {<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;if (<span class="var-local">$usuario</span>['id'] === <span class="var-local">$id</span>) {<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;return ['usuario' => <span class="var-local">$usuario</span>, 'index' => <span class="var-local">$index</span>];<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;}<br>
                    &nbsp;&nbsp;}<br>
                    &nbsp;&nbsp;return null;<br>
                    }
                </div>
                
                <div class="code-block">
                    <span class="var-session">// Variables de SESIÓN para mensajes</span><br>
                    $_SESSION['mensaje'] = "Usuario actualizado!";<br>
                    $_SESSION['tipo_mensaje'] = 'success';
                </div>

                <div style="margin-top: 20px;">
                    <button class="btn btn-secondary" onclick="recargarDatos()">
                        🔄 Recargar Datos
                    </button>
                    <button class="btn btn-warning" onclick="exportarJSON()">
                        📁 Exportar JSON
                    </button>
                </div>
            </div>
        </div>

        <!-- Lista de Usuarios -->
        <div class="card">
            <div style="display: flex; justify-content: between; align-items: center; margin-bottom: 20px;">
                <h2>👥 Usuarios Registrados (<?php echo count($usuarios); ?>)</h2>
                <?php if (count($usuarios) > 0): ?>
                    <button class="btn btn-danger btn-sm" onclick="confirmarEliminarTodos()">
                        🗑️ Eliminar Todos
                    </button>
                <?php endif; ?>
            </div>
            
            <?php if (empty($usuarios)): ?>
                <div style="text-align: center; padding: 40px; color: var(--gray);">
                    📝 No hay usuarios registrados aún. ¡Agrega el primero!
                </div>
            <?php else: ?>
                <div class="users-grid">
                    <?php foreach ($usuarios as $usuario): ?>
                        <div class="user-card">
                            <div class="user-header">
                                <div class="user-name"><?php echo htmlspecialchars($usuario['nombre']); ?></div>
                                <div class="user-age"><?php echo $usuario['edad']; ?> años</div>
                            </div>
                            
                            <div class="user-detail">
                                🏙️ <?php echo htmlspecialchars($usuario['ciudad']); ?>
                            </div>
                            <div class="user-detail">
                                💼 <?php echo htmlspecialchars($usuario['profesion']); ?>
                            </div>
                            <div class="user-detail">
                                📅 Registro: <?php echo $usuario['fecha_registro']; ?>
                            </div>
                            <?php if (!empty($usuario['fecha_actualizacion']) && $usuario['fecha_actualizacion'] !== $usuario['fecha_registro']): ?>
                                <div class="user-detail">
                                    ✏️ Actualizado: <?php echo $usuario['fecha_actualizacion']; ?>
                                </div>
                            <?php endif; ?>
                            
                            <div class="user-id">
                                ID: <?php echo $usuario['id']; ?>
                            </div>
                            
                            <div class="action-buttons">
                                <a href="?action=edit&id=<?php echo $usuario['id']; ?>" class="btn btn-warning btn-sm">
                                    ✏️ Editar
                                </a>
                                <button class="btn btn-danger btn-sm" 
                                        onclick="confirmarEliminar('<?php echo $usuario['id']; ?>', '<?php echo htmlspecialchars($usuario['nombre']); ?>')">
                                    🗑️ Eliminar
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="footer">
            <p>Sistema CRUD desarrollado con PHP - Variables Locales y Globales | Base de Datos JSON</p>
            <p style="margin-top: 10px; font-size: 0.9rem; color: var(--gray);">
                <?php echo $GLOBALS['config']['empresa']; ?> - 
                <?php echo count($usuarios); ?> usuarios registrados
            </p>
        </div>
    </div>

    <!-- Modal de Confirmación para Eliminar -->
    <div class="modal-overlay" id="modalEliminar">
        <div class="modal">
            <h3>🗑️ Confirmar Eliminación</h3>
            <p id="textoConfirmacion">¿Estás seguro de que deseas eliminar este usuario?</p>
            <div class="modal-actions">
                <button class="btn btn-secondary" onclick="cerrarModal()">Cancelar</button>
                <button class="btn btn-danger" id="btnConfirmarEliminar">Sí, Eliminar</button>
            </div>
        </div>
    </div>

    <script>
        function recargarDatos() {
            location.reload();
        }

        function exportarJSON() {
            const data = <?php echo json_encode($usuarios, JSON_PRETTY_PRINT); ?>;
            const blob = new Blob([JSON.stringify(data, null, 2)], { type: 'application/json' });
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = 'usuarios_backup.json';
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            URL.revokeObjectURL(url);
        }

        function confirmarEliminar(id, nombre) {
            const modal = document.getElementById('modalEliminar');
            const texto = document.getElementById('textoConfirmacion');
            const btnConfirmar = document.getElementById('btnConfirmarEliminar');
            
            texto.textContent = `¿Estás seguro de que deseas eliminar al usuario "${nombre}"?`;
            btnConfirmar.onclick = function() {
                window.location.href = `?action=delete&id=${id}`;
            };
            
            modal.style.display = 'flex';
        }

        function confirmarEliminarTodos() {
            if (confirm('¿Estás seguro de que deseas eliminar TODOS los usuarios? Esta acción no se puede deshacer.')) {
                // Crear formulario temporal para eliminar todos
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '';
                
                const inputAction = document.createElement('input');
                inputAction.type = 'hidden';
                inputAction.name = 'action';
                inputAction.value = 'delete_all';
                
                form.appendChild(inputAction);
                document.body.appendChild(form);
                form.submit();
            }
        }

        function cerrarModal() {
            document.getElementById('modalEliminar').style.display = 'none';
        }

        // Cerrar modal al hacer clic fuera
        document.getElementById('modalEliminar').addEventListener('click', function(e) {
            if (e.target === this) {
                cerrarModal();
            }
        });

        // Animación para los cards
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.card, .user-card');
            cards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                
                setTimeout(() => {
                    card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 100);
            });
        });
    </script>

    <?php
    // Procesar eliminación de todos los usuarios
    if ($_POST['action'] === 'delete_all') {
        if (guardarDatos([])) {
            $_SESSION['mensaje'] = "✅ Todos los usuarios han sido eliminados!";
            $_SESSION['tipo_mensaje'] = 'success';
        } else {
            $_SESSION['mensaje'] = "❌ Error al eliminar los usuarios";
            $_SESSION['tipo_mensaje'] = 'error';
        }
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    }
    ?>
</body>
</html>