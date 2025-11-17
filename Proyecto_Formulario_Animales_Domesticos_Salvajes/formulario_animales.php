<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Animales - JSON Database</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 1000px;
            margin: 0 auto;
        }

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

        .header p {
            font-size: 1.1rem;
            opacity: 0.9;
        }

        .dashboard {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 20px;
            color: white;
            text-align: center;
            border: 1px solid rgba(255, 255, 255, 0.3);
            transition: transform 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-number {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .stat-label {
            font-size: 0.9rem;
            opacity: 0.9;
        }

        .form-card {
            background: white;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 25px;
            position: relative;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #333;
            font-size: 1rem;
        }

        .input-icon {
            position: relative;
        }

        .input-icon i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #667eea;
            z-index: 2;
        }

        .input-icon input,
        .input-icon select,
        .input-icon textarea {
            width: 100%;
            padding: 12px 15px 12px 45px;
            border: 2px solid #e1e5ee;
            border-radius: 10px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: #f8f9fa;
        }

        .input-icon input:focus,
        .input-icon select:focus,
        .input-icon textarea:focus {
            border-color: #667eea;
            background: white;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
            outline: none;
        }

        .checkbox-group {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
            margin-top: 10px;
        }

        .checkbox-item {
            display: flex;
            align-items: center;
            padding: 12px;
            background: #f8f9fa;
            border-radius: 10px;
            border: 2px solid #e1e5ee;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .checkbox-item:hover {
            border-color: #667eea;
            background: white;
        }

        .checkbox-item input {
            margin-right: 10px;
            transform: scale(1.2);
        }

        .btn-submit {
            width: 100%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px;
            border: none;
            border-radius: 10px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }

        .view-data {
            text-align: center;
            margin-top: 20px;
        }

        .btn-view {
            display: inline-block;
            background: rgba(255, 255, 255, 0.2);
            color: white;
            padding: 12px 25px;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            border: 1px solid rgba(255, 255, 255, 0.3);
            margin: 5px;
        }

        .btn-view:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: translateY(-2px);
        }

        .database-info {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            padding: 15px;
            margin-top: 20px;
            text-align: center;
            color: white;
        }

        @media (max-width: 768px) {
            .form-card {
                padding: 30px 20px;
            }
            
            .header h1 {
                font-size: 2rem;
            }
            
            .checkbox-group {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div style="font-size: 4rem; margin-bottom: 20px;">🐾</div>
            <h1>Sistema de Registro de Animales</h1>
            <p>Base de datos JSON - Almacenamiento local</p>
        </div>

        <!-- Dashboard de estadísticas -->
        <div class="dashboard">
            <?php
            require_once 'config.php';
            $db = new JSONDatabase();
            $stats = $db->getAnimalStats();
            ?>
            <div class="stat-card">
                <div class="stat-number"><?php echo $stats['total']; ?></div>
                <div class="stat-label">Total Animales</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo $stats['domesticos']; ?></div>
                <div class="stat-label">Animales Domésticos</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo $stats['salvajes']; ?></div>
                <div class="stat-label">Animales Salvajes</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo count($stats['por_caracteristica']); ?></div>
                <div class="stat-label">Tipos de Características</div>
            </div>
        </div>

        <div class="form-card">
            <form action="procesar_animales.php" method="POST">
                <div class="form-group">
                    <label for="nombre">Nombre del Animal</label>
                    <div class="input-icon">
                        <i class="fas fa-paw"></i>
                        <input type="text" id="nombre" name="nombre" placeholder="Ej: León, Canario, Perro, etc." required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="tipo">Tipo de Animal</label>
                    <div class="input-icon">
                        <i class="fas fa-tag"></i>
                        <select id="tipo" name="tipo" required>
                            <option value="">Seleccione un tipo</option>
                            <option value="domestico">🐕 Doméstico</option>
                            <option value="salvaje">🐅 Salvaje</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="especie">Especie (Nombre científico)</label>
                    <div class="input-icon">
                        <i class="fas fa-dna"></i>
                        <input type="text" id="especie" name="especie" placeholder="Ej: Felis catus, Panthera leo, Canis lupus, etc." required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="edad">Edad (años)</label>
                    <div class="input-icon">
                        <i class="fas fa-birthday-cake"></i>
                        <input type="number" id="edad" name="edad" min="0" max="100" placeholder="0" required>
                    </div>
                </div>

                <div class="form-group">
                    <label>Características (Selecciona todas las que apliquen)</label>
                    <div class="checkbox-group">
                        <div class="checkbox-item">
                            <input type="checkbox" name="caracteristicas[]" value="mamifero" id="mamifero">
                            <label for="mamifero">🐾 Mamífero</label>
                        </div>
                        <div class="checkbox-item">
                            <input type="checkbox" name="caracteristicas[]" value="ave" id="ave">
                            <label for="ave">🦅 Ave</label>
                        </div>
                        <div class="checkbox-item">
                            <input type="checkbox" name="caracteristicas[]" value="reptil" id="reptil">
                            <label for="reptil">🦎 Reptil</label>
                        </div>
                        <div class="checkbox-item">
                            <input type="checkbox" name="caracteristicas[]" value="acuatico" id="acuatico">
                            <label for="acuatico">🐠 Acuático</label>
                        </div>
                        <div class="checkbox-item">
                            <input type="checkbox" name="caracteristicas[]" value="carnivoro" id="carnivoro">
                            <label for="carnivoro">🥩 Carnívoro</label>
                        </div>
                        <div class="checkbox-item">
                            <input type="checkbox" name="caracteristicas[]" value="herbivoro" id="herbivoro">
                            <label for="herbivoro">🌿 Herbívoro</label>
                        </div>
                        <div class="checkbox-item">
                            <input type="checkbox" name="caracteristicas[]" value="omnivoro" id="omnivoro">
                            <label for="omnivoro">🍎 Omnívoro</label>
                        </div>
                        <div class="checkbox-item">
                            <input type="checkbox" name="caracteristicas[]" value="volador" id="volador">
                            <label for="volador">🕊️ Volador</label>
                        </div>
                        <div class="checkbox-item">
                            <input type="checkbox" name="caracteristicas[]" value="terrestre" id="terrestre">
                            <label for="terrestre">🏞️ Terrestre</label>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="habitat">Hábitat Natural</label>
                    <div class="input-icon">
                        <i class="fas fa-mountain"></i>
                        <textarea id="habitat" name="habitat" rows="3" placeholder="Describa el hábitat natural del animal (ej: Selva tropical, Sabana africana, Hogar doméstico, etc.)"></textarea>
                    </div>
                </div>

                <button type="submit" class="btn-submit">
                    <i class="fas fa-save"></i> Guardar en Base de Datos JSON
                </button>
            </form>
        </div>

        <div class="view-data">
            <a href="ver_animales.php" class="btn-view">
                <i class="fas fa-database"></i> Ver Base de Datos Completa
            </a>
            <a href="procesar_animales.php?action=export" class="btn-view">
                <i class="fas fa-download"></i> Exportar Reporte
            </a>
        </div>

        <div class="database-info">
            <i class="fas fa-info-circle"></i> 
            Base de datos: <strong>animales.json</strong> | 
            Registros: <strong><?php echo $stats['total']; ?></strong> | 
            Última actualización: <strong><?php echo date('d/m/Y H:i'); ?></strong>
        </div>
    </div>

    <script>
        // Efectos interactivos
        document.addEventListener('DOMContentLoaded', function() {
            // Animación de inputs
            const inputs = document.querySelectorAll('input, select, textarea');
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.parentElement.style.transform = 'scale(1.02)';
                });
                
                input.addEventListener('blur', function() {
                    this.parentElement.style.transform = 'scale(1)';
                });
            });

            // Validación en tiempo real
            const nombreInput = document.getElementById('nombre');
            nombreInput.addEventListener('input', function() {
                if (this.value.length < 2) {
                    this.style.borderColor = '#ff4444';
                } else {
                    this.style.borderColor = '#4CAF50';
                }
            });

            // Mostrar/ocultar características según tipo
            const tipoSelect = document.getElementById('tipo');
            tipoSelect.addEventListener('change', function() {
                const caracteristicas = document.querySelectorAll('.checkbox-item');
                caracteristicas.forEach(item => {
                    item.style.opacity = '1';
                });
            });
        });
    </script>
</body>
</html>