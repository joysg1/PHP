<?php
require_once 'funciones.php';

// Verificar API
if (!verificarAPI()) {
    die('Error: API no disponible');
}

$estudiante_existente = null;
$cedula = isset($_GET['cedula']) ? $_GET['cedula'] : '';

if ($cedula) {
    $estudiante_existente = buscarEstudiantePorCedula($cedula);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $datos = [
        'cedula' => $_POST['cedula'],
        'carrera' => $_POST['carrera'],
        'materia' => $_POST['materia'],
        'calificacion' => $_POST['calificacion']
    ];
    
    if (!$estudiante_existente) {
        $datos['nombre'] = $_POST['nombre'];
        $datos['apellido'] = $_POST['apellido'];
    }
    
    $resultado = guardarEstudiante($datos);
    
    if ($resultado['success']) {
        $mensaje = $estudiante_existente ? 'materia_agregada' : 'agregado';
        header("Location: index.php?mensaje=$mensaje");
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
    <title>Agregar - Sistema Estudiantil</title>
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
            <h1><?php echo $estudiante_existente ? '📚 Agregar Materia' : '👨‍🎓 Nuevo Estudiante'; ?></h1>
            <p><?php echo $estudiante_existente ? 'Agregar materia a estudiante existente' : 'Registrar nuevo estudiante'; ?></p>
        </div>

        <div class="actions">
            <a href="index.php" class="btn btn-outline">← Volver al Dashboard</a>
        </div>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <?php if ($estudiante_existente): ?>
            <div class="alert alert-success">
                <h4>✅ Estudiante Encontrado</h4>
                <p><strong>Estudiante:</strong> <?php echo htmlspecialchars($estudiante_existente['nombre'] . ' ' . $estudiante_existente['apellido']); ?></p>
                <p><strong>Cédula:</strong> <?php echo htmlspecialchars($estudiante_existente['cedula']); ?></p>
                <p><strong>Total materias:</strong> <?php echo count($estudiante_existente['materias'] ?? []); ?></p>
            </div>
        <?php endif; ?>

        <div class="card">
            <form method="POST">
                <div class="form-row">
                    <div class="form-group">
                        <label for="cedula">
                            <span style="color: var(--danger-500);">*</span> Cédula / Identificación
                        </label>
                        <input type="text" id="cedula" name="cedula" class="form-control" 
                               value="<?php echo htmlspecialchars($cedula); ?>" 
                               required
                               placeholder="Ej: 1234567890"
                               onblur="if(this.value.length>=8) window.location='agregar.php?cedula='+this.value">
                        <small style="color: var(--text-muted); display: block; margin-top: 0.5rem;">
                            🔍 La cédula identifica al estudiante de forma única. Si existe, se cargarán sus datos.
                        </small>
                    </div>
                    
                    <?php if (!$estudiante_existente): ?>
                    <div class="form-group">
                        <label for="nombre">
                            <span style="color: var(--danger-500);">*</span> Nombre
                        </label>
                        <input type="text" id="nombre" name="nombre" class="form-control" 
                               value="<?php echo htmlspecialchars($_POST['nombre'] ?? ''); ?>" 
                               required
                               placeholder="Ej: María José">
                    </div>
                    
                    <div class="form-group">
                        <label for="apellido">
                            <span style="color: var(--danger-500);">*</span> Apellido
                        </label>
                        <input type="text" id="apellido" name="apellido" class="form-control" 
                               value="<?php echo htmlspecialchars($_POST['apellido'] ?? ''); ?>" 
                               required
                               placeholder="Ej: González Pérez">
                    </div>
                    <?php endif; ?>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="carrera">
                            <span style="color: var(--danger-500);">*</span> Carrera
                        </label>
                        <input type="text" id="carrera" name="carrera" class="form-control" 
                               value="<?php echo htmlspecialchars($_POST['carrera'] ?? ''); ?>" 
                               required
                               placeholder="Ej: Ingeniería en Sistemas"
                               list="carreras-list">
                        <datalist id="carreras-list">
                            <option value="Ingeniería en Sistemas">
                            <option value="Medicina">
                            <option value="Derecho">
                            <option value="Administración">
                            <option value="Psicología">
                            <option value="Contabilidad">
                            <option value="Arquitectura">
                        </datalist>
                    </div>
                    
                    <div class="form-group">
                        <label for="materia">
                            <span style="color: var(--danger-500);">*</span> Materia
                        </label>
                        <input type="text" id="materia" name="materia" class="form-control" 
                               value="<?php echo htmlspecialchars($_POST['materia'] ?? ''); ?>" 
                               required
                               placeholder="Ej: Programación Avanzada"
                               list="materias-list">
                        <datalist id="materias-list">
                            <option value="Programación I">
                            <option value="Base de Datos">
                            <option value="Estructuras de Datos">
                            <option value="Anatomía">
                            <option value="Derecho Civil">
                            <option value="Contabilidad General">
                        </datalist>
                    </div>
                </div>

                <div class="form-group">
                    <label for="calificacion">
                        <span style="color: var(--danger-500);">*</span> Calificación
                    </label>
                    <select id="calificacion" name="calificacion" class="form-control" required>
                        <option value="">Seleccione una calificación</option>
                        <option value="A" <?php echo ($_POST['calificacion'] ?? '') == 'A' ? 'selected' : ''; ?>>A - Excelente (90-100%)</option>
                        <option value="B" <?php echo ($_POST['calificacion'] ?? '') == 'B' ? 'selected' : ''; ?>>B - Muy Bueno (80-89%)</option>
                        <option value="C" <?php echo ($_POST['calificacion'] ?? '') == 'C' ? 'selected' : ''; ?>>C - Bueno (70-79%)</option>
                        <option value="D" <?php echo ($_POST['calificacion'] ?? '') == 'D' ? 'selected' : ''; ?>>D - Regular (60-69%)</option>
                        <option value="F" <?php echo ($_POST['calificacion'] ?? '') == 'F' ? 'selected' : ''; ?>>F - Reprobado (0-59%)</option>
                        <option value="I" <?php echo ($_POST['calificacion'] ?? '') == 'I' ? 'selected' : ''; ?>>I - Incompleto</option>
                        <option value="N" <?php echo ($_POST['calificacion'] ?? '') == 'N' ? 'selected' : ''; ?>>N - No presentado</option>
                    </select>
                    <small style="color: var(--text-muted); display: block; margin-top: 0.5rem;">
                        📊 Seleccione la calificación obtenida por el estudiante
                    </small>
                </div>

                <div class="actions" style="justify-content: center; gap: 2rem; margin-top: 2rem;">
                    <button type="submit" class="btn btn-success" style="min-width: 200px;">
                        <?php if ($estudiante_existente): ?>
                            💾 Agregar Materia
                        <?php else: ?>
                            👨‍🎓 Registrar Estudiante
                        <?php endif; ?>
                    </button>
                    <a href="index.php" class="btn btn-outline" style="min-width: 200px;">
                        ❌ Cancelar
                    </a>
                </div>
            </form>
        </div>

        <!-- Información de ayuda -->
        <div class="card">
            <h3>📋 Información Importante</h3>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem; margin-top: 1rem;">
                <div>
                    <h4 style="color: var(--primary-500); margin-bottom: 0.5rem;">Sobre la Cédula</h4>
                    <p style="color: var(--text-muted); margin: 0;">
                        La cédula es el identificador único del estudiante. Si el estudiante ya existe, 
                        se cargarán sus datos automáticamente.
                    </p>
                </div>
                <div>
                    <h4 style="color: var(--secondary-500); margin-bottom: 0.5rem;">Múltiples Carreras</h4>
                    <p style="color: var(--text-muted); margin: 0;">
                        Un estudiante puede estar en múltiples carreras simultáneamente. 
                        Cada materia se registra con su carrera correspondiente.
                    </p>
                </div>
                <div>
                    <h4 style="color: var(--accent-500); margin-bottom: 0.5rem;">Sistema de Calificaciones</h4>
                    <p style="color: var(--text-muted); margin: 0;">
                        Las calificaciones van de A (Excelente) a F (Reprobado), 
                        más I (Incompleto) y N (No presentado).
                    </p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>