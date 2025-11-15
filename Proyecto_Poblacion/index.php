<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Población Mundial - Gráficos con PHP y Python</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f5f5f5;
            color: #333;
            line-height: 1.6;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem 0;
            text-align: center;
            border-radius: 10px;
            margin-bottom: 2rem;
        }
        
        h1 {
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
        }
        
        .subtitle {
            font-size: 1.2rem;
            opacity: 0.9;
        }
        
        .controls {
            background: white;
            padding: 1.5rem;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
            text-align: center;
        }
        
        .btn {
            background: #667eea;
            color: white;
            border: none;
            padding: 12px 24px;
            margin: 0 10px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1rem;
            transition: background 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }
        
        .btn:hover {
            background: #764ba2;
        }
        
        .chart-container {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
            text-align: center;
        }
        
        .chart-title {
            color: #333;
            margin-bottom: 1rem;
            font-size: 1.5rem;
        }
        
        .chart-image {
            max-width: 100%;
            height: auto;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        .data-table {
            background: white;
            padding: 1.5rem;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        
        th {
            background-color: #667eea;
            color: white;
        }
        
        tr:hover {
            background-color: #f5f5f5;
        }
        
        footer {
            text-align: center;
            margin-top: 2rem;
            color: #666;
        }
        
        .info-box {
            background: #e7f3ff;
            border-left: 4px solid #667eea;
            padding: 1rem;
            margin: 1rem 0;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>🌍 Población Mundial 2023</h1>
            <p class="subtitle">Visualización de datos con PHP + Python + Seaborn</p>
        </header>

        <div class="controls">
            <h2>Selecciona el tipo de gráfico:</h2>
            <div style="margin-top: 1rem;">
                <a href="?grafico=barras" class="btn">📊 Gráfico de Barras</a>
                <a href="?grafico=torta" class="btn">🥧 Gráfico de Torta</a>
                <a href="?grafico=continentes" class="btn">🌎 Por Continentes</a>
                <a href="?" class="btn">🔄 Limpiar</a>
            </div>
        </div>

        <?php
        // Mostrar gráfico si se seleccionó uno
        if (isset($_GET['grafico'])) {
            $tipo_grafico = $_GET['grafico'];
            echo "<div class='chart-container'>";
            
            // Incluir el script que genera el gráfico
            include 'generar_grafico.php';
            
            echo "</div>";
        }
        ?>

        <div class="data-table">
            <h2>📋 Datos de Población Mundial (Top 10)</h2>
            <div class="info-box">
                <strong>💡 Información:</strong> Estos son los 10 países más poblados del mundo en 2023.
            </div>
            
            <?php
            // Mostrar tabla con datos
            $datos_csv = file('datos/poblacion_mundial.csv');
            if ($datos_csv) {
                echo "<table>";
                // Encabezados
                echo "<tr>";
                $encabezados = str_getcsv($datos_csv[0]);
                foreach ($encabezados as $encabezado) {
                    echo "<th>" . ucfirst($encabezado) . "</th>";
                }
                echo "</tr>";
                
                // Datos
                for ($i = 1; $i < count($datos_csv); $i++) {
                    $fila = str_getcsv($datos_csv[$i]);
                    echo "<tr>";
                    foreach ($fila as $dato) {
                        if (is_numeric($dato)) {
                            // Formatear números con separadores de miles
                            echo "<td>" . number_format($dato) . "</td>";
                        } else {
                            echo "<td>" . $dato . "</td>";
                        }
                    }
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                echo "<p>No se pudieron cargar los datos.</p>";
            }
            ?>
        </div>

        <div class="info-box">
            <h3>🔧 Tecnologías utilizadas:</h3>
            <ul style="list-style-position: inside; margin-top: 0.5rem;">
                <li><strong>PHP:</strong> Para la lógica web y servidor</li>
                <li><strong>Python:</strong> Para el análisis de datos</li>
                <li><strong>Seaborn:</strong> Para crear gráficos estadísticos</li>
                <li><strong>Matplotlib:</strong> Para visualizaciones</li>
                <li><strong>Pandas:</strong> Para manipulación de datos</li>
            </ul>
        </div>

        <footer>
            <p>Proyecto de aprendizaje - PHP + Python + Seaborn</p>
        </footer>
    </div>
</body>
</html>
