<?php
// Ejemplo 1: Clasificación de autos por precio
function clasificarAutoPorPrecio($precio) {
    echo "<div class='result-box'>";
    echo "<h3>🎯 Clasificación por precio</h3>";
    
    if ($precio < 10000) {
        echo "<div class='result-economico'>";
        echo "💰 <strong>Auto ECONÓMICO</strong> - Precio: $$precio<br>";
        echo "🎯 Ideal para primera compra o uso urbano";
        echo "</div>";
    } elseif ($precio >= 10000 && $precio < 30000) {
        echo "<div class='result-intermedio'>";
        echo "🚗 <strong>Auto INTERMEDIO</strong> - Precio: $$precio<br>";
        echo "⚡ Buen balance entre precio y características";
        echo "</div>";
    } elseif ($precio >= 30000 && $precio < 60000) {
        echo "<div class='result-premium'>";
        echo "🏁 <strong>Auto PREMIUM</strong> - Precio: $$precio<br>";
        echo "💎 Mayor confort y tecnología incluida";
        echo "</div>";
    } else {
        echo "<div class='result-lujo'>";
        echo "🏎️ <strong>Auto DE LUJO</strong> - Precio: $$precio<br>";
        echo "🌟 Máxima gama con todas las opciones";
        echo "</div>";
    }
    echo "</div>";
}

// Ejemplo 2: Evaluación de eficiencia de combustible
function evaluarEficienciaCombustible($kmPorLitro) {
    echo "<div class='result-box'>";
    echo "<h3>⛽ Eficiencia de combustible</h3>";
    
    if ($kmPorLitro < 8) {
        echo "<div class='result-alto'>";
        echo "⛽ <strong>CONSUMO ALTO</strong> - $kmPorLitro km/L<br>";
        echo "⚠️ Considerar uso en trayectos cortos";
        echo "</div>";
    } elseif ($kmPorLitro >= 8 && $kmPorLitro < 12) {
        echo "<div class='result-moderado'>";
        echo "🚙 <strong>CONSUMO MODERADO</strong> - $kmPorLitro km/L<br>";
        echo "✅ Adecuado para uso mixto ciudad/carretera";
        echo "</div>";
    } elseif ($kmPorLitro >= 12 && $kmPorLitro < 18) {
        echo "<div class='result-eficiente'>";
        echo "🚗 <strong>CONSUMO EFICIENTE</strong> - $kmPorLitro km/L<br>";
        echo "💚 Ideal para ahorro de combustible";
        echo "</div>";
    } else {
        echo "<div class='result-muy-eficiente'>";
        echo "🔋 <strong>CONSUMO MUY EFICIENTE</strong> - $kmPorLitro km/L<br>";
        echo "🏆 Excelente para viajes largos";
        echo "</div>";
    }
    echo "</div>";
}

// Ejemplo 3: Determinar tipo de auto por tamaño
function determinarTipoAuto($longitud, $pasajeros) {
    echo "<div class='result-box'>";
    echo "<h3>📏 Clasificación por tamaño</h3>";
    
    if ($longitud < 4.0 && $pasajeros <= 4) {
        echo "<div class='result-compacto'>";
        echo "🚗 <strong>AUTO COMPACTO</strong><br>";
        echo "📍 Longitud: {$longitud}m - Pasajeros: $pasajeros<br>";
        echo "🅿️ Perfecto para ciudad y estacionamiento fácil";
        echo "</div>";
    } elseif ($longitud >= 4.0 && $longitud < 4.8 && $pasajeros <= 5) {
        echo "<div class='result-sedan'>";
        echo "🚙 <strong>SEDÁN MEDIANO</strong><br>";
        echo "📍 Longitud: {$longitud}m - Pasajeros: $pasajeros<br>";
        echo "👨‍👩‍👧‍👦 Ideal para familia pequeña";
        echo "</div>";
    } elseif ($longitud >= 4.8 && $longitud < 5.2 && $pasajeros <= 7) {
        echo "<div class='result-suv'>";
        echo "🚐 <strong>SUV</strong><br>";
        echo "📍 Longitud: {$longitud}m - Pasajeros: $pasajeros<br>";
        echo "🏞️ Versátil para ciudad y aventuras";
        echo "</div>";
    } elseif ($longitud >= 5.2) {
        echo "<div class='result-camioneta'>";
        echo "🚐 <strong>CAMIONETA GRANDE</strong><br>";
        echo "📍 Longitud: {$longitud}m - Pasajeros: $pasajeros<br>";
        echo "🛻 Capacidad para carga y muchos pasajeros";
        echo "</div>";
    } else {
        echo "<div class='result-estandar'>";
        echo "🚗 <strong>AUTO ESTÁNDAR</strong><br>";
        echo "📍 Longitud: {$longitud}m - Pasajeros: $pasajeros";
        echo "</div>";
    }
    echo "</div>";
}

