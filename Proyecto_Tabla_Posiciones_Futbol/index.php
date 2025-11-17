<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Premier League - Tabla de Posiciones</title>
    <link rel="stylesheet" href="css/estilo.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>⚽ Premier League 2023/2024</h1>
            <p class="subtitle">Tabla de posiciones y estadísticas</p>
        </header>

        <div class="controls">
            <h2>Visualizaciones:</h2>
            <div class="button-group">
                <a href="?vista=tabla" class="btn btn-primary">📊 Tabla Completa</a>
                <a href="?vista=tabla_barras" class="btn btn-secondary">📈 Gráfico de Barras</a>
                <a href="?vista=goles" class="btn btn-success">🥅 Goles</a>
                <a href="?vista=efectividad" class="btn btn-warning">📊 Efectividad</a>
            </div>
        </div>

        <?php
        if (isset($_GET['vista'])) {
            $vista = $_GET['vista'];
            echo "<div class='content-container'>";
            
            if ($vista === 'tabla') {
                include 'generar_tabla.php';
            } else {
                include 'generar_grafico_futbol.php';
            }
            
            echo "</div>";
        }
        ?>

        <div class="info-box">
            <h3>📋 Leyenda:</h3>
            <div class="legend">
                <div class="legend-item">
                    <span class="color-champions"></span>
                    <span>Champions League</span>
                </div>
                <div class="legend-item">
                    <span class="color-europa"></span>
                    <span>Europa League</span>
                </div>
                <div class="legend-item">
                    <span class="color-conference"></span>
                    <span>Conference League</span>
                </div>
                <div class="legend-item">
                    <span class="color-descenso"></span>
                    <span>Descenso</span>
                </div>
            </div>
        </div>
    </div>
</body>
</html>