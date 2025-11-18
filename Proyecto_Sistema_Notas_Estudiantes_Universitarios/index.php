<?php
require_once 'funciones.php';

// Verificar si la API está funcionando
$api_activa = verificarAPI();
if (!$api_activa) {
    die('<div style="text-align: center; padding: 2rem; color: red;">
        <h2>❌ Error de Conexión</h2>
        <p>El servidor API no está disponible. Asegúrate de que Python Flask esté corriendo en el puerto 5000.</p>
        <p>Ejecuta: <code>python app.py</code></p>
    </div>');
}

// Procesar eliminaciones
if (isset($_GET['eliminar'])) {
    $resultado = eliminarEstudiante($_GET['eliminar']);
    if ($resultado['success']) {
        header('Location: index.php?mensaje=eliminado');
        exit;
    } else {
        header('Location: index.php?error=' . urlencode($resultado['error']));
        exit;
    }
}

if (isset($_GET['eliminar_materia'])) {
    $cedula = $_GET['cedula'];
    $materia_index = $_GET['eliminar_materia'];
    $resultado = eliminarMateriaEstudiante($cedula, $materia_index);
    if ($resultado['success']) {
        header('Location: index.php?mensaje=materia_eliminada');
        exit;
    } else {
        header('Location: index.php?error=' . urlencode($resultado['error']));
        exit;
    }
}

// Obtener datos
$estudiantes = obtenerEstudiantes();
$estadisticas = obtenerEstadisticas();

// Procesar mensajes
$mensaje = '';
$tipo_mensaje = 'success';
if (isset($_GET['mensaje'])) {
    switch ($_GET['mensaje']) {
        case 'agregado': $mensaje = '✅ Estudiante creado exitosamente'; break;
        case 'materia_agregada': $mensaje = '✅ Materia agregada exitosamente'; break;
        case 'editado': $mensaje = '✅ Estudiante actualizado exitosamente'; break;
        case 'eliminado': $mensaje = '✅ Estudiante eliminado exitosamente'; break;
        case 'materia_eliminada': $mensaje = '✅ Materia eliminada exitosamente'; break;
    }
}

if (isset($_GET['error'])) {
    $tipo_mensaje = 'danger';
    $mensaje = '❌ ' . htmlspecialchars($_GET['error']);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Gestión Estudiantil</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <!-- Fondo animado -->
    <div class="animated-bg">
        <div class="gradient-bg"></div>
        <div class="floating-shapes">
            <div class="shape"></div>
            <div class="shape"></div>
            <div class="shape"></div>
            <div class="shape"></div>
        </div>
    </div>

    <div class="container">
        <div class="header">
            <h1>🎓 Sistema Estudiantil</h1>
            <p>Gestión de estudiantes y calificaciones universitarias</p>
            <div class="api-status" style="margin-top: 1rem;">
                <span class="badge" style="background: var(--secondary-500);">
                    ✅ API Conectada
                </span>
            </div>
        </div>

        <?php if ($mensaje): ?>
            <div class="alert alert-<?php echo $tipo_mensaje; ?>">
                <?php echo $mensaje; ?>
            </div>
        <?php endif; ?>

        <!-- Estadísticas -->
        <?php if (!empty($estadisticas) && $estadisticas['total_estudiantes'] > 0): ?>
        <div class="card">
            <h3>📊 Estadísticas del Sistema</h3>
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-number"><?php echo $estadisticas['total_estudiantes']; ?></div>
                    <div class="stat-label">Total Estudiantes</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?php echo $estadisticas['total_materias']; ?></div>
                    <div class="stat-label">Total Materias</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?php echo $estadisticas['total_carreras']; ?></div>
                    <div class="stat-label">Carreras Diferentes</div>
                </div>
                <?php foreach (array_slice($estadisticas['calificaciones'], 0, 3) as $calif => $count): ?>
                    <?php if ($count > 0): ?>
                    <div class="stat-card">
                        <div class="stat-number">
                            <span class="badge badge-<?php echo $calif; ?>">
                                <?php echo $calif; ?>
                            </span>
                        </div>
                        <div class="stat-label"><?php echo $count; ?> materias</div>
                    </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <div class="actions">
            <a href="agregar.php" class="btn btn-primary">➕ Agregar Estudiante/Materia</a>
            <?php if (!empty($estadisticas) && $estadisticas['total_estudiantes'] > 0): ?>
                <span style="color: var(--text-muted); margin-left: auto;">
                    <?php echo $estadisticas['total_estudiantes']; ?> estudiantes · 
                    <?php echo $estadisticas['total_materias']; ?> materias
                </span>
            <?php endif; ?>
        </div>

        <div class="card">
            <h2>🎯 Lista de Estudiantes</h2>
            
            <?php if (empty($estudiantes)): ?>
                <div class="empty-state">
                    <div style="font-size: 4rem; margin-bottom: 1rem;">📚</div>
                    <h3>No hay estudiantes registrados</h3>
                    <p>Comienza agregando el primer estudiante al sistema.</p>
                    <a href="agregar.php" class="btn btn-primary">➕ Agregar Primer Estudiante</a>
                </div>
            <?php else: ?>
                <?php foreach ($estudiantes as $estudiante): ?>
                <div class="estudiante-card">
                    <div class="estudiante-header">
                        <div class="estudiante-info">
                            <h3><?php echo htmlspecialchars($estudiante['nombre'] . ' ' . $estudiante['apellido']); ?></h3>
                            <div class="estudiante-meta">
                                <strong>Cédula:</strong> <?php echo htmlspecialchars($estudiante['cedula']); ?> · 
                                <strong>Registro:</strong> <?php echo date('d/m/Y', strtotime($estudiante['fecha_registro'])); ?>
                            </div>
                            <?php 
                            $carreras = obtenerCarrerasEstudiante($estudiante['cedula']);
                            if (!empty($carreras)): 
                            ?>
                                <div style="margin-top: 0.5rem;">
                                    <strong>Carreras:</strong> 
                                    <?php foreach ($carreras as $carrera): ?>
                                        <span class="badge" style="background: var(--primary-600); margin-right: 0.5rem;">
                                            <?php echo htmlspecialchars($carrera); ?>
                                        </span>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="estudiante-actions">
                            <a href="agregar.php?cedula=<?php echo $estudiante['cedula']; ?>" class="btn btn-success btn-sm">
                                ➕ Materia
                            </a>
                            <a href="editar.php?id=<?php echo $estudiante['id']; ?>" class="btn btn-warning btn-sm">
                                ✏️ Editar
                            </a>
                            <a href="index.php?eliminar=<?php echo $estudiante['id']; ?>" class="btn btn-danger btn-sm"
                               onclick="return confirm('¿Estás seguro de eliminar a <?php echo htmlspecialchars($estudiante['nombre'] . ' ' . $estudiante['apellido']); ?>?')">
                                🗑️ Eliminar
                            </a>
                        </div>
                    </div>

                    <?php if (!empty($estudiante['materias'])): ?>
                    <div class="table-container">
                        <table>
                            <thead>
                                <tr>
                                    <th>Carrera</th>
                                    <th>Materia</th>
                                    <th>Calificación</th>
                                    <th>Fecha</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($estudiante['materias'] as $index => $materia): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($materia['carrera']); ?></td>
                                    <td><?php echo htmlspecialchars($materia['materia']); ?></td>
                                    <td>
                                        <span class="badge badge-<?php echo $materia['calificacion']; ?>">
                                            <?php echo $materia['calificacion']; ?>
                                        </span>
                                    </td>
                                    <td style="color: var(--text-muted);">
                                        <?php echo date('d/m/Y', strtotime($materia['fecha_registro'])); ?>
                                    </td>
                                    <td>
                                        <a href="index.php?eliminar_materia=<?php echo $index; ?>&cedula=<?php echo $estudiante['cedula']; ?>" 
                                           class="btn btn-danger btn-xs"
                                           onclick="return confirm('¿Estás seguro de eliminar la materia <?php echo htmlspecialchars($materia['materia']); ?>?')">
                                            🗑️
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php else: ?>
                        <div style="text-align: center; padding: 2rem; color: var(--text-muted);">
                            <p>Este estudiante no tiene materias registradas.</p>
                            <a href="agregar.php?cedula=<?php echo $estudiante['cedula']; ?>" class="btn btn-primary btn-sm">
                                ➕ Agregar Primera Materia
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>