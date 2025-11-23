<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ciclos For Anidados - Ejemplo Interactivo</title>
    <style>
        :root {
            --primary: #6366f1;
            --primary-dark: #4f46e5;
            --secondary: #10b981;
            --dark-bg: #0f172a;
            --dark-surface: #1e293b;
            --dark-card: #334155;
            --text-primary: #f1f5f9;
            --text-secondary: #cbd5e1;
            --border-color: #475569;
            --success: #22c55e;
            --warning: #f59e0b;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            background: linear-gradient(135deg, var(--dark-bg) 0%, #1e1b4b 100%);
            color: var(--text-primary);
            line-height: 1.6;
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .header {
            text-align: center;
            margin-bottom: 2rem;
            padding: 2rem 0;
        }

        .header h1 {
            font-size: 2.5rem;
            background: linear-gradient(90deg, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            margin-bottom: 0.5rem;
        }

        .header p {
            color: var(--text-secondary);
            font-size: 1.1rem;
        }

        .card {
            background: var(--dark-surface);
            border-radius: 16px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
            border: 1px solid var(--border-color);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.4);
        }

        .card-title {
            font-size: 1.5rem;
            margin-bottom: 1.5rem;
            color: var(--text-primary);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .card-title i {
            color: var(--primary);
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        label {
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: var(--text-secondary);
        }

        input, select {
            background: var(--dark-card);
            border: 1px solid var(--border-color);
            border-radius: 10px;
            padding: 12px 16px;
            color: var(--text-primary);
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
            border-radius: 10px;
            padding: 14px 28px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 7px 20px rgba(99, 102, 241, 0.4);
        }

        .btn:active {
            transform: translateY(0);
        }

        .result-section {
            margin-top: 2rem;
        }

        .result-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }

        @media (min-width: 768px) {
            .result-grid {
                grid-template-columns: 2fr 1fr;
            }
        }

        .table-container {
            overflow-x: auto;
            border-radius: 12px;
            background: var(--dark-card);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 400px;
        }

        th {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            padding: 1rem;
            text-align: center;
            font-weight: 600;
        }

        td {
            padding: 1rem;
            text-align: center;
            border-bottom: 1px solid var(--border-color);
            transition: background-color 0.2s ease;
        }

        tr:hover td {
            background-color: rgba(99, 102, 241, 0.1);
        }

        .execution-info {
            background: var(--dark-card);
            border-radius: 12px;
            padding: 1.5rem;
        }

        .info-item {
            display: flex;
            justify-content: space-between;
            padding: 0.75rem 0;
            border-bottom: 1px solid var(--border-color);
        }

        .info-item:last-child {
            border-bottom: none;
        }

        .code-block {
            background: #1a1f36;
            border-radius: 8px;
            padding: 1.5rem;
            margin-top: 1rem;
            overflow-x: auto;
            border-left: 4px solid var(--primary);
        }

        .code-block pre {
            color: var(--text-primary);
            font-family: 'Consolas', 'Monaco', monospace;
            line-height: 1.5;
        }

        .pattern-output {
            background: var(--dark-card);
            border-radius: 12px;
            padding: 1.5rem;
            font-family: 'Consolas', 'Monaco', monospace;
            line-height: 1.8;
        }

        .explanation {
            background: linear-gradient(135deg, var(--dark-surface), var(--dark-card));
            border-radius: 16px;
            padding: 2rem;
            margin-top: 2rem;
        }

        .explanation h3 {
            margin-bottom: 1rem;
            color: var(--text-primary);
        }

        .feature-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1rem;
            margin: 1.5rem 0;
        }

        .feature-item {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 1rem;
            background: rgba(99, 102, 241, 0.1);
            border-radius: 10px;
            border-left: 4px solid var(--primary);
        }

        .feature-icon {
            color: var(--primary);
            font-weight: bold;
        }

        .iterations-counter {
            background: linear-gradient(135deg, var(--secondary), #059669);
            color: white;
            padding: 1rem;
            border-radius: 10px;
            text-align: center;
            margin: 1rem 0;
            font-weight: 600;
        }

        /* Animaciones */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .fade-in {
            animation: fadeIn 0.5s ease-out;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .header h1 {
                font-size: 2rem;
            }
            
            .card {
                padding: 1.5rem;
            }
            
            .form-grid {
                grid-template-columns: 1fr;
            }
            
            .feature-list {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 480px) {
            body {
                padding: 10px;
            }
            
            .header h1 {
                font-size: 1.75rem;
            }
            
            .card {
                padding: 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <header class="header">
            <h1>🔁 Ciclos For Anidados</h1>
            <p>Visualiza y comprende el funcionamiento de ciclos anidados de forma interactiva</p>
        </header>

        <div class="card fade-in">
            <h2 class="card-title">
                <i>⚙️</i> Configuración de Parámetros
            </h2>
            <form method="POST" action="">
                <div class="form-grid">
                    <div class="form-group">
                        <label for="filas">📊 Número de Filas</label>
                        <input type="number" id="filas" name="filas" min="1" max="15" 
                               value="<?php echo isset($_POST['filas']) ? $_POST['filas'] : 5; ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="columnas">📈 Número de Columnas</label>
                        <input type="number" id="columnas" name="columnas" min="1" max="15" 
                               value="<?php echo isset($_POST['columnas']) ? $_POST['columnas'] : 5; ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="ejemplo">🎯 Tipo de Ejemplo</label>
                        <select name="ejemplo" id="ejemplo">
                            <option value="tabla_multiplicar" <?php echo (isset($_POST['ejemplo']) && $_POST['ejemplo'] == 'tabla_multiplicar') ? 'selected' : ''; ?>>
                                🧮 Tabla de Multiplicar
                            </option>
                            <option value="patron_numeros" <?php echo (isset($_POST['ejemplo']) && $_POST['ejemplo'] == 'patron_numeros') ? 'selected' : ''; ?>>
                                🔢 Patrón de Números
                            </option>
                            <option value="coordenadas" <?php echo (isset($_POST['ejemplo']) && $_POST['ejemplo'] == 'coordenadas') ? 'selected' : ''; ?>>
                                📍 Coordenadas
                            </option>
                        </select>
                    </div>
                </div>
                
                <button type="submit" name="ejecutar" class="btn">
                    <i>🚀</i> Ejecutar Ciclos
                </button>
            </form>
        </div>

        <?php
        if (isset($_POST['ejecutar'])) {
            $filas = intval($_POST['filas']);
            $columnas = intval($_POST['columnas']);
            $ejemplo = $_POST['ejemplo'];
            $total_iteraciones = $filas * $columnas;
            
            echo '<div class="result-section fade-in">';
            echo '<div class="iterations-counter">';
            echo "🔄 Total de Iteraciones: <strong>$total_iteraciones</strong> ($filas filas × $columnas columnas)";
            echo '</div>';
            
            echo '<div class="result-grid">';
            echo '<div class="card">';
            echo '<h2 class="card-title"><i>📊</i> Resultado Visual</h2>';
            
            switch ($ejemplo) {
                case 'tabla_multiplicar':
                    echo '<h3 style="margin-bottom: 1rem; color: var(--text-secondary);">🧮 Tabla de Multiplicar</h3>';
                    echo '<div class="table-container">';
                    echo '<table>';
                    // Encabezado de columnas
                    echo '<tr><th style="background: linear-gradient(135deg, var(--secondary), #059669);">×</th>';
                    for ($j = 1; $j <= $columnas; $j++) {
                        echo "<th>$j</th>";
                    }
                    echo '</tr>';
                    
                    // Filas de la tabla
                    for ($i = 1; $i <= $filas; $i++) {
                        echo '<tr>';
                        echo '<th style="background: linear-gradient(135deg, var(--secondary), #059669);">' . $i . '</th>';
                        for ($j = 1; $j <= $columnas; $j++) {
                            $resultado = $i * $j;
                            $color_intensity = min(100 + ($resultado * 10), 255);
                            echo "<td style='background: rgba(99, 102, 241, " . ($resultado / 100) . ");'><strong>$resultado</strong></td>";
                        }
                        echo '</tr>';
                    }
                    echo '</table>';
                    echo '</div>';
                    break;
                    
                case 'patron_numeros':
                    echo '<h3 style="margin-bottom: 1rem; color: var(--text-secondary);">🔢 Patrón de Números</h3>';
                    echo '<div class="pattern-output">';
                    for ($i = 1; $i <= $filas; $i++) {
                        for ($j = 1; $j <= $i; $j++) {
                            echo "<span style='color: var(--primary); font-weight: bold;'>$j </span>";
                        }
                        echo "<br>";
                    }
                    echo '</div>';
                    break;
                    
                case 'coordenadas':
                    echo '<h3 style="margin-bottom: 1rem; color: var(--text-secondary);">📍 Coordenadas (Fila, Columna)</h3>';
                    echo '<div class="table-container">';
                    echo '<table>';
                    for ($i = 1; $i <= $filas; $i++) {
                        echo '<tr>';
                        for ($j = 1; $j <= $columnas; $j++) {
                            $color_class = ($i + $j) % 2 == 0 ? 'var(--primary)' : 'var(--secondary)';
                            echo "<td style='color: $color_class;'><strong>($i, $j)</strong></td>";
                        }
                        echo '</tr>';
                    }
                    echo '</table>';
                    echo '</div>';
                    break;
            }
            
            echo '</div>'; // cierre del card de resultados
            
            // Panel de información
            echo '<div class="card">';
            echo '<h2 class="card-title"><i>ℹ️</i> Información del Ciclo</h2>';
            echo '<div class="execution-info">';
            echo '<div class="info-item"><span>📏 Filas:</span><strong>' . $filas . '</strong></div>';
            echo '<div class="info-item"><span>📐 Columnas:</span><strong>' . $columnas . '</strong></div>';
            echo '<div class="info-item"><span>🔄 Iteraciones totales:</span><strong>' . $total_iteraciones . '</strong></div>';
            echo '<div class="info-item"><span>⚡ Ejemplo:</span><strong>' . ucfirst(str_replace('_', ' ', $ejemplo)) . '</strong></div>';
            
            echo '<div class="code-block">';
            echo '<h4 style="margin-bottom: 0.5rem; color: var(--text-secondary);">📝 Estructura del Código:</h4>';
            echo '<pre>';
            echo "for (\$i = 1; \$i <= $filas; \$i++) {\n";
            echo "    for (\$j = 1; \$j <= $columnas; \$j++) {\n";
            echo "        // Tu código aquí\n";
            echo "        echo \"Iteración: \$i - \$j\";\n";
            echo "    }\n";
            echo "}";
            echo '</pre>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
            echo '</div>'; // cierre del result-grid
            echo '</div>'; // cierre del result-section
        }
        ?>
        
        <div class="explanation fade-in">
            <h3>🎓 ¿Cómo funcionan los ciclos for anidados?</h3>
            <div class="feature-list">
                <div class="feature-item">
                    <span class="feature-icon">🔄</span>
                    <div>
                        <strong>Ciclo Externo</strong>
                        <p>Controla las filas y se ejecuta una vez por cada fila completa</p>
                    </div>
                </div>
                <div class="feature-item">
                    <span class="feature-icon">⚡</span>
                    <div>
                        <strong>Ciclo Interno</strong>
                        <p>Se ejecuta completamente en cada iteración del ciclo externo</p>
                    </div>
                </div>
                <div class="feature-item">
                    <span class="feature-icon">🧮</span>
                    <div>
                        <strong>Total de Iteraciones</strong>
                        <p>Calculado como: filas × columnas</p>
                    </div>
                </div>
                <div class="feature-item">
                    <span class="feature-icon">📊</span>
                    <div>
                        <strong>Patrón de Ejecución</strong>
                        <p>El ciclo interno termina completamente antes de que el externo avance</p>
                    </div>
                </div>
            </div>
            
            <div style="background: var(--dark-card); padding: 1.5rem; border-radius: 10px; margin-top: 1.5rem;">
                <h4 style="color: var(--primary); margin-bottom: 1rem;">💡 Ejemplo Práctico:</h4>
                <p>Con <strong>3 filas</strong> y <strong>4 columnas</strong>:</p>
                <ul style="margin-top: 0.5rem; padding-left: 1.5rem;">
                    <li>El ciclo externo se ejecuta <strong>3 veces</strong></li>
                    <li>El ciclo interno se ejecuta <strong>4 veces por cada ciclo externo</strong></li>
                    <li>Total: <strong>3 × 4 = 12 iteraciones</strong></li>
                </ul>
            </div>
        </div>
    </div>

    <script>
        // Efectos de interacción adicionales
        document.addEventListener('DOMContentLoaded', function() {
            const inputs = document.querySelectorAll('input, select');
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.parentElement.style.transform = 'scale(1.02)';
                });
                
                input.addEventListener('blur', function() {
                    this.parentElement.style.transform = 'scale(1)';
                });
            });
            
            // Efecto de aparición secuencial
            const cards = document.querySelectorAll('.card');
            cards.forEach((card, index) => {
                card.style.animationDelay = `${index * 0.1}s`;
            });
        });
    </script>
</body>
</html>