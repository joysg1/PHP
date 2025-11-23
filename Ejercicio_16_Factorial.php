<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cálculo de Factorial - Ejemplo Interactivo</title>
    <style>
        :root {
            --primary: #8b5cf6;
            --primary-dark: #7c3aed;
            --secondary: #06d6a0;
            --accent: #f59e0b;
            --dark-bg: #0f172a;
            --dark-surface: #1e293b;
            --dark-card: #334155;
            --text-primary: #f1f5f9;
            --text-secondary: #cbd5e1;
            --border-color: #475569;
            --success: #10b981;
            --warning: #f59e0b;
            --error: #ef4444;
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
            max-width: 1000px;
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

        .form-container {
            display: grid;
            grid-template-columns: 1fr auto;
            gap: 1rem;
            align-items: end;
        }

        @media (max-width: 768px) {
            .form-container {
                grid-template-columns: 1fr;
            }
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

        input {
            background: var(--dark-card);
            border: 1px solid var(--border-color);
            border-radius: 10px;
            padding: 12px 16px;
            color: var(--text-primary);
            font-size: 1.2rem;
            font-weight: bold;
            text-align: center;
            transition: all 0.3s ease;
        }

        input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.2);
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
            height: fit-content;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 7px 20px rgba(139, 92, 246, 0.4);
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
                grid-template-columns: 1fr 1fr;
            }
        }

        .factorial-result {
            background: linear-gradient(135deg, var(--dark-card), #2d3748);
            border-radius: 12px;
            padding: 2rem;
            text-align: center;
            border: 2px solid var(--primary);
        }

        .result-number {
            font-size: 3rem;
            font-weight: bold;
            background: linear-gradient(90deg, var(--secondary), var(--accent));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            margin: 1rem 0;
        }

        .calculation-steps {
            background: var(--dark-card);
            border-radius: 12px;
            padding: 1.5rem;
            max-height: 400px;
            overflow-y: auto;
        }

        .step-list {
            list-style: none;
            counter-reset: step-counter;
        }

        .step-item {
            padding: 1rem;
            margin-bottom: 0.5rem;
            background: rgba(139, 92, 246, 0.1);
            border-radius: 8px;
            border-left: 4px solid var(--primary);
            counter-increment: step-counter;
        }

        .step-item::before {
            content: counter(step-counter);
            background: var(--primary);
            color: white;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-right: 12px;
            font-size: 0.8rem;
            font-weight: bold;
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

        .visual-representation {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            justify-content: center;
            margin: 1.5rem 0;
            padding: 1rem;
            background: rgba(139, 92, 246, 0.1);
            border-radius: 10px;
        }

        .factorial-bubble {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 1.1rem;
            animation: bubblePop 0.5s ease-out;
        }

        .multiplication-sign {
            color: var(--accent);
            font-weight: bold;
            font-size: 1.5rem;
            display: flex;
            align-items: center;
        }

        @keyframes bubblePop {
            0% { transform: scale(0); opacity: 0; }
            70% { transform: scale(1.1); opacity: 1; }
            100% { transform: scale(1); opacity: 1; }
        }

        .explanation {
            background: linear-gradient(135deg, var(--dark-surface), var(--dark-card));
            border-radius: 16px;
            padding: 2rem;
            margin-top: 2rem;
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
            background: rgba(139, 92, 246, 0.1);
            border-radius: 10px;
            border-left: 4px solid var(--primary);
        }

        .feature-icon {
            color: var(--primary);
            font-weight: bold;
        }

        .error-message {
            background: var(--error);
            color: white;
            padding: 1rem;
            border-radius: 10px;
            margin: 1rem 0;
            text-align: center;
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
            
            .result-number {
                font-size: 2.5rem;
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
            
            .result-number {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <header class="header">
            <h1>🧮 Calculadora de Factorial</h1>
            <p>Comprende el concepto matemático del factorial mediante ejemplos interactivos</p>
        </header>

        <div class="card fade-in">
            <h2 class="card-title">
                <i>🔢</i> Ingresa un Número
            </h2>
            <form method="POST" action="">
                <div class="form-container">
                    <div class="form-group">
                        <label for="numero">Número a calcular (0-20):</label>
                        <input type="number" id="numero" name="numero" min="0" max="20" 
                               value="<?php echo isset($_POST['numero']) ? $_POST['numero'] : 5; ?>" 
                               required>
                    </div>
                    
                    <button type="submit" name="calcular" class="btn">
                        <i>🚀</i> Calcular Factorial
                    </button>
                </div>
            </form>
        </div>

        <?php
        function calcularFactorial($n) {
            if ($n < 0) return "Indefinido";
            if ($n == 0 || $n == 1) return 1;
            
            $resultado = 1;
            $pasos = [];
            
            for ($i = $n; $i >= 1; $i--) {
                $resultado_anterior = $resultado;
                $resultado *= $i;
                $pasos[] = [
                    'iteracion' => $n - $i + 1,
                    'operacion' => "$resultado_anterior × $i",
                    'resultado' => $resultado
                ];
            }
            
            return [
                'resultado' => $resultado,
                'pasos' => $pasos
            ];
        }

        if (isset($_POST['calcular'])) {
            $numero = intval($_POST['numero']);
            
            echo '<div class="result-section fade-in">';
            
            if ($numero < 0 || $numero > 20) {
                echo '<div class="error-message">';
                echo '⚠️ Por favor ingresa un número entre 0 y 20';
                echo '</div>';
            } else {
                $factorial = calcularFactorial($numero);
                
                echo '<div class="result-grid">';
                
                // Resultado principal
                echo '<div class="card">';
                echo '<h2 class="card-title"><i>🎯</i> Resultado</h2>';
                echo '<div class="factorial-result">';
                echo '<h3>Factorial de ' . $numero . '</h3>';
                echo '<div class="result-number">' . 
                     (is_array($factorial) ? number_format($factorial['resultado']) : $factorial) . 
                     '</div>';
                echo '<div style="color: var(--text-secondary);">';
                echo $numero . '! = ' . 
                     (is_array($factorial) ? implode(' × ', range($numero, 1)) : $factorial);
                echo '</div>';
                
                // Representación visual
                if ($numero <= 10 && is_array($factorial)) {
                    echo '<div class="visual-representation">';
                    $numeros = range($numero, 1);
                    foreach ($numeros as $index => $num) {
                        echo '<div class="factorial-bubble" style="animation-delay: ' . ($index * 0.1) . 's">';
                        echo $num;
                        echo '</div>';
                        if ($index < count($numeros) - 1) {
                            echo '<div class="multiplication-sign">×</div>';
                        }
                    }
                    echo '</div>';
                }
                echo '</div>';
                echo '</div>';
                
                // Pasos de cálculo
                if (is_array($factorial) && !empty($factorial['pasos'])) {
                    echo '<div class="card">';
                    echo '<h2 class="card-title"><i>📝</i> Pasos del Cálculo</h2>';
                    echo '<div class="calculation-steps">';
                    echo '<ul class="step-list">';
                    foreach ($factorial['pasos'] as $paso) {
                        echo '<li class="step-item">';
                        echo '<strong>' . $paso['operacion'] . '</strong> = ' . number_format($paso['resultado']);
                        echo '</li>';
                    }
                    echo '</ul>';
                    echo '</div>';
                    echo '</div>';
                }
                
                echo '</div>'; // cierre del result-grid
                
                // Información adicional
                echo '<div class="card">';
                echo '<h2 class="card-title"><i>ℹ️</i> Información del Cálculo</h2>';
                echo '<div class="execution-info">';
                echo '<div class="info-item"><span>🔢 Número ingresado:</span><strong>' . $numero . '</strong></div>';
                echo '<div class="info-item"><span>📊 Notación:</span><strong>' . $numero . '!</strong></div>';
                echo '<div class="info-item"><span>🔄 Iteraciones:</span><strong>' . $numero . '</strong></div>';
                echo '<div class="info-item"><span>📏 Dígitos:</span><strong>' . strlen((string)$factorial['resultado']) . '</strong></div>';
                
                echo '<div class="code-block">';
                echo '<h4 style="margin-bottom: 0.5rem; color: var(--text-secondary);">📝 Código PHP:</h4>';
                echo '<pre>';
                echo "function factorial(\$n) {\n";
                echo "    if (\$n <= 1) return 1;\n";
                echo "    \$resultado = 1;\n";
                echo "    for (\$i = \$n; \$i >= 1; \$i--) {\n";
                echo "        \$resultado *= \$i;\n";
                echo "    }\n";
                echo "    return \$resultado;\n";
                echo "}";
                echo '</pre>';
                echo '</div>';
                echo '</div>';
                echo '</div>';
            }
            echo '</div>'; // cierre del result-section
        }
        ?>
        
        <div class="explanation fade-in">
            <h3>🎓 ¿Qué es el Factorial?</h3>
            <div class="feature-list">
                <div class="feature-item">
                    <span class="feature-icon">📚</span>
                    <div>
                        <strong>Definición Matemática</strong>
                        <p>El factorial de un número n (n!) es el producto de todos los números enteros positivos desde 1 hasta n</p>
                    </div>
                </div>
                <div class="feature-item">
                    <span class="feature-icon">⚡</span>
                    <div>
                        <strong>Caso Base</strong>
                        <p>0! = 1 y 1! = 1 por definición matemática</p>
                    </div>
                </div>
                <div class="feature-item">
                    <span class="feature-icon">🔄</span>
                    <div>
                        <strong>Crecimiento Exponencial</strong>
                        <p>Los factoriales crecen extremadamente rápido (20! tiene 19 dígitos)</p>
                    </div>
                </div>
                <div class="feature-item">
                    <span class="feature-icon">🎯</span>
                    <div>
                        <strong>Aplicaciones</strong>
                        <p>Combinatoria, probabilidad, análisis de algoritmos y más</p>
                    </div>
                </div>
            </div>
            
            <div style="background: var(--dark-card); padding: 1.5rem; border-radius: 10px; margin-top: 1.5rem;">
                <h4 style="color: var(--primary); margin-bottom: 1rem;">💡 Ejemplos Prácticos:</h4>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                    <div style="text-align: center;">
                        <div style="font-size: 1.2rem; font-weight: bold; color: var(--secondary);">5!</div>
                        <div style="font-size: 0.9rem; color: var(--text-secondary);">5 × 4 × 3 × 2 × 1 = 120</div>
                    </div>
                    <div style="text-align: center;">
                        <div style="font-size: 1.2rem; font-weight: bold; color: var(--secondary);">7!</div>
                        <div style="font-size: 0.9rem; color: var(--text-secondary);">5,040 permutaciones</div>
                    </div>
                    <div style="text-align: center;">
                        <div style="font-size: 1.2rem; font-weight: bold; color: var(--secondary);">10!</div>
                        <div style="font-size: 0.9rem; color: var(--text-secondary);">3,628,800 combinaciones</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Efectos de interacción
        document.addEventListener('DOMContentLoaded', function() {
            const input = document.getElementById('numero');
            const btn = document.querySelector('.btn');
            
            input.addEventListener('focus', function() {
                this.style.transform = 'scale(1.02)';
            });
            
            input.addEventListener('blur', function() {
                this.style.transform = 'scale(1)';
            });
            
            btn.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-2px)';
            });
            
            btn.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
            });
            
            // Validación en tiempo real
            input.addEventListener('input', function() {
                const value = parseInt(this.value);
                if (value > 20) {
                    this.value = 20;
                } else if (value < 0) {
                    this.value = 0;
                }
            });
        });
    </script>
</body>
</html>