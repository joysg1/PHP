<?php
require_once 'funciones.php';

// Verificar API
if (!verificarAPI()) {
    die('Error: API no disponible');
}

$id = $_GET['id'] ?? '';
$estudiante = obtenerEstudiantePorId($id);

if (!$estudiante) {
    header('Location: index.php?error=Estudiante no encontrado');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $datos = [
        'nombre' => $_POST['nombre'],
        'apellido' => $_POST['apellido'],
        'cedula' => $_POST['cedula']
    ];
    
    $resultado = actualizarEstudiante($id, $datos);
    
    if ($resultado['success']) {
        header('Location: index.php?mensaje=editado');
        exit;
    } else {
        $error = $resultado['error'];
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar - Sistema Estudiantil</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
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
            <h1>✏️ Editar Estudiante</h1>
            <p>Modificar información del estudiante</p>
        </div>

        <div class="actions">
            <a href="index.php" class="btn btn-outline">← Volver al Dashboard</a>
            <a href="agregar.php?cedula=<?php echo $estudiante['cedula']; ?>" class="btn btn-success">➕ Agregar Materia</a>
        </div>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <div class="card">
            <form method="POST">
                <h3 style="margin-bottom: 1.5rem;">📝 Información Básica</h3>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="cedula">
                            <span style="color: var(--danger-500);">*</span> Cédula / Identificación
                        </label>
                        <input type="text" id="cedula" name="cedula" class="form-control" 
                               value="<?php echo htmlspecialchars($estudiante['cedula']); ?>" 
                               required
                               pattern="[0-9]{10,13}"
                               title="La cédula debe contener entre 10 y 13 dígitos">
                        <small style="color: var(--text-muted); display: block; margin-top: 0.5rem;">
                            🔒 Identificador único del estudiante
                        </small>
                    </div>
                    
                    <div class="form-group">
                        <label for="nombre">
                            <span style="color: var(--danger-500);">*</span> Nombre
                        </label>
                        <input type="text" id="nombre" name="nombre" class="form-control" 
                               value="<?php echo htmlspecialchars($estudiante['nombre']); ?>" 
                               required>
                    </div>
                    
                    <div class="form-group">
                        <label for="apellido">
                            <span style="color: var(--danger-500);">*</span> Apellido
                        </label>
                        <input type="text" id="apellido" name="apellido" class="form-control" 
                               value="<?php echo htmlspecialchars($estudiante['apellido']); ?>" 
                               required>
                    </div>
                </div>

                <div class="actions" style="justify-content: center; gap: 2rem; margin-top: 2rem;">
                    <button type="submit" class="btn btn-success" style="min-width: 200px;">
                        💾 Guardar Cambios
                    </button>
                    <a href="index.php" class="btn btn-outline" style="min-width: 200px;">
                        ❌ Cancelar
                    </a>
                </div>
            </form>
        </div>

        <?php if (!empty($estudiante['materias'])): ?>
        <div class="card">
            <h3>📚 Materias Registradas</h3>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Carrera</th>
                            <th>Materia</th>
                            <th>Calificación</th>
                            <th>Fecha</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($estudiante['materias'] as $materia): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($materia['carrera']); ?></td>
                            <td><?php echo htmlspecialchars($materia['materia']); ?></td>
                            <td>
                                <span class="badge badge-<?php echo $materia['calificacion']; ?>">
                                    <?php echo $materia['calificacion']; ?>
                                </span>
                            </td>
                            <td><?php echo date('d/m/Y', strtotime($materia['fecha_registro'])); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php endif; ?>
    </div>
</body>
</html>