// Función principal que ejecuta todos los ejemplos
function ejecutarEjemplosIfElseif() {
    echo "<div class='examples-container'>";
    echo "<h1>🚗 Ejemplos de IF con ELSEIF - Mundo Automotriz 🚙</h1>";
    
    // Ejemplo 1
    clasificarAutoPorPrecio(8000);
    clasificarAutoPorPrecio(25000);
    clasificarAutoPorPrecio(45000);
    clasificarAutoPorPrecio(120000);
    
    // Ejemplo 2
    evaluarEficienciaCombustible(6);
    evaluarEficienciaCombustible(10);
    evaluarEficienciaCombustible(15);
    evaluarEficienciaCombustible(22);
    
    // Ejemplo 3
    determinarTipoAuto(3.8, 4);
    determinarTipoAuto(4.5, 5);
    determinarTipoAuto(5.0, 7);
    determinarTipoAuto(5.5, 8);
    
    echo "</div>";
}

// Ejecutar ejemplos solo si no hay formulario enviado
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    ejecutarEjemplosIfElseif();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Concesionaria - Tema Oscuro</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: #0f0f0f;
            color: #e0e0e0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            padding: 20px;
            background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
            min-height: 100vh;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 40px;
            padding: 30px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 15px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
        }

        .header h1 {
            font-size: 2.5em;
            background: linear-gradient(135deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 10px;
        }

        .header p {
            color: #b0b0b0;
            font-size: 1.1em;
        }

        .form-container {
            background: rgba(255, 255, 255, 0.05);
            padding: 30px;
            border-radius: 15px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            margin-bottom: 30px;
            backdrop-filter: blur(10px);
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #cccccc;
        }

        select, input[type="number"] {
            width: 100%;
            padding: 12px 15px;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 8px;
            color: #e0e0e0;
            font-size: 16px;
            transition: all 0.3s ease;
        }

        select:focus, input[type="number"]:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 2px rgba(102, 126, 234, 0.2);
        }

        select option {
            background: #2d2d2d;
            color: #e0e0e0;
        }

        button {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 18px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        button:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        .info-box {
            background: rgba(255, 255, 255, 0.05);
            padding: 25px;
            border-radius: 12px;
            border-left: 4px solid #667eea;
            margin: 25px 0;
            backdrop-filter: blur(10px);
        }

        .info-box h3 {
            color: #667eea;
            margin-bottom: 15px;
        }

        .code-example {
            background: #1a1a1a;
            color: #e0e0e0;
            padding: 20px;
            border-radius: 8px;
            font-family: 'Courier New', monospace;
            margin: 15px 0;
            border: 1px solid rgba(255, 255, 255, 0.1);
            overflow-x: auto;
        }

        .examples-container {
            margin-top: 40px;
        }

        .result-box {
            background: rgba(255, 255, 255, 0.05);
            padding: 20px;
            border-radius: 12px;
            margin: 20px 0;
            border: 1px solid rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
        }

        .result-box h3 {
            color: #764ba2;
            margin-bottom: 15px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            padding-bottom: 10px;
        }

        /* Estilos para los diferentes tipos de resultados */
        .result-economico {
            background: linear-gradient(135deg, rgba(76, 175, 80, 0.1), rgba(76, 175, 80, 0.05));
            padding: 15px;
            border-radius: 8px;
            border-left: 4px solid #4CAF50;
        }

        .result-intermedio {
            background: linear-gradient(135deg, rgba(33, 150, 243, 0.1), rgba(33, 150, 243, 0.05));
            padding: 15px;
            border-radius: 8px;
            border-left: 4px solid #2196F3;
        }

        .result-premium {
            background: linear-gradient(135deg, rgba(156, 39, 176, 0.1), rgba(156, 39, 176, 0.05));
            padding: 15px;
            border-radius: 8px;
            border-left: 4px solid #9C27B0;
        }

        .result-lujo {
            background: linear-gradient(135deg, rgba(255, 193, 7, 0.1), rgba(255, 193, 7, 0.05));
            padding: 15px;
            border-radius: 8px;
            border-left: 4px solid #FFC107;
        }

        .result-alto {
            background: linear-gradient(135deg, rgba(244, 67, 54, 0.1), rgba(244, 67, 54, 0.05));
            padding: 15px;
            border-radius: 8px;
            border-left: 4px solid #F44336;
        }

        .result-moderado {
            background: linear-gradient(135deg, rgba(255, 152, 0, 0.1), rgba(255, 152, 0, 0.05));
            padding: 15px;
            border-radius: 8px;
            border-left: 4px solid #FF9800;
        }

        .result-eficiente {
            background: linear-gradient(135deg, rgba(76, 175, 80, 0.1), rgba(76, 175, 80, 0.05));
            padding: 15px;
            border-radius: 8px;
            border-left: 4px solid #4CAF50;
        }

        .result-muy-eficiente {
            background: linear-gradient(135deg, rgba(0, 150, 136, 0.1), rgba(0, 150, 136, 0.05));
            padding: 15px;
            border-radius: 8px;
            border-left: 4px solid #009688;
        }

        .result-compacto, .result-sedan, .result-suv, .result-camioneta, .result-estandar {
            padding: 15px;
            border-radius: 8px;
            border-left: 4px solid;
        }

        .result-compacto { border-left-color: #FF5722; background: linear-gradient(135deg, rgba(255, 87, 34, 0.1), rgba(255, 87, 34, 0.05)); }
        .result-sedan { border-left-color: #3F51B5; background: linear-gradient(135deg, rgba(63, 81, 181, 0.1), rgba(63, 81, 181, 0.05)); }
        .result-suv { border-left-color: #E91E63; background: linear-gradient(135deg, rgba(233, 30, 99, 0.1), rgba(233, 30, 99, 0.05)); }
        .result-camioneta { border-left-color: #607D8B; background: linear-gradient(135deg, rgba(96, 125, 139, 0.1), rgba(96, 125, 139, 0.05)); }
        .result-estandar { border-left-color: #795548; background: linear-gradient(135deg, rgba(121, 85, 72, 0.1), rgba(121, 85, 72, 0.05)); }

        /* Scrollbar personalizado */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #1a1a1a;
        }

        ::-webkit-scrollbar-thumb {
            background: #667eea;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #764ba2;
        }

        @media (max-width: 768px) {
            .container {
                padding: 10px;
            }
            
            .header h1 {
                font-size: 2em;
            }
            
            .form-container {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🏁 Concesionaria "IF-ELSEIF"</h1>
            <p>Sistema de evaluación de vehículos usando condicionales múltiples</p>
        </div>
        
        <div class="info-box">
            <h3>💡 ¿Qué es IF-ELSEIF?</h3>
            <p>La estructura <strong>if-elseif</strong> permite evaluar múltiples condiciones en secuencia:</p>
            <div class="code-example">
                if (condición1) {<br>
                &nbsp;&nbsp;// Código si condición1 es verdadera<br>
                } elseif (condición2) {<br>
                &nbsp;&nbsp;// Código si condición2 es verdadera<br>
                } elseif (condición3) {<br>
                &nbsp;&nbsp;// Código si condición3 es verdadera<br>
                } else {<br>
                &nbsp;&nbsp;// Código si ninguna condición fue verdadera<br>
                }
            </div>
        </div>
        
        <div class="form-container">
            <h2 style="color: #667eea; margin-bottom: 20px;">📊 Evaluar Vehículo</h2>
            <form method="POST">
                <div class="form-group">
                    <label for="marca">Marca del vehículo:</label>
                    <select name="marca" id="marca" required>
                        <option value="">-- Selecciona marca --</option>
                        <option value="Toyota">Toyota</option>
                        <option value="Honda">Honda</option>
                        <option value="Ford">Ford</option>
                        <option value="Chevrolet">Chevrolet</option>
                        <option value="Nissan">Nissan</option>
                        <option value="Volkswagen">Volkswagen</option>
                        <option value="Hyundai">Hyundai</option>
                        <option value="Kia">Kia</option>
                        <option value="BMW">BMW</option>
                        <option value="Mercedes-Benz">Mercedes-Benz</option>
                        <option value="Audi">Audi</option>
                        <option value="Mazda">Mazda</option>
                        <option value="Subaru">Subaru</option>
                        <option value="Lexus">Lexus</option>
                        <option value="Acura">Acura</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="modelo">Modelo:</label>
                    <select name="modelo" id="modelo" required>
                        <option value="">-- Selecciona modelo --</option>
                        <option value="Corolla">Corolla</option>
                        <option value="Civic">Civic</option>
                        <option value="Focus">Focus</option>
                        <option value="Cruze">Cruze</option>
                        <option value="Sentra">Sentra</option>
                        <option value="Golf">Golf</option>
                        <option value="Elantra">Elantra</option>
                        <option value="Optima">Optima</option>
                        <option value="Camry">Camry</option>
                        <option value="Accord">Accord</option>
                        <option value="Fusion">Fusion</option>
                        <option value="Malibu">Malibu</option>
                        <option value="Altima">Altima</option>
                        <option value="Jetta">Jetta</option>
                        <option value="Sonata">Sonata</option>
                        <option value="Forte">Forte</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="ano">Año del vehículo:</label>
                    <select name="ano" id="ano" required>
                        <option value="">-- Selecciona año --</option>
                        <?php for ($i = 2024; $i >= 1990; $i--): ?>
                            <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="kilometraje">Kilometraje:</label>
                    <select name="kilometraje" id="kilometraje" required>
                        <option value="">-- Selecciona kilometraje --</option>
                        <option value="5000">0 - 10,000 km (Nuevo)</option>
                        <option value="25000">10,001 - 40,000 km (Poco uso)</option>
                        <option value="60000">40,001 - 80,000 km (Uso moderado)</option>
                        <option value="100000">80,001 - 120,000 km (Uso regular)</option>
                        <option value="150000">120,001 - 180,000 km (Alto kilometraje)</option>
                        <option value="200000">Más de 180,000 km (Muy usado)</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="precio">Precio (USD):</label>
                    <select name="precio" id="precio" required>
                        <option value="">-- Selecciona precio --</option>
                        <option value="5000">$0 - $10,000 (Económico)</option>
                        <option value="15000">$10,001 - $20,000 (Accesible)</option>
                        <option value="25000">$20,001 - $30,000 (Intermedio)</option>
                        <option value="40000">$30,001 - $50,000 (Premium)</option>
                        <option value="60000">$50,001 - $70,000 (Lujo)</option>
                        <option value="80000">Más de $70,000 (Alta gama)</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="tipo_uso">Tipo de uso principal:</label>
                    <select name="tipo_uso" id="tipo_uso" required>
                        <option value="">-- Selecciona uso --</option>
                        <option value="ciudad">🚗 Uso en ciudad</option>
                        <option value="carretera">🛣️ Uso en carretera</option>
                        <option value="familiar">👨‍👩‍👧‍👦 Uso familiar</option>
                        <option value="trabajo">💼 Uso laboral</option>
                        <option value="aventura">🏞️ Aventura/Off-road</option>
                    </select>
                </div>
                
                <button type="submit">Evaluar Vehículo</button>
                <button type="button" onclick="window.location.href=window.location.href" style="background: linear-gradient(135deg, #6c757d, #495057); margin-top: 10px;">
                    🔄 Limpiar Formulario
                </button>
            </form>
        </div>
        
        <?php
        // Procesar formulario solo si se envió
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $marca = htmlspecialchars($_POST['marca']);
            $modelo = htmlspecialchars($_POST['modelo']);
            $ano = intval($_POST['ano']);
            $kilometraje = intval($_POST['kilometraje']);
            $precio = intval($_POST['precio']);
            $tipo_uso = htmlspecialchars($_POST['tipo_uso']);
            
            echo "<div class='result-box'>";
            echo "<h3>🚗 Evaluación del Vehículo</h3>";
            echo "<div style='background: rgba(255,255,255,0.1); padding: 15px; border-radius: 8px; margin-bottom: 15px;'>";
            echo "<strong style='color: #667eea; font-size: 1.2em;'>$marca $modelo ($ano)</strong><br>";
            echo "Kilometraje: " . number_format($kilometraje) . " km<br>";
            echo "Precio: $" . number_format($precio) . "<br>";
            echo "Uso principal: $tipo_uso";
            echo "</div>";
            
            // Sistema de evaluación con if-elseif mejorado
            if ($ano >= 2020 && $kilometraje <= 25000 && $precio <= 25000) {
                echo "<div class='result-muy-eficiente'>";
                echo "🟢 <strong>EXCELENTE OFERTA</strong><br>";
                echo "💎 Vehículo moderno con poco uso<br>";
                echo "📈 Buena relación calidad-precio<br>";
                echo "✅ Recomendado para compra inmediata";
                echo "</div>";
            } elseif ($ano >= 2017 && $ano < 2020 && $kilometraje <= 60000 && $precio <= 18000) {
                echo "<div class='result-eficiente'>";
                echo "🔵 <strong>BUENA OPCIÓN</strong><br>";
                echo "⚡ Vehículo en buen estado<br>";
                echo "💡 Balance entre precio y condiciones<br>";
                echo "👍 Considerar prueba de manejo";
                echo "</div>";
            } elseif ($ano >= 2014 && $ano < 2017 && $kilometraje <= 100000 && $precio <= 12000) {
                echo "<div class='result-moderado'>";
                echo "🟡 <strong>OPCIÓN ECONÓMICA</strong><br>";
                echo "💰 Precio accesible<br>";
                echo "🔧 Puede requerir mantenimiento<br>";
                echo "📋 Verificar historial de servicio";
                echo "</div>";
            } elseif ($ano < 2014 || $kilometraje > 150000) {
                echo "<div class='result-alto'>";
                echo "🟠 <strong>VEHÍCULO USADO</strong><br>";
                echo "⏳ Alta antigüedad o kilometraje<br>";
                echo "🔍 Revisión mecánica obligatoria<br>";
                echo "💼 Considerar para segundo auto";
                echo "</div>";
            } else {
                echo "<div class='result-estandar'>";
                echo "⚪ <strong>EVALUAR PARTICULARIDADES</strong><br>";
                echo "📊 Combinación atípica de características<br>";
                echo "🔎 Recomendamos inspección profesional<br>";
                echo "💬 Consultar con nuestro equipo";
                echo "</div>";
            }
            
            // Recomendación adicional basada en el tipo de uso
            echo "<div style='margin-top: 20px; padding: 15px; background: rgba(102, 126, 234, 0.1); border-radius: 8px; border-left: 4px solid #667eea;'>";
            echo "<strong>💡 Recomendación para uso $tipo_uso:</strong><br>";
            
            if ($tipo_uso == "ciudad") {
                echo "🚗 Ideal para tráfico urbano, considera bajo consumo de combustible y fácil estacionamiento.";
            } elseif ($tipo_uso == "carretera") {
                echo "🛣️ Excelente para viajes largos, verifica confort y sistemas de seguridad avanzados.";
            } elseif ($tipo_uso == "familiar") {
                echo "👨‍👩‍👧‍👦 Perfecto para familia, prioriza espacio interior y sistemas de seguridad.";
            } elseif ($tipo_uso == "trabajo") {
                echo "💼 Adecuado para uso laboral, considera durabilidad y bajo costo de mantenimiento.";
            } elseif ($tipo_uso == "aventura") {
                echo "🏞️ Ideal para aventuras, verifica tracción y capacidad off-road.";
            }
            
            echo "</div>";
            echo "</div>";
        }
        ?>
    </div>

    <script>
        // Script para mejorar la experiencia de usuario
        document.addEventListener('DOMContentLoaded', function() {
            // Mantener los valores seleccionados después del envío del formulario
            <?php if ($_SERVER['REQUEST_METHOD'] == 'POST'): ?>
            document.getElementById('marca').value = '<?php echo $_POST['marca'] ?? ''; ?>';
            document.getElementById('modelo').value = '<?php echo $_POST['modelo'] ?? ''; ?>';
            document.getElementById('ano').value = '<?php echo $_POST['ano'] ?? ''; ?>';
            document.getElementById('kilometraje').value = '<?php echo $_POST['kilometraje'] ?? ''; ?>';
            document.getElementById('precio').value = '<?php echo $_POST['precio'] ?? ''; ?>';
            document.getElementById('tipo_uso').value = '<?php echo $_POST['tipo_uso'] ?? ''; ?>';
            <?php endif; ?>
        });
    </script>
</body>
</html>