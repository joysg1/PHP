<?php
require_once 'config.php';

$db = new JSONDatabase();
$animales = $db->getAllAnimals();
$stats = $db->getAnimalStats();

// Ordenar animales por fecha de registro (más recientes primero)
usort($animales, function($a, $b) {
    $timeA = isset($a['fecha_registro']) ? strtotime($a['fecha_registro']) : 0;
    $timeB = isset($b['fecha_registro']) ? strtotime($b['fecha_registro']) : 0;
    return $timeB - $timeA;
});
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Base de Datos de Animales - JSON</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: 'Poppins', sans-serif; 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh; 
            padding: 20px;
        }
        .container { max-width: 1200px; margin: 0 auto; }
        .header { 
            text-align: center; 
            margin-bottom: 30px; 
            color: white; 
        }
        .header h1 { 
            font-size: 2.5rem; 
            margin-bottom: 10px; 
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }
        .stats-bar {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
            margin-bottom: 30px;
        }
        .stat-item {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            border-radius: 10px;
            padding: 15px;
            color: white;
            text-align: center;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        .stat-number {
            font-size: 1.5rem;
            font-weight: bold;
        }
        .table-container { 
            background: white; 
            border-radius: 20px; 
            padding: 30px; 
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            overflow-x: auto;
            margin-bottom: 30px;
        }
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin: 20px 0;
        }
        th, td { 
            padding: 15px; 
            text-align: left; 
            border-bottom: 1px solid #e1e5ee;
        }
        th { 
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white; 
            font-weight: 600;
            position: sticky;
            top: 0;
        }
        tr:hover { background: #f8f9fa; }
        .badge { 
            padding: 5px 10px; 
            border-radius: 15px; 
            font-size: 0.8rem; 
            font-weight: 600;
        }
        .badge-domestico { background: #e8f5e8; color: #2e7d32; }
        .badge-salvaje { background: #ffebee; color: #c62828; }
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
        .btn-secondary { background: #6c757d; }
        .characteristics { 
            display: flex; 
            flex-wrap: wrap; 
            gap: 5px; 
            max-width: 200px;
        }
        .char-badge { 
            background: #e3f2fd; 
            color: #1976d2; 
            padding: 2px 8px; 
            border-radius: 10px; 
            font-size: 0.7rem;
        }
        .empty-state { 
            text-align: center; 
            padding: 40px; 
            color: #666;
        }
        .json-view {
            background: #2d3748;
            color: #e2e8f0;
            padding: 20px;
            border-radius: 10px;
            font-family: 'Courier New', monospace;
            margin: 20px 0;
            max-height: 400px;
            overflow-y: auto;
        }
        .section {
            background: white;
            border-radius: 15px;
            padding: 25px;
            margin: 25px 0;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .feature-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
            gap: 10px;
            margin-top: 15px;
        }
        .feature-stat {
            background: #f8f9fa;
            padding: 10px;
            border-radius: 8px;
            text-align: center;
            border-left: 4px solid #667eea;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🐾 Base de Datos JSON de Animales</h1>
            <p>Sistema de almacenamiento local - Archivo: animales.json</p>
        </div>

        <!-- Barra de estadísticas -->
        <div class="stats-bar">
            <div class="stat-item">
                <div class="stat-number"><?php echo $stats['total']; ?></div>
                <div>Total Animales</div>
            </div>
            <div class="stat-item">
                <div class="stat-number"><?php echo $stats['domesticos']; ?></div>
                <div>Domésticos</div>
            </div>
            <div class="stat-item">
                <div class="stat-number"><?php echo $stats['salvajes']; ?></div>
                <div>Salvajes</div>
            </div>
            <div class="stat-item">
                <div class="stat-number"><?php echo count($stats['por_caracteristica']); ?></div>
                <div>Características Únicas</div>
            </div>
        </div>

        <!-- Estadísticas de características -->
        <?php if (!empty($stats['por_caracteristica'])): ?>
        <div class="section">
            <h2>📊 Distribución por Características</h2>
            <div class="feature-stats">
                <?php foreach ($stats['por_caracteristica'] as $caracteristica => $cantidad): ?>
                <div class="feature-stat">
                    <div style="font-size: 1.2rem; font-weight: bold;"><?php echo $cantidad; ?></div>
                    <div style="font-size: 0.8rem;"><?php echo ucfirst($caracteristica); ?></div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- Tabla de animales -->
        <div class="table-container">
            <h2 style="color: #333; margin-bottom: 20px;">📋 Todos los Animales Registrados</h2>
            
            <?php if (count($animales) > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Tipo</th>
                            <th>Especie</th>
                            <th>Edad</th>
                            <th>Hábitat</th>
                            <th>Características</th>
                            <th>Fecha Registro</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($animales as $animal): ?>
                        <tr>
                            <td><strong>#<?php echo isset($animal['id']) ? $animal['id'] : 'N/A'; ?></strong></td>
                            <td><?php echo isset($animal['nombre']) ? $animal['nombre'] : 'N/A'; ?></td>
                            <td>
                                <?php if (isset($animal['tipo'])): ?>
                                <span class="badge <?php echo $animal['tipo'] == 'domestico' ? 'badge-domestico' : 'badge-salvaje'; ?>">
                                    <?php echo $animal['tipo'] == 'domestico' ? '🐕 Doméstico' : '🐅 Salvaje'; ?>
                                </span>
                                <?php else: ?>
                                <span class="badge" style="background: #ccc; color: #666;">N/A</span>
                                <?php endif; ?>
                            </td>
                            <td><em><?php echo isset($animal['especie']) ? $animal['especie'] : 'N/A'; ?></em></td>
                            <td><?php echo isset($animal['edad']) ? $animal['edad'] : 'N/A'; ?> años</td>
                            <td><?php echo isset($animal['habitat']) ? $animal['habitat'] : 'N/A'; ?></td>
                            <td>
                                <div class="characteristics">
                                    <?php if (isset($animal['caracteristicas']) && is_array($animal['caracteristicas']) && !empty($animal['caracteristicas'])): ?>
                                        <?php foreach ($animal['caracteristicas'] as $char): ?>
                                            <span class="char-badge"><?php echo ucfirst($char); ?></span>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <span style="color: #666; font-size: 0.8rem;">Ninguna</span>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td>
                                <?php 
                                if (isset($animal['fecha_registro'])) {
                                    echo date('d/m/Y H:i', strtotime($animal['fecha_registro']));
                                } else {
                                    echo 'N/A';
                                }
                                ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="empty-state">
                    <i class="fas fa-database" style="font-size: 4rem; color: #ccc; margin-bottom: 20px;"></i>
                    <h3>La base de datos está vacía</h3>
                    <p>No hay animales registrados todavía.</p>
                    <a href="formulario_animales.php" class="btn" style="margin-top: 20px;">
                        <i class="fas fa-plus"></i> Registrar Primer Animal
                    </a>
                </div>
            <?php endif; ?>
        </div>

        <!-- Vista JSON (opcional) -->
        <?php if (count($animales) > 0): ?>
        <div class="section">
            <h2>🔍 Vista JSON de la Base de Datos</h2>
            <div class="json-view">
                <pre><?php 
                $db_data = $db->getData();
                echo htmlspecialchars(json_encode($db_data, JSON_PRETTY_PRINT)); 
                ?></pre>
            </div>
        </div>
        <?php endif; ?>

        <div style="text-align: center;">
            <a href="formulario_animales.php" class="btn">
                <i class="fas fa-arrow-left"></i> Volver al Formulario
            </a>
            <a href="procesar_animales.php?action=export" class="btn btn-secondary">
                <i class="fas fa-download"></i> Exportar Reporte
            </a>
        </div>
    </div>
</body>
</html>