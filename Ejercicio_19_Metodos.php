<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Métodos en PHP - POO Interactivo</title>
    <style>
        :root {
            --primary: #8b5cf6;
            --primary-dark: #7c3aed;
            --secondary: #06d6a0;
            --accent: #f59e0b;
            --warning: #eab308;
            --danger: #ef4444;
            --info: #3b82f6;
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
            background: linear-gradient(135deg, var(--dark-bg) 0%, #1e1b4b 100%);
            color: var(--text-primary);
            line-height: 1.6;
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 1400px;
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

        .class-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .class-card {
            background: var(--dark-card);
            border-radius: 12px;
            padding: 1.5rem;
            border: 2px solid transparent;
            transition: all 0.3s ease;
            cursor: pointer;
            position: relative;
            overflow: hidden;
        }

        .class-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(139, 92, 246, 0.1), transparent);
            transition: left 0.5s ease;
        }

        .class-card:hover::before {
            left: 100%;
        }

        .class-card:hover {
            border-color: var(--primary);
            transform: translateY(-3px);
        }

        .class-card.active {
            border-color: var(--secondary);
            background: rgba(6, 214, 160, 0.1);
        }

        .class-icon {
            font-size: 2rem;
            margin-bottom: 1rem;
        }

        .class-name {
            font-size: 1.3rem;
            font-weight: bold;
            margin-bottom: 0.5rem;
            color: var(--primary);
        }

        .class-desc {
            color: var(--text-secondary);
            font-size: 0.9rem;
            margin-bottom: 1rem;
        }

        .methods-list {
            margin-top: 1rem;
        }

        .method-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem;
            margin: 0.25rem 0;
            background: rgba(139, 92, 246, 0.1);
            border-radius: 6px;
            font-size: 0.85rem;
        }

        .method-icon {
            color: var(--accent);
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
            box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.2);
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
            box-shadow: 0 7px 20px rgba(139, 92, 246, 0.4);
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
            background: rgba(139, 92, 246, 0.1);
            border-radius: 8px;
            border-left: 4px solid var(--primary);
            transition: all 0.3s ease;
        }

        .flow-step:hover {
            transform: translateX(5px);
            background: rgba(139, 92, 246, 0.2);
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
            font-size: 2rem;
            font-weight: bold;
            background: linear-gradient(90deg, var(--secondary), var(--accent));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            margin: 1rem 0;
        }

        .object-state {
            background: rgba(59, 130, 246, 0.1);
            border-radius: 8px;
            padding: 1.5rem;
            margin: 1rem 0;
            border-left: 4px solid var(--info);
        }

        .state-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-top: 1rem;
        }

        .state-item {
            background: rgba(139, 92, 246, 0.1);
            padding: 1rem;
            border-radius: 8px;
            text-align: center;
        }

        .state-name {
            font-weight: bold;
            color: var(--primary);
            font-size: 0.9rem;
        }

        .state-value {
            color: var(--secondary);
            font-size: 1.1rem;
            margin-top: 0.5rem;
        }

        .method-call {
            background: rgba(6, 214, 160, 0.1);
            padding: 1rem;
            border-radius: 8px;
            margin: 1rem 0;
            font-family: 'Consolas', 'Monaco', monospace;
            border-left: 4px solid var(--secondary);
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
            background: rgba(139, 92, 246, 0.1);
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
            
            .class-grid {
                grid-template-columns: 1fr;
            }
            
            .params-grid {
                grid-template-columns: 1fr;
            }
            
            .result-value {
                font-size: 1.5rem;
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
            <h1>🏗️ Métodos en PHP - Programación Orientada a Objetos</h1>
            <p>Explora métodos, clases y objetos de forma interactiva</p>
        </header>

        <div class="card fade-in">
            <h2 class="card-title">
                <i>🎯</i> Selecciona una Clase para Trabajar
            </h2>
            
            <div class="class-grid">
                <div class="class-card <?php echo (!isset($_POST['clase']) || $_POST['clase'] == 'Usuario') ? 'active' : ''; ?>" 
                     onclick="selectClass('Usuario')">
                    <div class="class-icon">👤</div>
                    <div class="class-name">Clase Usuario</div>
                    <div class="class-desc">Gestiona información de usuarios con métodos para validar y manipular datos</div>
                    <div class="methods-list">
                        <div class="method-item">
                            <span class="method-icon">⚡</span>
                            <span>__construct()</span>
                        </div>
                        <div class="method-item">
                            <span class="method-icon">✅</span>
                            <span>validar()</span>
                        </div>
                        <div class="method-item">
                            <span class="method-icon">📧</span>
                            <span>generarEmail()</span>
                        </div>
                        <div class="method-item">
                            <span class="method-icon">🎂</span>
                            <span>esMayorDeEdad()</span>
                        </div>
                    </div>
                </div>
                
                <div class="class-card <?php echo (isset($_POST['clase']) && $_POST['clase'] == 'Calculadora') ? 'active' : ''; ?>" 
                     onclick="selectClass('Calculadora')">
                    <div class="class-icon">🧮</div>
                    <div class="class-name">Clase Calculadora</div>
                    <div class="class-desc">Realiza operaciones matemáticas con métodos estáticos y de instancia</div>
                    <div class="methods-list">
                        <div class="method-item">
                            <span class="method-icon">⚡</span>
                            <span>__construct()</span>
                        </div>
                        <div class="method-item">
                            <span class="method-icon">➕</span>
                            <span>sumar()</span>
                        </div>
                        <div class="method-item">
                            <span class="method-icon">📊</span>
                            <span>promedio()</span>
                        </div>
                        <div class="method-item">
                            <span class="method-icon">⭐</span>
                            <span>esPar() - estático</span>
                        </div>
                    </div>
                </div>
                
                <div class="class-card <?php echo (isset($_POST['clase']) && $_POST['clase'] == 'Producto') ? 'active' : ''; ?>" 
                     onclick="selectClass('Producto')">
                    <div class="class-icon">📦</div>
                    <div class="class-name">Clase Producto</div>
                    <div class="class-desc">Modela productos con métodos para gestionar inventario y precios</div>
                    <div class="methods-list">
                        <div class="method-item">
                            <span class="method-icon">⚡</span>
                            <span>__construct()</span>
                        </div>
                        <div class="method-item">
                            <span class="method-icon">💰</span>
                            <span>aplicarDescuento()</span>
                        </div>
                        <div class="method-item">
                            <span class="method-icon">📋</span>
                            <span>estaDisponible()</span>
                        </div>
                        <div class="method-item">
                            <span class="method-icon">🔄</span>
                            <span>actualizarStock()</span>
                        </div>
                    </div>
                </div>
                
                <div class="class-card <?php echo (isset($_POST['clase']) && $_POST['clase'] == 'Banco') ? 'active' : ''; ?>" 
                     onclick="selectClass('Banco')">
                    <div class="class-icon">🏦</div>
                    <div class="class-name">Clase Banco</div>
                    <div class="class-desc">Simula operaciones bancarias con métodos para transacciones</div>
                    <div class="methods-list">
                        <div class="method-item">
                            <span class="method-icon">⚡</span>
                            <span>__construct()</span>
                        </div>
                        <div class="method-item">
                            <span class="method-icon">💳</span>
                            <span>depositar()</span>
                        </div>
                        <div class="method-item">
                            <span class="method-icon">🏧</span>
                            <span>retirar()</span>
                        </div>
                        <div class="method-item">
                            <span class="method-icon">📈</span>
                            <span>calcularInteres()</span>
                        </div>
                    </div>
                </div>
            </div>

            <form method="POST" action="" id="classForm">
                <input type="hidden" name="clase" id="claseInput" value="<?php echo isset($_POST['clase']) ? $_POST['clase'] : 'Usuario'; ?>">
                
                <div class="params-container fade-in">
                    <h3 class="card-title">
                        <i>⚙️</i> Configuración del Objeto
                    </h3>
                    
                    <div id="parametrosDinamicos">
                        <?php
                        $clase_actual = isset($_POST['clase']) ? $_POST['clase'] : 'Usuario';
                        
                        switch ($clase_actual) {
                            case 'Usuario':
                                echo '
                                <div class="params-grid">
                                    <div class="param-group">
                                        <div class="param-header">
                                            <span class="param-icon">👤</span>
                                            <label class="param-label">Nombre</label>
                                        </div>
                                        <input type="text" name="nombre" value="'.(isset($_POST['nombre']) ? $_POST['nombre'] : 'Ana').'" required>
                                        <div class="param-hint">Nombre del usuario</div>
                                    </div>
                                    
                                    <div class="param-group">
                                        <div class="param-header">
                                            <span class="param-icon">👥</span>
                                            <label class="param-label">Apellido</label>
                                        </div>
                                        <input type="text" name="apellido" value="'.(isset($_POST['apellido']) ? $_POST['apellido'] : 'García').'" required>
                                        <div class="param-hint">Apellido del usuario</div>
                                    </div>
                                    
                                    <div class="param-group">
                                        <div class="param-header">
                                            <span class="param-icon">🎂</span>
                                            <label class="param-label">Edad</label>
                                        </div>
                                        <input type="number" name="edad" value="'.(isset($_POST['edad']) ? $_POST['edad'] : '25').'" min="1" max="120" required>
                                        <div class="param-hint">Edad del usuario</div>
                                    </div>
                                    
                                    <div class="param-group">
                                        <div class="param-header">
                                            <span class="param-icon">📧</span>
                                            <label class="param-label">Email</label>
                                        </div>
                                        <input type="email" name="email" value="'.(isset($_POST['email']) ? $_POST['email'] : 'ana@ejemplo.com').'" required>
                                        <div class="param-hint">Email del usuario</div>
                                    </div>
                                </div>
                                ';
                                break;
                                
                            case 'Calculadora':
                                echo '
                                <div class="params-grid">
                                    <div class="param-group">
                                        <div class="param-header">
                                            <span class="param-icon">🔢</span>
                                            <label class="param-label">Número 1</label>
                                        </div>
                                        <input type="number" name="num1" value="'.(isset($_POST['num1']) ? $_POST['num1'] : '10').'" step="0.1" required>
                                        <div class="param-hint">Primer número para operaciones</div>
                                    </div>
                                    
                                    <div class="param-group">
                                        <div class="param-header">
                                            <span class="param-icon">🔢</span>
                                            <label class="param-label">Número 2</label>
                                        </div>
                                        <input type="number" name="num2" value="'.(isset($_POST['num2']) ? $_POST['num2'] : '5').'" step="0.1" required>
                                        <div class="param-hint">Segundo número para operaciones</div>
                                    </div>
                                    
                                    <div class="param-group">
                                        <div class="param-header">
                                            <span class="param-icon">📊</span>
                                            <label class="param-label">Lista de Números</label>
                                        </div>
                                        <input type="text" name="numeros" value="'.(isset($_POST['numeros']) ? $_POST['numeros'] : '10,20,30,40,50').'" required>
                                        <div class="param-hint">Números separados por coma para promedio</div>
                                    </div>
                                </div>
                                ';
                                break;
                                
                            case 'Producto':
                                echo '
                                <div class="params-grid">
                                    <div class="param-group">
                                        <div class="param-header">
                                            <span class="param-icon">📦</span>
                                            <label class="param-label">Nombre del Producto</label>
                                        </div>
                                        <input type="text" name="nombre_producto" value="'.(isset($_POST['nombre_producto']) ? $_POST['nombre_producto'] : 'Laptop Gaming').'" required>
                                        <div class="param-hint">Nombre del producto</div>
                                    </div>
                                    
                                    <div class="param-group">
                                        <div class="param-header">
                                            <span class="param-icon">💰</span>
                                            <label class="param-label">Precio</label>
                                        </div>
                                        <input type="number" name="precio" value="'.(isset($_POST['precio']) ? $_POST['precio'] : '1200').'" step="0.01" min="0" required>
                                        <div class="param-hint">Precio del producto</div>
                                    </div>
                                    
                                    <div class="param-group">
                                        <div class="param-header">
                                            <span class="param-icon">📋</span>
                                            <label class="param-label">Stock</label>
                                        </div>
                                        <input type="number" name="stock" value="'.(isset($_POST['stock']) ? $_POST['stock'] : '50').'" min="0" required>
                                        <div class="param-hint">Cantidad en inventario</div>
                                    </div>
                                    
                                    <div class="param-group">
                                        <div class="param-header">
                                            <span class="param-icon">🎯</span>
                                            <label class="param-label">Descuento (%)</label>
                                        </div>
                                        <input type="number" name="descuento" value="'.(isset($_POST['descuento']) ? $_POST['descuento'] : '15').'" min="0" max="100" required>
                                        <div class="param-hint">Porcentaje de descuento a aplicar</div>
                                    </div>
                                </div>
                                ';
                                break;
                                
                            case 'Banco':
                                echo '
                                <div class="params-grid">
                                    <div class="param-group">
                                        <div class="param-header">
                                            <span class="param-icon">👤</span>
                                            <label class="param-label">Titular de la Cuenta</label>
                                        </div>
                                        <input type="text" name="titular" value="'.(isset($_POST['titular']) ? $_POST['titular'] : 'Carlos López').'" required>
                                        <div class="param-hint">Nombre del titular de la cuenta</div>
                                    </div>
                                    
                                    <div class="param-group">
                                        <div class="param-header">
                                            <span class="param-icon">💰</span>
                                            <label class="param-label">Saldo Inicial</label>
                                        </div>
                                        <input type="number" name="saldo" value="'.(isset($_POST['saldo']) ? $_POST['saldo'] : '1000').'" step="0.01" min="0" required>
                                        <div class="param-hint">Saldo inicial de la cuenta</div>
                                    </div>
                                    
                                    <div class="param-group">
                                        <div class="param-header">
                                            <span class="param-icon">💳</span>
                                            <label class="param-label">Monto a Depositar</label>
                                        </div>
                                        <input type="number" name="deposito" value="'.(isset($_POST['deposito']) ? $_POST['deposito'] : '500').'" step="0.01" min="0" required>
                                        <div class="param-hint">Cantidad a depositar</div>
                                    </div>
                                    
                                    <div class="param-group">
                                        <div class="param-header">
                                            <span class="param-icon">🏧</span>
                                            <label class="param-label">Monto a Retirar</label>
                                        </div>
                                        <input type="number" name="retiro" value="'.(isset($_POST['retiro']) ? $_POST['retiro'] : '200').'" step="0.01" min="0" required>
                                        <div class="param-hint">Cantidad a retirar</div>
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
                        <i>🚀</i> Crear Objeto y Ejecutar Métodos
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
        // --- DEFINICIÓN DE CLASES Y MÉTODOS ---
        
        class Usuario {
            private $nombre;
            private $apellido;
            private $edad;
            private $email;
            
            public function __construct($nombre, $apellido, $edad, $email) {
                $this->nombre = $nombre;
                $this->apellido = $apellido;
                $this->edad = $edad;
                $this->email = $email;
            }
            
            public function validar() {
                $errores = [];
                
                if (strlen($this->nombre) < 2) {
                    $errores[] = "El nombre debe tener al menos 2 caracteres";
                }
                
                if (strlen($this->apellido) < 2) {
                    $errores[] = "El apellido debe tener al menos 2 caracteres";
                }
                
                if ($this->edad < 18) {
                    $errores[] = "El usuario debe ser mayor de edad";
                }
                
                if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
                    $errores[] = "El email no tiene un formato válido";
                }
                
                return [
                    'valido' => empty($errores),
                    'errores' => $errores
                ];
            }
            
            public function generarEmail() {
                $dominio = explode('@', $this->email)[1];
                $emailBase = strtolower($this->nombre . '.' . $this->apellido);
                $emailBase = str_replace(' ', '', $emailBase);
                return $emailBase . '@' . $dominio;
            }
            
            public function esMayorDeEdad() {
                return $this->edad >= 18;
            }
            
            public function getInfo() {
                return [
                    'nombre' => $this->nombre,
                    'apellido' => $this->apellido,
                    'edad' => $this->edad,
                    'email' => $this->email
                ];
            }
        }
        
        class Calculadora {
            private $historial = [];
            
            public function sumar($a, $b) {
                $resultado = $a + $b;
                $this->historial[] = "{$a} + {$b} = {$resultado}";
                return $resultado;
            }
            
            public function promedio($numeros) {
                if (empty($numeros)) return 0;
                $suma = array_sum($numeros);
                $resultado = $suma / count($numeros);
                $this->historial[] = "Promedio de " . implode(', ', $numeros) . " = {$resultado}";
                return $resultado;
            }
            
            public static function esPar($numero) {
                return $numero % 2 == 0;
            }
            
            public function getHistorial() {
                return $this->historial;
            }
        }
        
        class Producto {
            private $nombre;
            private $precio;
            private $stock;
            
            public function __construct($nombre, $precio, $stock) {
                $this->nombre = $nombre;
                $this->precio = $precio;
                $this->stock = $stock;
            }
            
            public function aplicarDescuento($porcentaje) {
                $descuento = $this->precio * ($porcentaje / 100);
                $nuevoPrecio = $this->precio - $descuento;
                return [
                    'precio_original' => $this->precio,
                    'descuento' => $descuento,
                    'nuevo_precio' => $nuevoPrecio,
                    'porcentaje' => $porcentaje
                ];
            }
            
            public function estaDisponible() {
                return $this->stock > 0;
            }
            
            public function actualizarStock($cantidad) {
                $this->stock += $cantidad;
                return [
                    'stock_anterior' => $this->stock - $cantidad,
                    'stock_actual' => $this->stock,
                    'cambio' => $cantidad
                ];
            }
            
            public function getInfo() {
                return [
                    'nombre' => $this->nombre,
                    'precio' => $this->precio,
                    'stock' => $this->stock
                ];
            }
        }
        
        class Banco {
            private $titular;
            private $saldo;
            
            public function __construct($titular, $saldo = 0) {
                $this->titular = $titular;
                $this->saldo = $saldo;
            }
            
            public function depositar($monto) {
                $this->saldo += $monto;
                return [
                    'accion' => 'deposito',
                    'monto' => $monto,
                    'saldo_anterior' => $this->saldo - $monto,
                    'saldo_actual' => $this->saldo
                ];
            }
            
            public function retirar($monto) {
                if ($monto > $this->saldo) {
                    return [
                        'accion' => 'retiro',
                        'estado' => 'fallido',
                        'mensaje' => 'Fondos insuficientes',
                        'monto_solicitado' => $monto,
                        'saldo_actual' => $this->saldo
                    ];
                }
                
                $this->saldo -= $monto;
                return [
                    'accion' => 'retiro',
                    'estado' => 'exitoso',
                    'monto' => $monto,
                    'saldo_anterior' => $this->saldo + $monto,
                    'saldo_actual' => $this->saldo
                ];
            }
            
            public function calcularInteres($tasa, $periodo) {
                $interes = $this->saldo * ($tasa / 100) * $periodo;
                return [
                    'saldo_actual' => $this->saldo,
                    'tasa' => $tasa,
                    'periodo' => $periodo,
                    'interes_generado' => $interes,
                    'saldo_futuro' => $this->saldo + $interes
                ];
            }
            
            public function getInfo() {
                return [
                    'titular' => $this->titular,
                    'saldo' => $this->saldo
                ];
            }
        }
        
        // --- EJECUCIÓN DE MÉTODOS ---
        
        if (isset($_POST['ejecutar'])) {
            $clase = $_POST['clase'];
            
            echo '<div class="result-section fade-in">';
            echo '<div class="card">';
            echo '<h2 class="card-title"><i>📊</i> Resultado de la Ejecución - Clase ' . $clase . '</h2>';
            
            // Mostrar parámetros utilizados
            echo '<div class="object-state">';
            echo '<h3 style="color: var(--info); margin-bottom: 1rem;">📝 Estado del Objeto:</h3>';
            echo '<div class="state-grid">';
            foreach ($_POST as $key => $value) {
                if ($key != 'ejecutar' && $key != 'clase' && !empty($value)) {
                    echo '<div class="state-item">';
                    echo '<div class="state-name">' . htmlspecialchars($key) . '</div>';
                    echo '<div class="state-value">' . htmlspecialchars($value) . '</div>';
                    echo '</div>';
                }
            }
            echo '</div>';
            echo '</div>';
            
            // Ejecutar métodos según la clase seleccionada
            echo '<div class="execution-flow">';
            echo '<h3 style="color: var(--primary); margin-bottom: 1rem;">🔍 Flujo de Ejecución de Métodos:</h3>';
            
            switch ($clase) {
                case 'Usuario':
                    $usuario = new Usuario(
                        $_POST['nombre'],
                        $_POST['apellido'],
                        intval($_POST['edad']),
                        $_POST['email']
                    );
                    
                    echo '<div class="flow-step">';
                    echo '<div class="step-number">1</div>';
                    echo '<div>Objeto <strong>Usuario</strong> creado con el constructor</div>';
                    echo '</div>';
                    
                    $validacion = $usuario->validar();
                    echo '<div class="flow-step">';
                    echo '<div class="step-number">2</div>';
                    echo '<div>Método <strong>validar()</strong> ejecutado</div>';
                    echo '</div>';
                    
                    $emailGenerado = $usuario->generarEmail();
                    echo '<div class="flow-step">';
                    echo '<div class="step-number">3</div>';
                    echo '<div>Método <strong>generarEmail()</strong> ejecutado</div>';
                    echo '</div>';
                    
                    $mayorEdad = $usuario->esMayorDeEdad();
                    echo '<div class="flow-step">';
                    echo '<div class="step-number">4</div>';
                    echo '<div>Método <strong>esMayorDeEdad()</strong> ejecutado</div>';
                    echo '</div>';
                    
                    echo '<div class="result-display">';
                    echo '<h3>👤 Resultados del Usuario</h3>';
                    if ($validacion['valido']) {
                        echo '<div class="result-value" style="color: var(--success);">USUARIO VÁLIDO</div>';
                    } else {
                        echo '<div class="result-value" style="color: var(--danger);">USUARIO INVÁLIDO</div>';
                        echo '<div style="color: var(--danger); margin: 1rem 0;">';
                        foreach ($validacion['errores'] as $error) {
                            echo '• ' . $error . '<br>';
                        }
                        echo '</div>';
                    }
                    echo '<div style="color: var(--text-secondary); margin-top: 1rem;">';
                    echo 'Email generado: <strong>' . $emailGenerado . '</strong><br>';
                    echo 'Mayor de edad: <strong>' . ($mayorEdad ? 'Sí' : 'No') . '</strong>';
                    echo '</div>';
                    echo '</div>';
                    break;
                    
                case 'Calculadora':
                    $calculadora = new Calculadora();
                    $num1 = floatval($_POST['num1']);
                    $num2 = floatval($_POST['num2']);
                    $numerosArray = array_map('floatval', explode(',', $_POST['numeros']));
                    
                    echo '<div class="flow-step">';
                    echo '<div class="step-number">1</div>';
                    echo '<div>Objeto <strong>Calculadora</strong> creado</div>';
                    echo '</div>';
                    
                    $suma = $calculadora->sumar($num1, $num2);
                    echo '<div class="flow-step">';
                    echo '<div class="step-number">2</div>';
                    echo '<div>Método <strong>sumar(' . $num1 . ', ' . $num2 . ')</strong> ejecutado</div>';
                    echo '</div>';
                    
                    $promedio = $calculadora->promedio($numerosArray);
                    echo '<div class="flow-step">';
                    echo '<div class="step-number">3</div>';
                    echo '<div>Método <strong>promedio()</strong> ejecutado con los números: ' . implode(', ', $numerosArray) . '</div>';
                    echo '</div>';
                    
                    $esPar = Calculadora::esPar($num1);
                    echo '<div class="flow-step">';
                    echo '<div class="step-number">4</div>';
                    echo '<div>Método estático <strong>esPar(' . $num1 . ')</strong> ejecutado</div>';
                    echo '</div>';
                    
                    echo '<div class="result-display">';
                    echo '<h3>🧮 Resultados de la Calculadora</h3>';
                    echo '<div class="result-value">' . $suma . '</div>';
                    echo '<div style="color: var(--text-secondary);">';
                    echo $num1 . ' + ' . $num2 . ' = ' . $suma . '<br>';
                    echo 'Promedio: ' . number_format($promedio, 2) . '<br>';
                    echo $num1 . ' es ' . ($esPar ? 'par' : 'impar') . '<br>';
                    echo '</div>';
                    echo '</div>';
                    
                    $historial = $calculadora->getHistorial();
                    if (!empty($historial)) {
                        echo '<div class="object-state">';
                        echo '<h4 style="color: var(--accent);">📋 Historial de Operaciones:</h4>';
                        foreach ($historial as $operacion) {
                            echo '<div class="method-item">' . $operacion . '</div>';
                        }
                        echo '</div>';
                    }
                    break;
                    
                case 'Producto':
                    $producto = new Producto(
                        $_POST['nombre_producto'],
                        floatval($_POST['precio']),
                        intval($_POST['stock'])
                    );
                    $descuento = intval($_POST['descuento']);
                    
                    echo '<div class="flow-step">';
                    echo '<div class="step-number">1</div>';
                    echo '<div>Objeto <strong>Producto</strong> creado con el constructor</div>';
                    echo '</div>';
                    
                    $infoDescuento = $producto->aplicarDescuento($descuento);
                    echo '<div class="flow-step">';
                    echo '<div class="step-number">2</div>';
                    echo '<div>Método <strong>aplicarDescuento(' . $descuento . '%)</strong> ejecutado</div>';
                    echo '</div>';
                    
                    $disponible = $producto->estaDisponible();
                    echo '<div class="flow-step">';
                    echo '<div class="step-number">3</div>';
                    echo '<div>Método <strong>estaDisponible()</strong> ejecutado</div>';
                    echo '</div>';
                    
                    $actualizacionStock = $producto->actualizarStock(-5); // Simular venta
                    echo '<div class="flow-step">';
                    echo '<div class="step-number">4</div>';
                    echo '<div>Método <strong>actualizarStock(-5)</strong> ejecutado</div>';
                    echo '</div>';
                    
                    echo '<div class="result-display">';
                    echo '<h3>📦 Resultados del Producto</h3>';
                    echo '<div class="result-value">$' . number_format($infoDescuento['nuevo_precio'], 2) . '</div>';
                    echo '<div style="color: var(--text-secondary);">';
                    echo 'Precio original: $' . number_format($infoDescuento['precio_original'], 2) . '<br>';
                    echo 'Descuento aplicado: ' . $descuento . '% ($' . number_format($infoDescuento['descuento'], 2) . ')<br>';
                    echo 'Disponible: ' . ($disponible ? 'Sí' : 'No') . '<br>';
                    echo 'Stock actualizado: ' . $actualizacionStock['stock_actual'] . ' unidades';
                    echo '</div>';
                    echo '</div>';
                    break;
                    
                case 'Banco':
                    $banco = new Banco(
                        $_POST['titular'],
                        floatval($_POST['saldo'])
                    );
                    $deposito = floatval($_POST['deposito']);
                    $retiro = floatval($_POST['retiro']);
                    
                    echo '<div class="flow-step">';
                    echo '<div class="step-number">1</div>';
                    echo '<div>Objeto <strong>Banco</strong> creado con el constructor</div>';
                    echo '</div>';
                    
                    $resultadoDeposito = $banco->depositar($deposito);
                    echo '<div class="flow-step">';
                    echo '<div class="step-number">2</div>';
                    echo '<div>Método <strong>depositar($' . $deposito . ')</strong> ejecutado</div>';
                    echo '</div>';
                    
                    $resultadoRetiro = $banco->retirar($retiro);
                    echo '<div class="flow-step">';
                    echo '<div class="step-number">3</div>';
                    echo '<div>Método <strong>retirar($' . $retiro . ')</strong> ejecutado</div>';
                    echo '</div>';
                    
                    $interes = $banco->calcularInteres(5, 1); // 5% anual
                    echo '<div class="flow-step">';
                    echo '<div class="step-number">4</div>';
                    echo '<div>Método <strong>calcularInteres(5%, 1 año)</strong> ejecutado</div>';
                    echo '</div>';
                    
                    echo '<div class="result-display">';
                    echo '<h3>🏦 Resultados Bancarios</h3>';
                    echo '<div class="result-value">$' . number_format($resultadoRetiro['saldo_actual'], 2) . '</div>';
                    echo '<div style="color: var(--text-secondary);">';
                    echo 'Depósito: $' . $deposito . ' → Saldo: $' . number_format($resultadoDeposito['saldo_actual'], 2) . '<br>';
                    if ($resultadoRetiro['estado'] == 'exitoso') {
                        echo 'Retiro: $' . $retiro . ' → Saldo: $' . number_format($resultadoRetiro['saldo_actual'], 2) . '<br>';
                    } else {
                        echo 'Retiro fallido: ' . $resultadoRetiro['mensaje'] . '<br>';
                    }
                    echo 'Interés anual (5%): $' . number_format($interes['interes_generado'], 2);
                    echo '</div>';
                    echo '</div>';
                    break;
            }
            
            echo '</div>'; // cierre execution-flow
            
            // Mostrar código de la clase
            echo '<div class="code-preview">';
            echo '<h3 style="color: var(--primary); margin-bottom: 1rem;">📝 Código de la Clase ' . $clase . ':</h3>';
            echo '<pre>';
            switch ($clase) {
                case 'Usuario':
                    echo "class Usuario {\n";
                    echo "    private \$nombre;\n";
                    echo "    private \$apellido;\n";
                    echo "    private \$edad;\n";
                    echo "    private \$email;\n";
                    echo "    \n";
                    echo "    public function __construct(\$nombre, \$apellido, \$edad, \$email) {\n";
                    echo "        \$this->nombre = \$nombre;\n";
                    echo "        \$this->apellido = \$apellido;\n";
                    echo "        \$this->edad = \$edad;\n";
                    echo "        \$this->email = \$email;\n";
                    echo "    }\n";
                    echo "    \n";
                    echo "    public function validar() {\n";
                    echo "        // Lógica de validación\n";
                    echo "        return \$errores;\n";
                    echo "    }\n";
                    echo "    \n";
                    echo "    public function generarEmail() {\n";
                    echo "        // Generar email corporativo\n";
                    echo "        return \$email;\n";
                    echo "    }\n";
                    echo "}";
                    break;
                    
                case 'Calculadora':
                    echo "class Calculadora {\n";
                    echo "    private \$historial = [];\n";
                    echo "    \n";
                    echo "    public function sumar(\$a, \$b) {\n";
                    echo "        \$resultado = \$a + \$b;\n";
                    echo "        \$this->historial[] = \"{\$a} + {\$b} = {\$resultado}\";\n";
                    echo "        return \$resultado;\n";
                    echo "    }\n";
                    echo "    \n";
                    echo "    public function promedio(\$numeros) {\n";
                    echo "        // Calcular promedio\n";
                    echo "        return \$resultado;\n";
                    echo "    }\n";
                    echo "    \n";
                    echo "    public static function esPar(\$numero) {\n";
                    echo "        return \$numero % 2 == 0;\n";
                    echo "    }\n";
                    echo "}";
                    break;
                    
                case 'Producto':
                    echo "class Producto {\n";
                    echo "    private \$nombre;\n";
                    echo "    private \$precio;\n";
                    echo "    private \$stock;\n";
                    echo "    \n";
                    echo "    public function __construct(\$nombre, \$precio, \$stock) {\n";
                    echo "        \$this->nombre = \$nombre;\n";
                    echo "        \$this->precio = \$precio;\n";
                    echo "        \$this->stock = \$stock;\n";
                    echo "    }\n";
                    echo "    \n";
                    echo "    public function aplicarDescuento(\$porcentaje) {\n";
                    echo "        // Aplicar descuento al precio\n";
                    echo "        return \$resultado;\n";
                    echo "    }\n";
                    echo "    \n";
                    echo "    public function estaDisponible() {\n";
                    echo "        return \$this->stock > 0;\n";
                    echo "    }\n";
                    echo "}";
                    break;
                    
                case 'Banco':
                    echo "class Banco {\n";
                    echo "    private \$titular;\n";
                    echo "    private \$saldo;\n";
                    echo "    \n";
                    echo "    public function __construct(\$titular, \$saldo = 0) {\n";
                    echo "        \$this->titular = \$titular;\n";
                    echo "        \$this->saldo = \$saldo;\n";
                    echo "    }\n";
                    echo "    \n";
                    echo "    public function depositar(\$monto) {\n";
                    echo "        \$this->saldo += \$monto;\n";
                    echo "        return \$resultado;\n";
                    echo "    }\n";
                    echo "    \n";
                    echo "    public function retirar(\$monto) {\n";
                    echo "        // Lógica para retirar dinero\n";
                    echo "        return \$resultado;\n";
                    echo "    }\n";
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
            <h3>🎓 Conceptos de Métodos en Programación Orientada a Objetos</h3>
            <div class="feature-list">
                <div class="feature-item">
                    <span class="feature-icon">🏗️</span>
                    <div>
                        <strong>Clases y Objetos</strong>
                        <p>Las clases son plantillas y los objetos son instancias concretas con estado y comportamiento</p>
                    </div>
                </div>
                <div class="feature-item">
                    <span class="feature-icon">⚡</span>
                    <div>
                        <strong>Constructor</strong>
                        <p>Método especial __construct() que se ejecuta al crear un nuevo objeto</p>
                    </div>
                </div>
                <div class="feature-item">
                    <span class="feature-icon">📤</span>
                    <div>
                        <strong>Métodos de Instancia</strong>
                        <p>Operan sobre el estado del objeto específico usando $this</p>
                    </div>
                </div>
                <div class="feature-item">
                    <span class="feature-icon">⭐</span>
                    <div>
                        <strong>Métodos Estáticos</strong>
                        <p>Pertenecen a la clase, no a instancias específicas. Se llaman con Class::metodo()</p>
                    </div>
                </div>
            </div>
            
            <div class="syntax-example">
                <h4 style="color: var(--primary); margin-bottom: 1rem;">💡 Sintaxis de Métodos:</h4>
                <div class="method-call">
                    // Crear objeto<br>
                    $objeto = new Clase($param1, $param2);<br><br>
                    
                    // Llamar método de instancia<br>
                    $resultado = $objeto->metodoInstancia($param);<br><br>
                    
                    // Llamar método estático<br>
                    $resultado = Clase::metodoEstatico($param);
                </div>
            </div>
            
            <div style="background: var(--dark-card); padding: 1.5rem; border-radius: 10px; margin-top: 1.5rem;">
                <h4 style="color: var(--primary); margin-bottom: 1rem;">🎯 Tipos de Métodos Comunes:</h4>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1rem;">
                    <div>
                        <div style="font-weight: bold; color: var(--secondary);">🔧 Getters/Setters</div>
                        <div style="font-size: 0.9rem; color: var(--text-secondary);">Acceder y modificar propiedades privadas</div>
                    </div>
                    <div>
                        <div style="font-weight: bold; color: var(--secondary);">✅ Validadores</div>
                        <div style="font-size: 0.9rem; color: var(--text-secondary);">Verificar estado o datos del objeto</div>
                    </div>
                    <div>
                        <div style="font-weight: bold; color: var(--secondary);">🔄 Manipuladores</div>
                        <div style="font-size: 0.9rem; color: var(--text-secondary);">Modificar el estado interno del objeto</div>
                    </div>
                    <div>
                        <div style="font-weight: bold; color: var(--secondary);">📊 Calculadores</div>
                        <div style="font-size: 0.9rem; color: var(--text-secondary);">Realizar operaciones y devolver resultados</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function selectClass(clase) {
            document.getElementById('claseInput').value = clase;
            
            // Actualizar tarjetas activas
            document.querySelectorAll('.class-card').forEach(card => {
                card.classList.remove('active');
            });
            event.currentTarget.classList.add('active');
            
            // Recargar para mostrar nuevos parámetros
            document.getElementById('classForm').submit();
        }
        
        function resetForm() {
            document.getElementById('classForm').reset();
            document.getElementById('claseInput').value = 'Usuario';
            
            // Reactivar la primera tarjeta
            document.querySelectorAll('.class-card').forEach((card, index) => {
                card.classList.remove('active');
                if (index === 0) card.classList.add('active');
            });
        }
        
        function loadExample() {
            const currentClass = document.getElementById('claseInput').value;
            
            switch (currentClass) {
                case 'Usuario':
                    document.querySelector('input[name="nombre"]').value = 'María';
                    document.querySelector('input[name="apellido"]').value = 'López';
                    document.querySelector('input[name="edad"]').value = '22';
                    document.querySelector('input[name="email"]').value = 'maria.lopez@empresa.com';
                    break;
                case 'Calculadora':
                    document.querySelector('input[name="num1"]').value = '15';
                    document.querySelector('input[name="num2"]').value = '8';
                    document.querySelector('input[name="numeros"]').value = '5,10,15,20,25';
                    break;
                case 'Producto':
                    document.querySelector('input[name="nombre_producto"]').value = 'Smartphone Pro';
                    document.querySelector('input[name="precio"]').value = '899.99';
                    document.querySelector('input[name="stock"]').value = '25';
                    document.querySelector('input[name="descuento"]').value = '20';
                    break;
                case 'Banco':
                    document.querySelector('input[name="titular"]').value = 'Ana Martínez';
                    document.querySelector('input[name="saldo"]').value = '2500';
                    document.querySelector('input[name="deposito"]').value = '300';
                    document.querySelector('input[name="retiro"]').value = '150';
                    break;
            }
        }
        
        // Efectos de interacción
        document.addEventListener('DOMContentLoaded', function() {
            const inputs = document.querySelectorAll('input, select, textarea');
            const cards = document.querySelectorAll('.class-card');
            
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