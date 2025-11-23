<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Funciones en PHP - Ejemplo Interactivo</title>
    <style>
        :root {
            --primary: #3b82f6;
            --primary-dark: #2563eb;
            --secondary: #06d6a0;
            --accent: #f59e0b;
            --warning: #eab308;
            --danger: #ef4444;
            --dark-bg: #0f172a;
            --dark-surface: #1e293b;
            --dark-card: #334155;
            --text-primary: #f1f5f9;
            --text-secondary: #cbd5e1;
            --border-color: #475569;
            --success: #10b981;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            background: linear-gradient(135deg, var(--dark-bg) 0%, #1e3a8a 100%);
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

        .function-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .function-card {
            background: var(--dark-card);
            border-radius: 12px;
            padding: 1.5rem;
            border: 2px solid transparent;
            transition: all 0.3s ease;
            cursor: pointer;
            position: relative;
            overflow: hidden;
        }

        .function-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(59, 130, 246, 0.1), transparent);
            transition: left 0.5s ease;
        }

        .function-card:hover::before {
            left: 100%;
        }

        .function-card:hover {
            border-color: var(--primary);
            transform: translateY(-3px);
        }

        .function-card.active {
            border-color: var(--secondary);
            background: rgba(6, 214, 160, 0.1);
        }

        .function-icon {
            font-size: 2rem;
            margin-bottom: 1rem;
        }

        .function-name {
            font-size: 1.2rem;
            font-weight: bold;
            margin-bottom: 0.5rem;
            color: var(--primary);
        }

        .function-desc {
            color: var(--text-secondary);
            font-size: 0.9rem;
            margin-bottom: 1rem;
        }

        .function-params {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-top: 1rem;
        }

        .param-tag {
            background: rgba(59, 130, 246, 0.2);
            color: var(--primary);
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            border: 1px solid var(--primary);
        }

        .params-container {
            background: var(--dark-card);
            border-radius: 12px;
            padding: 2rem;
            margin: 1.5rem 0;
            border: 2px solid var(--border-color);
        }

        .params-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
        }

        .param-group {
            display: flex;
            flex-direction: column;
        }

        .param-header {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 0.75rem;
        }

        .param-icon {
            color: var(--accent);
            font-weight: bold;
        }

        .param-label {
            font-weight: 600;
            color: var(--text-secondary);
            margin-bottom: 0.5rem;
        }

        .param-hint {
            font-size: 0.8rem;
            color: var(--text-secondary);
            margin-top: 0.25rem;
            font-style: italic;
        }

        input, select, textarea {
            background: var(--dark-surface);
            border: 1px solid var(--border-color);
            border-radius: 10px;
            padding: 12px 16px;
            color: var(--text-primary);
            font-size: 1rem;
            transition: all 0.3s ease;
            width: 100%;
        }

        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
            transform: scale(1.02);
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
            box-shadow: 0 7px 20px rgba(59, 130, 246, 0.4);
        }

        .btn:active {
            transform: translateY(0);
        }

        .btn-secondary {
            background: linear-gradient(135deg, var(--secondary), #059669);
        }

        .btn-warning {
            background: linear-gradient(135deg, var(--warning), #ca8a04);
        }

        .btn-group {
            display: flex;
            gap: 1rem;
            margin-top: 1.5rem;
            flex-wrap: wrap;
        }

        .result-section {
            margin-top: 2rem;
        }

        .execution-flow {
            background: var(--dark-card);
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .flow-step {
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
            padding: 1rem;
            background: rgba(59, 130, 246, 0.1);
            border-radius: 8px;
            border-left: 4px solid var(--primary);
            transition: all 0.3s ease;
        }

        .flow-step:hover {
            transform: translateX(5px);
            background: rgba(59, 130, 246, 0.2);
        }

        .step-number {
            background: var(--primary);
            color: white;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            margin-right: 1rem;
            flex-shrink: 0;
        }

        .code-preview {
            background: #1a1f36;
            border-radius: 12px;
            padding: 1.5rem;
            margin-top: 1rem;
            overflow-x: auto;
            border-left: 4px solid var(--primary);
        }

        .code-preview pre {
            color: var(--text-primary);
            font-family: 'Consolas', 'Monaco', monospace;
            line-height: 1.5;
            font-size: 0.9rem;
        }

        .result-display {
            background: linear-gradient(135deg, var(--dark-card), #2d3748);
            border-radius: 12px;
            padding: 2rem;
            text-align: center;
            border: 2px solid var(--secondary);
            margin: 1.5rem 0;
        }

        .result-value {
            font-size: 2.5rem;
            font-weight: bold;
            background: linear-gradient(90deg, var(--secondary), var(--accent));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            margin: 1rem 0;
        }

        .function-signature {
            background: rgba(59, 130, 246, 0.1);
            padding: 1rem;
            border-radius: 8px;
            margin: 1rem 0;
            font-family: 'Consolas', 'Monaco', monospace;
            border-left: 4px solid var(--primary);
        }

        .param-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin: 1rem 0;
        }

        .param-item {
            background: rgba(59, 130, 246, 0.1);
            padding: 1rem;
            border-radius: 8px;
            text-align: center;
            transition: all 0.3s ease;
        }

        .param-item:hover {
            transform: scale(1.05);
            background: rgba(59, 130, 246, 0.2);
        }

        .param-name {
            font-weight: bold;
            color: var(--primary);
        }

        .param-value {
            color: var(--secondary);
            font-size: 1.2rem;
            margin-top: 0.5rem;
            word-break: break-all;
        }

        .explanation {
            background: linear-gradient(135deg, var(--dark-surface), var(--dark-card));
            border-radius: 16px;
            padding: 2rem;
            margin-top: 2rem;
        }

        .feature-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1rem;
            margin: 1.5rem 0;
        }

        .feature-item {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 1rem;
            background: rgba(59, 130, 246, 0.1);
            border-radius: 10px;
            border-left: 4px solid var(--primary);
        }

        .feature-icon {
            color: var(--primary);
            font-weight: bold;
        }

        .syntax-example {
            background: var(--dark-card);
            border-radius: 10px;
            padding: 1.5rem;
            margin: 1rem 0;
            border: 1px solid var(--border-color);
        }

        .function-call {
            background: rgba(6, 214, 160, 0.1);
            padding: 1rem;
            border-radius: 8px;
            margin: 1rem 0;
            font-family: 'Consolas', 'Monaco', monospace;
            border-left: 4px solid var(--secondary);
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
            
            .function-grid {
                grid-template-columns: 1fr;
            }
            
            .params-grid {
                grid-template-columns: 1fr;
            }
            
            .result-value {
                font-size: 2rem;
            }
            
            .btn-group {
                flex-direction: column;
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
            <h1>🔧 Funciones en PHP - Ejemplo Interactivo</h1>
            <p>Experimenta con diferentes funciones y personaliza sus parámetros</p>
        </header>

        <div class="card fade-in">
            <h2 class="card-title">
                <i>🎯</i> Selecciona una Función para Ejecutar
            </h2>
            
            <div class="function-grid">
                <div class="function-card <?php echo (!isset($_POST['funcion']) || $_POST['funcion'] == 'calcular_iva') ? 'active' : ''; ?>" 
                     onclick="selectFunction('calcular_iva')">
                    <div class="function-icon">💰</div>
                    <div class="function-name">Calcular IVA</div>
                    <div class="function-desc">Calcula el IVA de un monto con porcentaje personalizable</div>
                    <div class="function-params">
                        <span class="param-tag">$monto</span>
                        <span class="param-tag">$porcentaje</span>
                    </div>
                </div>
                
                <div class="function-card <?php echo (isset($_POST['funcion']) && $_POST['funcion'] == 'generar_email') ? 'active' : ''; ?>" 
                     onclick="selectFunction('generar_email')">
                    <div class="function-icon">📧</div>
                    <div class="function-name">Generar Email</div>
                    <div class="function-desc">Genera un email corporativo personalizado</div>
                    <div class="function-params">
                        <span class="param-tag">$nombre</span>
                        <span class="param-tag">$apellido</span>
                        <span class="param-tag">$dominio</span>
                    </div>
                </div>
                
                <div class="function-card <?php echo (isset($_POST['funcion']) && $_POST['funcion'] == 'calcular_factorial') ? 'active' : ''; ?>" 
                     onclick="selectFunction('calcular_factorial')">
                    <div class="function-icon">🧮</div>
                    <div class="function-name">Factorial Recursivo</div>
                    <div class="function-desc">Calcula el factorial de un número usando recursión</div>
                    <div class="function-params">
                        <span class="param-tag">$numero</span>
                    </div>
                </div>
                
                <div class="function-card <?php echo (isset($_POST['funcion']) && $_POST['funcion'] == 'validar_usuario') ? 'active' : ''; ?>" 
                     onclick="selectFunction('validar_usuario')">
                    <div class="function-icon">👤</div>
                    <div class="function-name">Validar Usuario</div>
                    <div class="function-desc">Valida datos de usuario con criterios personalizables</div>
                    <div class="function-params">
                        <span class="param-tag">$usuario</span>
                        <span class="param-tag">$password</span>
                        <span class="param-tag">$edad</span>
                        <span class="param-tag">$pais</span>
                    </div>
                </div>
            </div>

            <form method="POST" action="" id="functionForm">
                <input type="hidden" name="funcion" id="funcionInput" value="<?php echo isset($_POST['funcion']) ? $_POST['funcion'] : 'calcular_iva'; ?>">
                
                <div class="params-container fade-in">
                    <h3 class="card-title">
                        <i>⚙️</i> Parámetros de la Función
                    </h3>
                    
                    <div id="parametrosDinamicos">
                        <?php
                        $funcion_actual = isset($_POST['funcion']) ? $_POST['funcion'] : 'calcular_iva';
                        
                        switch ($funcion_actual) {
                            case 'calcular_iva':
                                echo '
                                <div class="params-grid">
                                    <div class="param-group">
                                        <div class="param-header">
                                            <span class="param-icon">💰</span>
                                            <label class="param-label">Monto Base</label>
                                        </div>
                                        <input type="number" name="monto" value="'.(isset($_POST['monto']) ? $_POST['monto'] : '1000').'" step="0.01" min="0" required>
                                        <div class="param-hint">Ingresa el monto al que calcular el IVA</div>
                                    </div>
                                    
                                    <div class="param-group">
                                        <div class="param-header">
                                            <span class="param-icon">📊</span>
                                            <label class="param-label">Porcentaje de IVA (%)</label>
                                        </div>
                                        <input type="number" name="porcentaje_iva" value="'.(isset($_POST['porcentaje_iva']) ? $_POST['porcentaje_iva'] : '21').'" step="0.1" min="0" max="100" required>
                                        <div class="param-hint">Porcentaje de IVA a aplicar (0-100)</div>
                                    </div>
                                </div>
                                ';
                                break;
                                
                            case 'generar_email':
                                echo '
                                <div class="params-grid">
                                    <div class="param-group">
                                        <div class="param-header">
                                            <span class="param-icon">👤</span>
                                            <label class="param-label">Nombre</label>
                                        </div>
                                        <input type="text" name="nombre" value="'.(isset($_POST['nombre']) ? $_POST['nombre'] : 'Juan').'" required>
                                        <div class="param-hint">Nombre de la persona</div>
                                    </div>
                                    
                                    <div class="param-group">
                                        <div class="param-header">
                                            <span class="param-icon">👥</span>
                                            <label class="param-label">Apellido</label>
                                        </div>
                                        <input type="text" name="apellido" value="'.(isset($_POST['apellido']) ? $_POST['apellido'] : 'Pérez').'" required>
                                        <div class="param-hint">Apellido de la persona</div>
                                    </div>
                                    
                                    <div class="param-group">
                                        <div class="param-header">
                                            <span class="param-icon">🏢</span>
                                            <label class="param-label">Empresa</label>
                                        </div>
                                        <input type="text" name="empresa" value="'.(isset($_POST['empresa']) ? $_POST['empresa'] : 'MiEmpresa').'" required>
                                        <div class="param-hint">Nombre de la empresa</div>
                                    </div>
                                    
                                    <div class="param-group">
                                        <div class="param-header">
                                            <span class="param-icon">🌐</span>
                                            <label class="param-label">Dominio</label>
                                        </div>
                                        <input type="text" name="dominio" value="'.(isset($_POST['dominio']) ? $_POST['dominio'] : 'empresa.com').'" required>
                                        <div class="param-hint">Dominio del email (ej: empresa.com)</div>
                                    </div>
                                </div>
                                ';
                                break;
                                
                            case 'calcular_factorial':
                                echo '
                                <div class="params-grid">
                                    <div class="param-group">
                                        <div class="param-header">
                                            <span class="param-icon">🔢</span>
                                            <label class="param-label">Número</label>
                                        </div>
                                        <input type="number" name="numero" value="'.(isset($_POST['numero']) ? $_POST['numero'] : '5').'" min="0" max="50" required>
                                        <div class="param-hint">Número para calcular factorial (0-50)</div>
                                    </div>
                                </div>
                                ';
                                break;
                                
                            case 'validar_usuario':
                                echo '
                                <div class="params-grid">
                                    <div class="param-group">
                                        <div class="param-header">
                                            <span class="param-icon">👤</span>
                                            <label class="param-label">Usuario</label>
                                        </div>
                                        <input type="text" name="usuario" value="'.(isset($_POST['usuario']) ? $_POST['usuario'] : 'juan_perez').'" required>
                                        <div class="param-hint">Nombre de usuario (mín. 4 caracteres)</div>
                                    </div>
                                    
                                    <div class="param-group">
                                        <div class="param-header">
                                            <span class="param-icon">🔒</span>
                                            <label class="param-label">Contraseña</label>
                                        </div>
                                        <input type="password" name="password" value="'.(isset($_POST['password']) ? $_POST['password'] : '123456').'" required>
                                        <div class="param-hint">Contraseña (mín. 6 caracteres)</div>
                                    </div>
                                    
                                    <div class="param-group">
                                        <div class="param-header">
                                            <span class="param-icon">🎂</span>
                                            <label class="param-label">Edad</label>
                                        </div>
                                        <input type="number" name="edad" value="'.(isset($_POST['edad']) ? $_POST['edad'] : '25').'" min="1" max="120" required>
                                        <div class="param-hint">Edad del usuario (18-120)</div>
                                    </div>
                                    
                                    <div class="param-group">
                                        <div class="param-header">
                                            <span class="param-icon">🌎</span>
                                            <label class="param-label">País</label>
                                        </div>
                                        <select name="pais" required>
                                            <option value="Argentina" '.((isset($_POST['pais']) && $_POST['pais'] == 'Argentina') ? 'selected' : '').'>Argentina</option>
                                            <option value="Chile" '.((isset($_POST['pais']) && $_POST['pais'] == 'Chile') ? 'selected' : '').'>Chile</option>
                                            <option value="Uruguay" '.((isset($_POST['pais']) && $_POST['pais'] == 'Uruguay') ? 'selected' : '').'>Uruguay</option>
                                            <option value="Paraguay" '.((isset($_POST['pais']) && $_POST['pais'] == 'Paraguay') ? 'selected' : '').'>Paraguay</option>
                                            <option value="Brasil" '.((isset($_POST['pais']) && $_POST['pais'] == 'Brasil') ? 'selected' : '').'>Brasil</option>
                                            <option value="Bolivia" '.((isset($_POST['pais']) && $_POST['pais'] == 'Bolivia') ? 'selected' : '').'>Bolivia</option>
                                        </select>
                                        <div class="param-hint">País de residencia</div>
                                    </div>
                                </div>
                                ';
                                break;
                        }
                        ?>
                    </div>
                </div>
                
                <div class="btn-group">
                    <button type="submit" name="ejecutar" class="btn">
                        <i>🚀</i> Ejecutar Función
                    </button>
                    <button type="button" onclick="resetForm()" class="btn btn-warning">
                        <i>🔄</i> Reiniciar
                    </button>
                    <button type="button" onclick="loadExample()" class="btn btn-secondary">
                        <i>📚</i> Cargar Ejemplo
                    </button>
                </div>
            </form>
        </div>

        <?php
        // --- DEFINICIÓN DE FUNCIONES ---
        
        function calcular_iva($monto, $porcentaje_iva = 21) {
            $iva = $monto * ($porcentaje_iva / 100);
            $total = $monto + $iva;
            
            return [
                'monto_original' => $monto,
                'porcentaje_iva' => $porcentaje_iva,
                'monto_iva' => $iva,
                'total' => $total
            ];
        }
        
        function generar_email($nombre, $apellido, $empresa, $dominio = 'company.com') {
            $email_base = strtolower($nombre . '.' . $apellido);
            $email_base = str_replace(' ', '', $email_base);
            $email = $email_base . '@' . $dominio;
            
            return [
                'nombre_completo' => $nombre . ' ' . $apellido,
                'empresa' => $empresa,
                'email' => $email,
                'usuario' => $email_base
            ];
        }
        
        function calcular_factorial($numero) {
            if ($numero < 0) {
                return "Error: No existe factorial de números negativos";
            }
            
            if ($numero == 0 || $numero == 1) {
                return 1;
            }
            
            return $numero * calcular_factorial($numero - 1);
        }
        
        function validar_usuario($usuario, $password, $edad, $pais) {
            $errores = [];
            
            // Validar usuario
            if (strlen($usuario) < 4) {
                $errores[] = "El usuario debe tener al menos 4 caracteres";
            }
            
            if (!preg_match('/^[a-zA-Z0-9_]+$/', $usuario)) {
                $errores[] = "El usuario solo puede contener letras, números y guiones bajos";
            }
            
            // Validar contraseña
            if (strlen($password) < 6) {
                $errores[] = "La contraseña debe tener al menos 6 caracteres";
            }
            
            // Validar edad
            if ($edad < 18) {
                $errores[] = "Debes ser mayor de 18 años";
            }
            
            if ($edad > 120) {
                $errores[] = "Edad no válida";
            }
            
            // Validar país
            $paises_validos = ['Argentina', 'Chile', 'Uruguay', 'Paraguay', 'Brasil', 'Bolivia'];
            if (!in_array($pais, $paises_validos)) {
                $errores[] = "País no válido para el registro";
            }
            
            return [
                'valido' => empty($errores),
                'errores' => $errores,
                'usuario' => $usuario
            ];
        }
        
        // --- EJECUCIÓN DE FUNCIONES ---
        
        if (isset($_POST['ejecutar'])) {
            $funcion = $_POST['funcion'];
            
            echo '<div class="result-section fade-in">';
            echo '<div class="card">';
            echo '<h2 class="card-title"><i>📊</i> Resultado de la Ejecución</h2>';
            
            // Mostrar parámetros utilizados
            echo '<div class="param-list">';
            foreach ($_POST as $key => $value) {
                if ($key != 'ejecutar' && $key != 'funcion' && !empty($value)) {
                    echo '<div class="param-item">';
                    echo '<div class="param-name">' . htmlspecialchars($key) . '</div>';
                    echo '<div class="param-value">' . htmlspecialchars($value) . '</div>';
                    echo '</div>';
                }
            }
            echo '</div>';
            
            // Ejecutar función seleccionada
            echo '<div class="execution-flow">';
            echo '<h3 style="color: var(--primary); margin-bottom: 1rem;">🔍 Flujo de Ejecución:</h3>';
            
            switch ($funcion) {
                case 'calcular_iva':
                    $monto = floatval($_POST['monto']);
                    $porcentaje = floatval($_POST['porcentaje_iva']);
                    $resultado = calcular_iva($monto, $porcentaje);
                    
                    echo '<div class="flow-step">';
                    echo '<div class="step-number">1</div>';
                    echo '<div>Función <strong>calcular_iva()</strong> llamada con monto: $' . $monto . ' e IVA: ' . $porcentaje . '%</div>';
                    echo '</div>';
                    
                    echo '<div class="flow-step">';
                    echo '<div class="step-number">2</div>';
                    echo '<div>Cálculo del IVA: $' . $monto . ' × ' . $porcentaje . '% = $' . number_format($resultado['monto_iva'], 2) . '</div>';
                    echo '</div>';
                    
                    echo '<div class="flow-step">';
                    echo '<div class="step-number">3</div>';
                    echo '<div>Total calculado: $' . $monto . ' + $' . number_format($resultado['monto_iva'], 2) . ' = $' . number_format($resultado['total'], 2) . '</div>';
                    echo '</div>';
                    
                    echo '<div class="result-display">';
                    echo '<h3>💰 Resultado del Cálculo de IVA</h3>';
                    echo '<div class="result-value">$' . number_format($resultado['total'], 2) . '</div>';
                    echo '<div style="color: var(--text-secondary);">';
                    echo 'Monto original: $' . number_format($resultado['monto_original'], 2) . ' | ';
                    echo 'IVA (' . $resultado['porcentaje_iva'] . '%): $' . number_format($resultado['monto_iva'], 2);
                    echo '</div>';
                    echo '</div>';
                    break;
                    
                case 'generar_email':
                    $nombre = $_POST['nombre'];
                    $apellido = $_POST['apellido'];
                    $empresa = $_POST['empresa'];
                    $dominio = $_POST['dominio'];
                    $resultado = generar_email($nombre, $apellido, $empresa, $dominio);
                    
                    echo '<div class="flow-step">';
                    echo '<div class="step-number">1</div>';
                    echo '<div>Función <strong>generar_email()</strong> llamada con: ' . $nombre . ' ' . $apellido . '</div>';
                    echo '</div>';
                    
                    echo '<div class="flow-step">';
                    echo '<div class="step-number">2</div>';
                    echo '<div>Generando nombre de usuario: "' . $nombre . '.' . $apellido . '" → "' . $resultado['usuario'] . '"</div>';
                    echo '</div>';
                    
                    echo '<div class="flow-step">';
                    echo '<div class="step-number">3</div>';
                    echo '<div>Combinando con dominio: ' . $resultado['usuario'] . '@' . $dominio . '</div>';
                    echo '</div>';
                    
                    echo '<div class="result-display">';
                    echo '<h3>📧 Email Corporativo Generado</h3>';
                    echo '<div class="result-value">' . $resultado['email'] . '</div>';
                    echo '<div style="color: var(--text-secondary);">';
                    echo 'Para: ' . $resultado['nombre_completo'] . ' | ';
                    echo 'Empresa: ' . $resultado['empresa'];
                    echo '</div>';
                    echo '</div>';
                    break;
                    
                case 'calcular_factorial':
                    $numero = intval($_POST['numero']);
                    $resultado = calcular_factorial($numero);
                    
                    echo '<div class="flow-step">';
                    echo '<div class="step-number">1</div>';
                    echo '<div>Función <strong>calcular_factorial()</strong> llamada con n = ' . $numero . '</div>';
                    echo '</div>';
                    
                    if (is_numeric($resultado)) {
                        echo '<div class="flow-step">';
                        echo '<div class="step-number">2</div>';
                        echo '<div>Proceso recursivo: ' . $numero . '! = ' . $numero . ' × ' . ($numero-1) . '! </div>';
                        echo '</div>';
                        
                        echo '<div class="flow-step">';
                        echo '<div class="step-number">3</div>';
                        echo '<div>Descomposición completa: ' . implode(' × ', range($numero, 1)) . '</div>';
                        echo '</div>';
                        
                        echo '<div class="result-display">';
                        echo '<h3>🧮 Resultado del Factorial</h3>';
                        echo '<div class="result-value">' . number_format($resultado) . '</div>';
                        echo '<div style="color: var(--text-secondary);">';
                        echo $numero . '! = ' . implode(' × ', range($numero, 1));
                        echo '</div>';
                        echo '</div>';
                    } else {
                        echo '<div class="result-display" style="border-color: var(--danger);">';
                        echo '<h3>❌ Error en el Cálculo</h3>';
                        echo '<div class="result-value" style="color: var(--danger);">' . $resultado . '</div>';
                        echo '</div>';
                    }
                    break;
                    
                case 'validar_usuario':
                    $usuario = $_POST['usuario'];
                    $password = $_POST['password'];
                    $edad = intval($_POST['edad']);
                    $pais = $_POST['pais'];
                    $resultado = validar_usuario($usuario, $password, $edad, $pais);
                    
                    echo '<div class="flow-step">';
                    echo '<div class="step-number">1</div>';
                    echo '<div>Función <strong>validar_usuario()</strong> llamada para usuario: ' . $usuario . '</div>';
                    echo '</div>';
                    
                    echo '<div class="flow-step">';
                    echo '<div class="step-number">2</div>';
                    echo '<div>Validando criterios: longitud, caracteres, edad, país...</div>';
                    echo '</div>';
                    
                    if ($resultado['valido']) {
                        echo '<div class="result-display" style="border-color: var(--success);">';
                        echo '<h3>✅ Validación Exitosa</h3>';
                        echo '<div class="result-value" style="color: var(--success);">USUARIO VÁLIDO</div>';
                        echo '<div style="color: var(--text-secondary);">';
                        echo 'El usuario "' . $resultado['usuario'] . '" cumple con todos los requisitos';
                        echo '</div>';
                        echo '</div>';
                    } else {
                        echo '<div class="result-display" style="border-color: var(--danger);">';
                        echo '<h3>❌ Errores de Validación</h3>';
                        echo '<div style="color: var(--danger); font-size: 1.2rem; margin: 1rem 0;">';
                        foreach ($resultado['errores'] as $error) {
                            echo '• ' . $error . '<br>';
                        }
                        echo '</div>';
                        echo '</div>';
                    }
                    break;
            }
            
            echo '</div>'; // cierre execution-flow
            
            // Mostrar código de la función
            echo '<div class="code-preview">';
            echo '<h3 style="color: var(--primary); margin-bottom: 1rem;">📝 Código de la Función:</h3>';
            echo '<pre>';
            switch ($funcion) {
                case 'calcular_iva':
                    echo "function calcular_iva(\$monto, \$porcentaje_iva = 21) {\n";
                    echo "    \$iva = \$monto * (\$porcentaje_iva / 100);\n";
                    echo "    \$total = \$monto + \$iva;\n";
                    echo "    return [\n";
                    echo "        'monto_original' => \$monto,\n";
                    echo "        'porcentaje_iva' => \$porcentaje_iva,\n";
                    echo "        'monto_iva' => \$iva,\n";
                    echo "        'total' => \$total\n";
                    echo "    ];\n";
                    echo "}";
                    break;
                    
                case 'generar_email':
                    echo "function generar_email(\$nombre, \$apellido, \$empresa, \$dominio = 'company.com') {\n";
                    echo "    \$email_base = strtolower(\$nombre . '.' . \$apellido);\n";
                    echo "    \$email_base = str_replace(' ', '', \$email_base);\n";
                    echo "    \$email = \$email_base . '@' . \$dominio;\n";
                    echo "    return [\n";
                    echo "        'nombre_completo' => \$nombre . ' ' . \$apellido,\n";
                    echo "        'empresa' => \$empresa,\n";
                    echo "        'email' => \$email,\n";
                    echo "        'usuario' => \$email_base\n";
                    echo "    ];\n";
                    echo "}";
                    break;
                    
                case 'calcular_factorial':
                    echo "function calcular_factorial(\$numero) {\n";
                    echo "    if (\$numero < 0) {\n";
                    echo "        return 'Error: No existe factorial de números negativos';\n";
                    echo "    }\n";
                    echo "    if (\$numero == 0 || \$numero == 1) {\n";
                    echo "        return 1;\n";
                    echo "    }\n";
                    echo "    return \$numero * calcular_factorial(\$numero - 1);\n";
                    echo "}";
                    break;
                    
                case 'validar_usuario':
                    echo "function validar_usuario(\$usuario, \$password, \$edad, \$pais) {\n";
                    echo "    \$errores = [];\n";
                    echo "    if (strlen(\$usuario) < 4) {\n";
                    echo "        \$errores[] = 'El usuario debe tener al menos 4 caracteres';\n";
                    echo "    }\n";
                    echo "    if (!preg_match('/^[a-zA-Z0-9_]+$/', \$usuario)) {\n";
                    echo "        \$errores[] = 'El usuario solo puede contener letras, números y guiones bajos';\n";
                    echo "    }\n";
                    echo "    if (strlen(\$password) < 6) {\n";
                    echo "        \$errores[] = 'La contraseña debe tener al menos 6 caracteres';\n";
                    echo "    }\n";
                    echo "    if (\$edad < 18) {\n";
                    echo "        \$errores[] = 'Debes ser mayor de 18 años';\n";
                    echo "    }\n";
                    echo "    \$paises_validos = ['Argentina', 'Chile', 'Uruguay', 'Paraguay', 'Brasil', 'Bolivia'];\n";
                    echo "    if (!in_array(\$pais, \$paises_validos)) {\n";
                    echo "        \$errores[] = 'País no válido para el registro';\n";
                    echo "    }\n";
                    echo "    return [\n";
                    echo "        'valido' => empty(\$errores),\n";
                    echo "        'errores' => \$errores,\n";
                    echo "        'usuario' => \$usuario\n";
                    echo "    ];\n";
                    echo "}";
                    break;
            }
            echo '</pre>';
            echo '</div>';
            
            echo '</div>'; // cierre del card
            echo '</div>'; // cierre del result-section
        }
        ?>
        
        <div class="explanation fade-in">
            <h3>🎓 Conceptos Fundamentales de Funciones</h3>
            <div class="feature-list">
                <div class="feature-item">
                    <span class="feature-icon">📝</span>
                    <div>
                        <strong>Declaración</strong>
                        <p>Las funciones se definen con la palabra clave <code>function</code> seguida del nombre y parámetros</p>
                    </div>
                </div>
                <div class="feature-item">
                    <span class="feature-icon">🔄</span>
                    <div>
                        <strong>Parámetros</strong>
                        <p>Pueden tener valores por defecto y tipos definidos para mayor robustez</p>
                    </div>
                </div>
                <div class="feature-item">
                    <span class="feature-icon">📤</span>
                    <div>
                        <strong>Retorno de Valores</strong>
                        <p>Pueden devolver cualquier tipo de dato, incluyendo arrays y objetos</p>
                    </div>
                </div>
                <div class="feature-item">
                    <span class="feature-icon">🌀</span>
                    <div>
                        <strong>Recursividad</strong>
                        <p>Una función puede llamarse a sí misma para resolver problemas complejos</p>
                    </div>
                </div>
            </div>
            
            <div class="syntax-example">
                <h4 style="color: var(--primary); margin-bottom: 1rem;">💡 Estructura Básica:</h4>
                <div class="function-signature">
                    function nombreFuncion($parametro1, $parametro2 = valorDefault) {<br>
                    &nbsp;&nbsp;// Código de la función<br>
                    &nbsp;&nbsp;return $resultado;<br>
                    }
                </div>
                
                <div class="function-call">
                    // Llamada a la función<br>
                    $resultado = nombreFuncion($valor1, $valor2);
                </div>
            </div>
            
            <div style="background: var(--dark-card); padding: 1.5rem; border-radius: 10px; margin-top: 1.5rem;">
                <h4 style="color: var(--primary); margin-bottom: 1rem;">🎯 Mejores Prácticas:</h4>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1rem;">
                    <div>
                        <div style="font-weight: bold; color: var(--secondary);">🎪 Un solo propósito</div>
                        <div style="font-size: 0.9rem; color: var(--text-secondary);">Cada función debe hacer una cosa específica</div>
                    </div>
                    <div>
                        <div style="font-weight: bold; color: var(--secondary);">📏 Nombres descriptivos</div>
                        <div style="font-size: 0.9rem; color: var(--text-secondary);">Usar verbos que describan la acción</div>
                    </div>
                    <div>
                        <div style="font-weight: bold; color: var(--secondary);">🛡️ Validar entradas</div>
                        <div style="font-size: 0.9rem; color: var(--text-secondary);">Verificar parámetros antes de procesar</div>
                    </div>
                    <div>
                        <div style="font-weight: bold; color: var(--secondary);">📚 Documentar</div>
                        <div style="font-size: 0.9rem; color: var(--text-secondary);">Comentar el propósito y parámetros</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function selectFunction(funcion) {
            document.getElementById('funcionInput').value = funcion;
            
            // Actualizar tarjetas activas
            document.querySelectorAll('.function-card').forEach(card => {
                card.classList.remove('active');
            });
            event.currentTarget.classList.add('active');
            
            // Recargar para mostrar nuevos parámetros
            document.getElementById('functionForm').submit();
        }
        
        function resetForm() {
            document.getElementById('functionForm').reset();
            document.getElementById('funcionInput').value = 'calcular_iva';
            
            // Reactivar la primera tarjeta
            document.querySelectorAll('.function-card').forEach((card, index) => {
                card.classList.remove('active');
                if (index === 0) card.classList.add('active');
            });
        }
        
        function loadExample() {
            const currentFunction = document.getElementById('funcionInput').value;
            
            switch (currentFunction) {
                case 'calcular_iva':
                    document.querySelector('input[name="monto"]').value = '2500';
                    document.querySelector('input[name="porcentaje_iva"]').value = '10.5';
                    break;
                case 'generar_email':
                    document.querySelector('input[name="nombre"]').value = 'María';
                    document.querySelector('input[name="apellido"]').value = 'González';
                    document.querySelector('input[name="empresa"]').value = 'TechSolutions';
                    document.querySelector('input[name="dominio"]').value = 'techsolutions.com';
                    break;
                case 'calcular_factorial':
                    document.querySelector('input[name="numero"]').value = '7';
                    break;
                case 'validar_usuario':
                    document.querySelector('input[name="usuario"]').value = 'ana_developer';
                    document.querySelector('input[name="password"]').value = 'securepass123';
                    document.querySelector('input[name="edad"]').value = '28';
                    document.querySelector('select[name="pais"]').value = 'Argentina';
                    break;
            }
        }
        
        // Efectos de interacción
        document.addEventListener('DOMContentLoaded', function() {
            const inputs = document.querySelectorAll('input, select, textarea');
            const cards = document.querySelectorAll('.function-card');
            
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.style.transform = 'scale(1.02)';
                });
                
                input.addEventListener('blur', function() {
                    this.style.transform = 'scale(1)';
                });
            });
            
            cards.forEach(card => {
                card.addEventListener('click', function() {
                    cards.forEach(c => c.classList.remove('active'));
                    this.classList.add('active');
                });
            });
        });
    </script>
</body>
</html>