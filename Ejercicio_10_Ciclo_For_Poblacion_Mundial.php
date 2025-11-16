<?php
// Simulador de Crecimiento Poblacional - Ciclo FOR
class CrecimientoPoblacional {
    private $datosPoblacion;
    private $eventosHistoricos;
    
    public function __construct() {
        // Datos históricos aproximados de población mundial (en millones)
        $this->datosPoblacion = [
            1914 => 1800, 1915 => 1810, 1916 => 1815, 1917 => 1810, 1918 => 1800,
            1919 => 1810, 1920 => 1860, 1921 => 1890, 1922 => 1920, 1923 => 1950,
            1924 => 1980, 1925 => 2010, 1926 => 2040, 1927 => 2070, 1928 => 2100,
            1929 => 2130, 1930 => 2170, 1931 => 2210, 1932 => 2250, 1933 => 2290,
            1934 => 2330, 1935 => 2370, 1936 => 2410, 1937 => 2450, 1938 => 2490,
            1939 => 2530, 1940 => 2570, 1941 => 2610, 1942 => 2650, 1943 => 2690,
            1944 => 2730, 1945 => 2770, 1946 => 2820, 1947 => 2870, 1948 => 2920,
            1949 => 2970, 1950 => 2536, 1951 => 2586, 1952 => 2636, 1953 => 2686,
            1954 => 2736, 1955 => 2786, 1956 => 2836, 1957 => 2886, 1958 => 2936,
            1959 => 2986, 1960 => 3035, 1961 => 3089, 1962 => 3144, 1963 => 3200,
            1964 => 3258, 1965 => 3318, 1966 => 3379, 1967 => 3442, 1968 => 3506,
            1969 => 3571, 1970 => 3638, 1971 => 3706, 1972 => 3775, 1973 => 3845,
            1974 => 3916, 1975 => 3988, 1976 => 4061, 1977 => 4135, 1978 => 4210,
            1979 => 4286, 1980 => 4362, 1981 => 4440, 1982 => 4518, 1983 => 4597,
            1984 => 4677, 1985 => 4758, 1986 => 4840, 1987 => 4923, 1988 => 5007,
            1989 => 5092, 1990 => 5178, 1991 => 5265, 1992 => 5353, 1993 => 5442,
            1994 => 5532, 1995 => 5623, 1996 => 5715, 1997 => 5808, 1998 => 5902,
            1999 => 5997, 2000 => 6093, 2001 => 6189, 2002 => 6286, 2003 => 6384,
            2004 => 6482, 2005 => 6581, 2006 => 6681, 2007 => 6781, 2008 => 6882,
            2009 => 6983, 2010 => 7085, 2011 => 7187, 2012 => 7289, 2013 => 7392,
            2014 => 7495, 2015 => 7598, 2016 => 7701, 2017 => 7804, 2018 => 7907,
            2019 => 8010, 2020 => 8110, 2021 => 8200, 2022 => 8280, 2023 => 8360,
            2024 => 8440
        ];
        
        // Eventos históricos significativos
        $this->eventosHistoricos = [
            1914 => "📯 Primera Guerra Mundial",
            1918 => "🦠 Gripe Española",
            1929 => "💸 Gran Depresión",
            1939 => "💥 Segunda Guerra Mundial",
            1945 => "☢️ Fin WWII / Era Nuclear",
            1950 => "🌍 ONU establecida",
            1960 => "🚀 Era Espacial",
            1970 => "📈 Boom poblacional",
            1980 => "💻 Revolución Digital",
            1990 => "🌐 Internet Global",
            2000 => "📱 Era Móvil",
            2010 => "🤖 IA y Big Data",
            2020 => "😷 Pandemia COVID-19"
        ];
    }
    
    // Obtener datos para JavaScript
    public function getDatosParaGrafico() {
        return json_encode([
            'anios' => array_keys($this->datosPoblacion),
            'poblaciones' => array_values($this->datosPoblacion),
            'eventos' => $this->eventosHistoricos
        ]);
    }
    
    // Ciclo FOR para mostrar crecimiento año por año
    public function mostrarCrecimientoHistorico($anioInicio = 1914, $anioFin = 2024) {
        echo "<div class='operacion-box'>";
        echo "<h3>📈 CRECIMIENTO POBLACIONAL MUNDIAL {$anioInicio}-{$anioFin}</h3>";
        echo "<div class='estado-inicial'>";
        echo "🌍 Período analizado: <strong>{$anioInicio} - {$anioFin}</strong><br>";
        echo "📊 Total de años: <strong>" . ($anioFin - $anioInicio + 1) . "</strong><br>";
        echo "👥 Población inicial: <strong>" . number_format($this->datosPoblacion[$anioInicio]) . " millones</strong><br>";
        echo "👥 Población final: <strong>" . number_format($this->datosPoblacion[$anioFin]) . " millones</strong>";
        echo "</div>";
        echo "</div>";
        
        $totalIncremento = 0;
        $crecimientoTotal = 0;
        
        // CICLO FOR - Recorriendo cada año en el rango especificado
        for ($anio = $anioInicio; $anio <= $anioFin; $anio++) {
            $poblacionActual = $this->datosPoblacion[$anio];
            $poblacionAnterior = isset($this->datosPoblacion[$anio-1]) ? $this->datosPoblacion[$anio-1] : $poblacionActual;
            
            $incremento = $poblacionActual - $poblacionAnterior;
            $tasaCrecimiento = $poblacionAnterior > 0 ? ($incremento / $poblacionAnterior) * 100 : 0;
            
            $totalIncremento += $incremento;
            $crecimientoTotal += $tasaCrecimiento;
            
            echo "<div class='anio-box' id='anio-{$anio}'>";
            echo "<h4>📅 AÑO {$anio}</h4>";
            
            // Información del año
            echo "<div class='info-anio'>";
            echo "👥 Población: <strong>" . number_format($poblacionActual) . " millones</strong><br>";
            
            if ($anio > $anioInicio) {
                $tendencia = $incremento >= 0 ? "📈" : "📉";
                $colorIncremento = $incremento >= 0 ? "incremento-positivo" : "incremento-negativo";
                echo "<span class='{$colorIncremento}'>{$tendencia} Cambio: " . ($incremento >= 0 ? "+" : "") . number_format($incremento) . " millones</span><br>";
                echo "📊 Tasa crecimiento: <strong>" . number_format($tasaCrecimiento, 2) . "%</strong>";
            }
            
            // Evento histórico si existe
            if (isset($this->eventosHistoricos[$anio])) {
                echo "<br>🎯 <strong>Evento: " . $this->eventosHistoricos[$anio] . "</strong>";
            }
            echo "</div>";
            
            // Gráfico simple de barras
            $porcentajePoblacion = ($poblacionActual / $this->datosPoblacion[$anioFin]) * 100;
            echo "<div class='grafico-barra'>";
            echo "<div class='barra-poblacion' style='width: {$porcentajePoblacion}%' title='{$poblacionActual} millones'>";
            echo "<span class='etiqueta-poblacion'>" . number_format($poblacionActual) . "M</span>";
            echo "</div>";
            echo "</div>";
            
            echo "</div>";
            
            // Pequeño delay visual entre años
            echo "<script>setTimeout(() => { document.getElementById('anio-{$anio}').scrollIntoView({ behavior: 'smooth', block: 'center' }); }, 200);</script>";
        }
        
        // Estadísticas finales
        $promedioCrecimiento = $crecimientoTotal / ($anioFin - $anioInicio);
        $incrementoTotal = $this->datosPoblacion[$anioFin] - $this->datosPoblacion[$anioInicio];
        $porcentajeCrecimiento = ($incrementoTotal / $this->datosPoblacion[$anioInicio]) * 100;
        
        echo "<div class='resumen-box'>";
        echo "<h3>📊 RESUMEN DEL PERÍODO {$anioInicio}-{$anioFin}</h3>";
        echo "👥 Crecimiento total: <strong>" . number_format($incrementoTotal) . " millones</strong><br>";
        echo "📈 Porcentaje de crecimiento: <strong>" . number_format($porcentajeCrecimiento, 2) . "%</strong><br>";
        echo "📊 Tasa promedio anual: <strong>" . number_format($promedioCrecimiento, 2) . "%</strong><br>";
        echo "🌍 Población se multiplicó por: <strong>" . number_format($this->datosPoblacion[$anioFin] / $this->datosPoblacion[$anioInicio], 1) . "x</strong>";
        echo "</div>";
        
        return [
            'inicio' => $this->datosPoblacion[$anioInicio],
            'fin' => $this->datosPoblacion[$anioFin],
            'crecimiento_total' => $incrementoTotal,
            'porcentaje_crecimiento' => $porcentajeCrecimiento
        ];
    }
    
    // Ciclo FOR para análisis por décadas
    public function analisisPorDecadas() {
        echo "<div class='operacion-box'>";
        echo "<h3>🕰️ ANÁLISIS POR DÉCADAS</h3>";
        echo "</div>";
        
        // CICLO FOR agrupando por décadas
        for ($decada = 1910; $decada <= 2020; $decada += 10) {
            $anioInicioDecada = $decada;
            $anioFinDecada = $decada + 9;
            $poblacionInicio = isset($this->datosPoblacion[$anioInicioDecada]) ? $this->datosPoblacion[$anioInicioDecada] : null;
            $poblacionFin = isset($this->datosPoblacion[$anioFinDecada]) ? $this->datosPoblacion[$anioFinDecada] : null;
            
            if ($poblacionInicio && $poblacionFin) {
                $crecimientoDecada = $poblacionFin - $poblacionInicio;
                $tasaCrecimientoDecada = ($crecimientoDecada / $poblacionInicio) * 100;
                
                echo "<div class='decada-box'>";
                echo "<h4>📊 DÉCADA {$decada}s</h4>";
                echo "<div class='info-decada'>";
                echo "👥 Inicio: <strong>" . number_format($poblacionInicio) . "M</strong><br>";
                echo "👥 Fin: <strong>" . number_format($poblacionFin) . "M</strong><br>";
                echo "📈 Crecimiento: <strong>" . number_format($crecimientoDecada) . "M</strong><br>";
                echo "📊 Tasa: <strong>" . number_format($tasaCrecimientoDecada, 1) . "%</strong>";
                echo "</div>";
                
                // Gráfico comparativo
                $porcentajeCrecimientoVisual = min(100, abs($tasaCrecimientoDecada) * 2);
                $claseBarra = $tasaCrecimientoDecada >= 0 ? "barra-crecimiento" : "barra-decrecimiento";
                echo "<div class='grafico-decada'>";
                echo "<div class='{$claseBarra}' style='width: {$porcentajeCrecimientoVisual}%'>";
                echo "<span class='etiqueta-tasa'>" . number_format($tasaCrecimientoDecada, 1) . "%</span>";
                echo "</div>";
                echo "</div>";
                
                echo "</div>";
            }
        }
    }
    
    // Proyección futura usando ciclo FOR
    public function proyeccionFutura($aniosProyeccion = 20) {
        echo "<div class='operacion-box'>";
        echo "<h3>🔮 PROYECCIÓN POBLACIONAL FUTURA</h3>";
        echo "<div class='estado-inicial'>";
        echo "📅 Años proyectados: <strong>{$aniosProyeccion}</strong><br>";
        echo "👥 Población base (2024): <strong>" . number_format($this->datosPoblacion[2024]) . " millones</strong><br>";
        echo "📈 Tasa de crecimiento estimada: <strong>0.8% anual</strong>";
        echo "</div>";
        echo "</div>";
        
        $poblacionActual = $this->datosPoblacion[2024];
        $tasaCrecimiento = 0.008; // 0.8% anual
        
        // CICLO FOR para proyección futura
        for ($i = 1; $i <= $aniosProyeccion; $i++) {
            $anioProyectado = 2024 + $i;
            $poblacionProyectada = $poblacionActual * pow(1 + $tasaCrecimiento, $i);
            
            echo "<div class='proyeccion-box'>";
            echo "<h4>📅 AÑO {$anioProyectado}</h4>";
            echo "<div class='info-proyeccion'>";
            echo "👥 Población proyectada: <strong>" . number_format($poblacionProyectada, 0) . " millones</strong><br>";
            echo "📈 Incremento desde 2024: <strong>+" . number_format($poblacionProyectada - $this->datosPoblacion[2024], 0) . " millones</strong><br>";
            echo "📊 Crecimiento acumulado: <strong>" . number_format((($poblacionProyectada / $this->datosPoblacion[2024]) - 1) * 100, 1) . "%</strong>";
            echo "</div>";
            
            // Indicador visual de crecimiento
            $porcentajeProyeccion = (($poblacionProyectada - $this->datosPoblacion[2024]) / $this->datosPoblacion[2024]) * 100;
            $anchoBarra = min(100, abs($porcentajeProyeccion) * 2);
            echo "<div class='grafico-proyeccion'>";
            echo "<div class='barra-proyeccion' style='width: {$anchoBarra}%'>";
            echo "<span class='etiqueta-proyeccion'>+" . number_format($porcentajeProyeccion, 1) . "%</span>";
            echo "</div>";
            echo "</div>";
            
            echo "</div>";
        }
        
        $poblacionFinal = $poblacionActual * pow(1 + $tasaCrecimiento, $aniosProyeccion);
        
        echo "<div class='resumen-box'>";
        echo "<h3>🎯 RESUMEN DE PROYECCIÓN</h3>";
        echo "👥 Población en " . (2024 + $aniosProyeccion) . ": <strong>" . number_format($poblacionFinal, 0) . " millones</strong><br>";
        echo "📈 Crecimiento total: <strong>" . number_format($poblacionFinal - $this->datosPoblacion[2024], 0) . " millones</strong><br>";
        echo "📊 Tasa anual promedio: <strong>0.8%</strong><br>";
        echo "🌍 Incremento diario estimado: <strong>" . number_format(($poblacionFinal - $this->datosPoblacion[2024]) / ($aniosProyeccion * 365), 0) . " personas/día</strong>";
        echo "</div>";
        
        return [
            'poblacion_final' => $poblacionFinal,
            'crecimiento_total' => $poblacionFinal - $this->datosPoblacion[2024]
        ];
    }
}

// Función para demostrar ejemplos
function demostrarEjemplosFor() {
    echo "<div class='demo-container'>";
    echo "<h2>🎯 EJEMPLOS DEMOSTRATIVOS</h2>";
    
    $simulador = new CrecimientoPoblacional();
    
    // Ejemplo 1: Período de guerras mundiales
    echo "<div class='ejemplo-box'>";
    echo "<h3>1. Período de Guerras Mundiales (1914-1945)</h3>";
    $simulador->mostrarCrecimientoHistorico(1914, 1945);
    echo "</div>";
    
    // Ejemplo 2: Boom poblacional
    echo "<div class='ejemplo-box'>";
    echo "<h3>2. Boom Poblacional (1950-2000)</h3>";
    $simulador->mostrarCrecimientoHistorico(1950, 2000);
    echo "</div>";
    
    echo "</div>";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crecimiento Poblacional - Ciclo FOR</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: #0a0a0a;
            color: #e0e0e0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            padding: 20px;
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
            min-height: 100vh;
        }

        .container {
            max-width: 1400px;
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
            background: linear-gradient(135deg, #ff6b6b, #ffa726);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 10px;
        }

        .info-box {
            background: rgba(255, 255, 255, 0.05);
            padding: 25px;
            border-radius: 12px;
            border-left: 4px solid #ff6b6b;
            margin: 25px 0;
            backdrop-filter: blur(10px);
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

        select, input {
            width: 100%;
            padding: 12px 15px;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 8px;
            color: #e0e0e0;
            font-size: 16px;
            transition: all 0.3s ease;
        }

        button {
            padding: 15px;
            background: linear-gradient(135deg, #ff6b6b, #ffa726);
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
            box-shadow: 0 5px 15px rgba(255, 107, 107, 0.4);
        }

        .btn-secundario {
            background: linear-gradient(135deg, #6c757d, #495057) !important;
            margin-top: 10px;
        }

        /* Estilos para las operaciones */
        .operacion-box {
            background: rgba(255, 107, 107, 0.1);
            padding: 20px;
            border-radius: 12px;
            border: 1px solid rgba(255, 107, 107, 0.3);
            margin: 20px 0;
        }

        .anio-box, .decada-box, .proyeccion-box {
            background: rgba(255, 255, 255, 0.05);
            padding: 20px;
            border-radius: 8px;
            margin: 15px 0;
            border-left: 4px solid #4fc3f7;
            transition: all 0.3s ease;
        }

        .anio-box:hover, .decada-box:hover, .proyeccion-box:hover {
            background: rgba(255, 255, 255, 0.08);
            transform: translateX(5px);
        }

        .resumen-box {
            background: rgba(76, 175, 80, 0.1);
            padding: 20px;
            border-radius: 12px;
            border: 1px solid rgba(76, 175, 80, 0.3);
            margin: 20px 0;
        }

        .estado-inicial {
            background: rgba(255, 255, 255, 0.05);
            padding: 15px;
            border-radius: 8px;
            margin: 10px 0;
        }

        .info-anio, .info-decada, .info-proyeccion {
            margin-bottom: 15px;
        }

        .incremento-positivo {
            color: #4caf50;
            font-weight: bold;
        }

        .incremento-negativo {
            color: #ff6b6b;
            font-weight: bold;
        }

        /* Gráficos */
        .grafico-barra, .grafico-decada, .grafico-proyeccion {
            background: rgba(255, 255, 255, 0.1);
            height: 30px;
            border-radius: 15px;
            margin: 10px 0;
            overflow: hidden;
            position: relative;
        }

        .barra-poblacion {
            background: linear-gradient(90deg, #4fc3f7, #29b6f6);
            height: 100%;
            border-radius: 15px;
            transition: width 0.5s ease;
            position: relative;
        }

        .barra-crecimiento {
            background: linear-gradient(90deg, #4caf50, #45a049);
            height: 100%;
            border-radius: 15px;
        }

        .barra-decrecimiento {
            background: linear-gradient(90deg, #ff6b6b, #ff5252);
            height: 100%;
            border-radius: 15px;
        }

        .barra-proyeccion {
            background: linear-gradient(90deg, #ffa726, #ff9800);
            height: 100%;
            border-radius: 15px;
        }

        .etiqueta-poblacion, .etiqueta-tasa, .etiqueta-proyeccion {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: white;
            font-weight: bold;
            font-size: 0.8em;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.5);
        }

        .instrucciones-box {
            background: rgba(255, 193, 7, 0.1);
            padding: 20px;
            border-radius: 12px;
            border-left: 4px solid #FFC107;
            margin: 20px 0;
        }

        /* Gráfico principal mejorado - ÁREA AMPLIADA */
        .grafico-container {
            background: rgba(255, 255, 255, 0.05);
            padding: 25px;
            border-radius: 12px;
            margin: 20px 0;
            text-align: center;
        }

        #graficoPoblacion {
            width: 100%;
            height: 600px;
            background: rgba(255, 255, 255, 0.02);
            border-radius: 8px;
            margin: 0 auto;
        }

        .tooltip-poblacion {
            position: absolute;
            background: rgba(0, 0, 0, 0.9);
            color: white;
            padding: 12px 16px;
            border-radius: 6px;
            font-size: 13px;
            pointer-events: none;
            z-index: 1000;
            border: 1px solid #4fc3f7;
            box-shadow: 0 4px 12px rgba(0,0,0,0.5);
            max-width: 280px;
            line-height: 1.4;
        }

        .demo-container {
            margin-top: 40px;
        }

        .ejemplo-box {
            background: rgba(255, 255, 255, 0.05);
            padding: 20px;
            border-radius: 12px;
            margin: 20px 0;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .controles-grafico {
            display: flex;
            justify-content: center;
            gap: 10px;
            flex-wrap: wrap;
            margin-top: 15px;
        }

        .controles-grafico button {
            width: auto;
            padding: 10px 20px;
            margin: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🌍 Crecimiento Poblacional Mundial</h1>
            <p>Simulación del ciclo FOR con datos históricos y proyecciones</p>
        </div>
        
        <div class="info-box">
            <h3>💡 ¿Qué es el ciclo FOR?</h3>
            <p>El ciclo <strong>for</strong> ejecuta un bloque de código un número <strong>específico y conocido</strong> de veces:</p>
            <div style="background: #1a1a1a; padding: 15px; border-radius: 8px; font-family: monospace; margin: 15px 0;">
                for (inicialización; condición; incremento) {<br>
                &nbsp;&nbsp;// Código a ejecutar en cada iteración<br>
                }
            </div>
            <p><strong>Perfecto para:</strong> Recorrer arrays, procesar rangos numéricos, ejecutar operaciones un número determinado de veces.</p>
        </div>

        <!-- Gráfico principal mejorado - ÁREA AMPLIADA -->
        <div class="grafico-container">
            <h3>📈 Evolución Poblacional 1914-2024</h3>
            <div style="position: relative;">
                <canvas id="graficoPoblacion"></canvas>
                <div id="tooltipPoblacion" class="tooltip-poblacion" style="display: none;"></div>
            </div>
            <div class="controles-grafico">
                <button onclick="iniciarAnimacionGrafico()" class="btn-secundario">
                    ▶️ Animar Evolución
                </button>
                <button onclick="mostrarTodosPuntos()" class="btn-secundario">
                    🔍 Mostrar Todos los Puntos
                </button>
                <button onclick="mostrarPuntosEstrategicos()" class="btn-secundario">
                    🎯 Puntos Estratégicos
                </button>
                <button onclick="ocultarPuntos()" class="btn-secundario">
                    ❌ Ocultar Puntos
                </button>
            </div>
        </div>
        
        <div class="form-container">
            <h2 style="color: #ff6b6b; margin-bottom: 20px;">🎮 Simulador de Crecimiento</h2>
            <form method="POST">
                <div class="form-group">
                    <label for="tipo_analisis">Tipo de análisis:</label>
                    <select name="tipo_analisis" id="tipo_analisis" required>
                        <option value="">-- Selecciona análisis --</option>
                        <option value="historico">📅 Análisis Histórico (1914-2024)</option>
                        <option value="decadas">🕰️ Análisis por Décadas</option>
                        <option value="proyeccion">🔮 Proyección Futura</option>
                    </select>
                </div>
                
                <div class="form-group" id="rango_anios_group" style="display: none;">
                    <label for="rango_anios">Rango de años (1914-2024):</label>
                    <select name="rango_anios" id="rango_anios">
                        <option value="1914-2024">1914-2024 (Completo)</option>
                        <option value="1914-1945">1914-1945 (Guerras Mundiales)</option>
                        <option value="1945-1990">1945-1990 (Guerra Fría)</option>
                        <option value="1990-2024">1990-2024 (Era Digital)</option>
                    </select>
                </div>
                
                <div class="form-group" id="proyeccion_group" style="display: none;">
                    <label for="anios_proyeccion">Años a proyectar:</label>
                    <select name="anios_proyeccion" id="anios_proyeccion">
                        <option value="10">10 años (hasta 2034)</option>
                        <option value="20">20 años (hasta 2044)</option>
                        <option value="30">30 años (hasta 2054)</option>
                        <option value="50">50 años (hasta 2074)</option>
                    </select>
                </div>
                
                <button type="submit" style="width: 100%;">🌍 Ejecutar Análisis</button>
                <button type="button" onclick="window.location.href=window.location.href" class="btn-secundario" style="width: 100%;">
                    🔄 Reiniciar Simulador
                </button>
            </form>
        </div>
        
        <?php
        // Procesar formulario
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $tipoAnalisis = $_POST['tipo_analisis'];
            $rangoAnios = $_POST['rango_anios'] ?? '1914-2024';
            $aniosProyeccion = $_POST['anios_proyeccion'] ?? 20;
            
            $simulador = new CrecimientoPoblacional();
            
            echo "<div class='operacion-box'>";
            echo "<h2>🌀 SIMULACIÓN CICLO FOR EN EJECUCIÓN</h2>";
            
            switch ($tipoAnalisis) {
                case 'historico':
                    list($anioInicio, $anioFin) = explode('-', $rangoAnios);
                    $simulador->mostrarCrecimientoHistorico($anioInicio, $anioFin);
                    break;
                case 'decadas':
                    $simulador->analisisPorDecadas();
                    break;
                case 'proyeccion':
                    $simulador->proyeccionFutura($aniosProyeccion);
                    break;
            }
            
            echo "</div>";
            
        } else {
            // Mostrar instrucciones
            echo "<div class='instrucciones-box'>";
            echo "<h3>📋 Instrucciones de Uso</h3>";
            echo "<p>Este simulador utiliza el ciclo <strong>FOR</strong> para analizar el crecimiento poblacional mundial:</p>";
            echo "<ul>";
            echo "<li><strong>📅 Análisis Histórico:</strong> Recorre año por año mostrando datos detallados</li>";
            echo "<li><strong>🕰️ Análisis por Décadas:</strong> Agrupa datos por períodos de 10 años</li>";
            echo "<li><strong>🔮 Proyección Futura:</strong> Calcula crecimiento futuro usando modelos</li>";
            echo "</ul>";
            echo "<p>Cada análisis muestra cómo el ciclo FOR procesa rangos numéricos de manera eficiente.</p>";
            echo "</div>";
            
            // Mostrar ejemplos demostrativos
            demostrarEjemplosFor();
        }
        ?>
    </div>

    <script>
        // Datos de población (se pasan desde PHP)
        const datosGrafico = <?php echo (new CrecimientoPoblacional())->getDatosParaGrafico(); ?>;
        const anios = datosGrafico.anios;
        const poblaciones = datosGrafico.poblaciones;
        const eventos = datosGrafico.eventos;

        let puntosVisibles = false;
        let animacionActiva = false;
        let modoEstrategico = false;

        function inicializarGrafico() {
            const canvas = document.getElementById('graficoPoblacion');
            const ctx = canvas.getContext('2d');
            
            // Configurar tamaño - ÁREA AMPLIADA
            canvas.width = canvas.offsetWidth;
            canvas.height = 600; // Altura aumentada
            
            // Dibujar gráfico estático
            dibujarGrafico(ctx, 1.0, false);
        }

        function dibujarGrafico(ctx, progreso, mostrarPuntos = false) {
            const width = ctx.canvas.width;
            const height = ctx.canvas.height;
            const padding = 100; // Padding aumentado para más espacio
            
            // Limpiar canvas
            ctx.clearRect(0, 0, width, height);
            
            // Fondo de cuadrícula
            dibujarCuadricula(ctx, width, height, padding);
            
            // Ejes
            ctx.strokeStyle = '#666';
            ctx.lineWidth = 2;
            ctx.beginPath();
            ctx.moveTo(padding, padding);
            ctx.lineTo(padding, height - padding);
            ctx.lineTo(width - padding, height - padding);
            ctx.stroke();
            
            // Calcular escalas
            const maxPoblacion = Math.max(...poblaciones);
            const minPoblacion = Math.min(...poblaciones);
            const escalaX = (width - 2 * padding) / (anios.length - 1);
            const escalaY = (height - 2 * padding) / (maxPoblacion - minPoblacion);
            
            // Dibujar línea de progreso
            ctx.strokeStyle = '#ff6b6b';
            ctx.lineWidth = 4;
            ctx.lineJoin = 'round';
            ctx.beginPath();
            
            const puntosTotales = anios.length;
            const puntosDibujar = Math.floor(puntosTotales * progreso);
            
            for (let i = 0; i < puntosDibujar; i++) {
                const x = padding + i * escalaX;
                const y = height - padding - ((poblaciones[i] - minPoblacion) * escalaY);
                
                if (i === 0) {
                    ctx.moveTo(x, y);
                } else {
                    ctx.lineTo(x, y);
                }
            }
            ctx.stroke();
            
            // Dibujar puntos según el modo seleccionado
            if (mostrarPuntos || puntosVisibles) {
                if (modoEstrategico) {
                    // Modo estratégico: solo puntos importantes
                    dibujarPuntosEstrategicos(ctx, padding, escalaX, escalaY, height, minPoblacion, puntosDibujar);
                } else {
                    // Modo completo: todos los puntos
                    dibujarTodosLosPuntos(ctx, padding, escalaX, escalaY, height, minPoblacion, puntosDibujar);
                }
            } else {
                // Solo mostrar punto final durante animación
                if (puntosDibujar > 0) {
                    const i = puntosDibujar - 1;
                    const x = padding + i * escalaX;
                    const y = height - padding - ((poblaciones[i] - minPoblacion) * escalaY);
                    
                    ctx.fillStyle = '#ff6b6b';
                    ctx.beginPath();
                    ctx.arc(x, y, 8, 0, 2 * Math.PI);
                    ctx.fill();
                    
                    ctx.strokeStyle = '#fff';
                    ctx.lineWidth = 2;
                    ctx.stroke();
                    
                    // Etiqueta del punto actual
                    ctx.fillStyle = '#fff';
                    ctx.font = 'bold 14px Arial';
                    ctx.textAlign = 'center';
                    ctx.fillText(poblaciones[i] + 'M', x, y - 25);
                    ctx.fillText(anios[i], x, height - padding + 35);
                }
            }
            
            // Leyendas y títulos
            dibujarLeyendas(ctx, width, height, padding, maxPoblacion, minPoblacion);
        }

        function dibujarTodosLosPuntos(ctx, padding, escalaX, escalaY, height, minPoblacion, puntosDibujar) {
            // Mostrar puntos cada 2 años para evitar saturación
            for (let i = 0; i < puntosDibujar; i += 2) {
                const x = padding + i * escalaX;
                const y = height - padding - ((poblaciones[i] - minPoblacion) * escalaY);
                const anio = anios[i];
                const poblacion = poblaciones[i];
                
                // Punto en el gráfico
                ctx.fillStyle = eventos[anio] ? '#ffa726' : '#4fc3f7';
                ctx.beginPath();
                ctx.arc(x, y, 5, 0, 2 * Math.PI);
                ctx.fill();
                
                ctx.strokeStyle = '#fff';
                ctx.lineWidth = 1.5;
                ctx.stroke();
                
                // Etiqueta de población cada 5 puntos
                if (i % 10 === 0) {
                    ctx.fillStyle = '#fff';
                    ctx.font = 'bold 11px Arial';
                    ctx.textAlign = 'center';
                    ctx.fillText(poblacion + 'M', x, y - 20);
                }
                
                // Año cada 10 puntos
                if (i % 10 === 0) {
                    ctx.fillStyle = '#ccc';
                    ctx.font = '11px Arial';
                    ctx.fillText(anio, x, height - padding + 25);
                }
            }
        }

        function dibujarPuntosEstrategicos(ctx, padding, escalaX, escalaY, height, minPoblacion, puntosDibujar) {
            // Puntos estratégicos: años con eventos y cada década
            const puntosEstrategicos = [];
            
            for (let i = 0; i < puntosDibujar; i++) {
                const anio = anios[i];
                // Incluir años con eventos históricos y cada 10 años
                if (eventos[anio] || anio % 10 === 0) {
                    puntosEstrategicos.push(i);
                }
            }
            
            // Asegurar que tengamos algunos puntos adicionales para mejor visualización
            for (let i = 0; i < puntosDibujar; i += 20) {
                if (!puntosEstrategicos.includes(i)) {
                    puntosEstrategicos.push(i);
                }
            }
            
            // Ordenar y dibujar puntos estratégicos
            puntosEstrategicos.sort((a, b) => a - b);
            
            puntosEstrategicos.forEach(i => {
                const x = padding + i * escalaX;
                const y = height - padding - ((poblaciones[i] - minPoblacion) * escalaY);
                const anio = anios[i];
                const poblacion = poblaciones[i];
                
                // Punto en el gráfico
                ctx.fillStyle = eventos[anio] ? '#ffa726' : '#4fc3f7';
                ctx.beginPath();
                ctx.arc(x, y, 6, 0, 2 * Math.PI);
                ctx.fill();
                
                ctx.strokeStyle = '#fff';
                ctx.lineWidth = 2;
                ctx.stroke();
                
                // Etiqueta de población
                ctx.fillStyle = '#fff';
                ctx.font = 'bold 12px Arial';
                ctx.textAlign = 'center';
                ctx.fillText(poblacion + 'M', x, y - 20);
                
                // Año
                ctx.fillStyle = '#ccc';
                ctx.font = '11px Arial';
                ctx.fillText(anio, x, height - padding + 25);
                
                // Línea conectora para años importantes
                if (eventos[anio]) {
                    ctx.strokeStyle = 'rgba(255, 255, 255, 0.3)';
                    ctx.lineWidth = 1;
                    ctx.beginPath();
                    ctx.moveTo(x, y - 15);
                    ctx.lineTo(x, y - 30);
                    ctx.stroke();
                }
            });
        }

        function dibujarCuadricula(ctx, width, height, padding) {
            ctx.strokeStyle = 'rgba(255, 255, 255, 0.05)';
            ctx.lineWidth = 1;
            
            // Cuadrícula vertical
            for (let i = 0; i <= 10; i++) {
                const x = padding + (i * (width - 2 * padding) / 10);
                ctx.beginPath();
                ctx.moveTo(x, padding);
                ctx.lineTo(x, height - padding);
                ctx.stroke();
            }
            
            // Cuadrícula horizontal
            for (let i = 0; i <= 5; i++) {
                const y = padding + (i * (height - 2 * padding) / 5);
                ctx.beginPath();
                ctx.moveTo(padding, y);
                ctx.lineTo(width - padding, y);
                ctx.stroke();
            }
        }

        function dibujarLeyendas(ctx, width, height, padding, maxPoblacion, minPoblacion) {
            // Título principal
            ctx.fillStyle = '#fff';
            ctx.font = 'bold 18px Arial';
            ctx.textAlign = 'center';
            ctx.fillText('Población Mundial (millones)', width / 2, padding - 30);
            
            // Etiqueta eje Y
            ctx.textAlign = 'right';
            ctx.font = '14px Arial';
            ctx.fillText('Millones', padding - 15, padding + 60);
            
            // Escala poblacional
            ctx.textAlign = 'left';
            ctx.font = '12px Arial';
            for (let i = 0; i <= 5; i++) {
                const valor = minPoblacion + (i * (maxPoblacion - minPoblacion) / 5);
                const y = height - padding - (i * (height - 2 * padding) / 5);
                ctx.fillText(Math.round(valor) + 'M', padding - 60, y + 4);
                
                // Línea horizontal
                ctx.strokeStyle = 'rgba(255, 255, 255, 0.1)';
                ctx.lineWidth = 1;
                ctx.beginPath();
                ctx.moveTo(padding, y);
                ctx.lineTo(width - padding, y);
                ctx.stroke();
            }
            
            // Leyenda de colores
            const leyendaX = width - 200;
            const leyendaY = padding + 30;
            
            ctx.fillStyle = '#4fc3f7';
            ctx.beginPath();
            ctx.arc(leyendaX, leyendaY, 4, 0, 2 * Math.PI);
            ctx.fill();
            ctx.fillStyle = '#ccc';
            ctx.font = '11px Arial';
            ctx.textAlign = 'left';
            ctx.fillText('Año normal', leyendaX + 10, leyendaY + 4);
            
            ctx.fillStyle = '#ffa726';
            ctx.beginPath();
            ctx.arc(leyendaX, leyendaY + 20, 4, 0, 2 * Math.PI);
            ctx.fill();
            ctx.fillStyle = '#ccc';
            ctx.fillText('Evento histórico', leyendaX + 10, leyendaY + 24);
        }

        function iniciarAnimacionGrafico() {
            if (animacionActiva) return;
            
            animacionActiva = true;
            const canvas = document.getElementById('graficoPoblacion');
            const ctx = canvas.getContext('2d');
            
            let progreso = 0;
            const duracion = 5000; // 5 segundos para mejor visualización
            
            function animar(timestamp) {
                if (!inicio) inicio = timestamp;
                const transcurrido = timestamp - inicio;
                
                progreso = Math.min(transcurrido / duracion, 1);
                
                dibujarGrafico(ctx, progreso, puntosVisibles);
                
                if (progreso < 1) {
                    requestAnimationFrame(animar);
                } else {
                    animacionActiva = false;
                }
            }
            
            let inicio;
            requestAnimationFrame(animar);
        }

        function mostrarTodosPuntos() {
            puntosVisibles = true;
            modoEstrategico = false;
            const canvas = document.getElementById('graficoPoblacion');
            const ctx = canvas.getContext('2d');
            dibujarGrafico(ctx, 1.0, true);
        }

        function mostrarPuntosEstrategicos() {
            puntosVisibles = true;
            modoEstrategico = true;
            const canvas = document.getElementById('graficoPoblacion');
            const ctx = canvas.getContext('2d');
            dibujarGrafico(ctx, 1.0, true);
        }

        function ocultarPuntos() {
            puntosVisibles = false;
            modoEstrategico = false;
            const canvas = document.getElementById('graficoPoblacion');
            const ctx = canvas.getContext('2d');
            dibujarGrafico(ctx, 1.0, false);
        }

        // Event listeners para tooltips
        document.getElementById('graficoPoblacion').addEventListener('mousemove', function(event) {
            const canvas = this;
            const rect = canvas.getBoundingClientRect();
            const x = event.clientX - rect.left;
            const y = event.clientY - rect.top;
            
            const tooltip = document.getElementById('tooltipPoblacion');
            const padding = 100;
            const width = canvas.width;
            const height = canvas.height;
            
            const escalaX = (width - 2 * padding) / (anios.length - 1);
            const maxPoblacion = Math.max(...poblaciones);
            const minPoblacion = Math.min(...poblaciones);
            const escalaY = (height - 2 * padding) / (maxPoblacion - minPoblacion);
            
            // Encontrar el punto más cercano
            let puntoCercano = null;
            let distanciaMinima = Infinity;
            
            for (let i = 0; i < anios.length; i++) {
                const puntoX = padding + i * escalaX;
                const puntoY = height - padding - ((poblaciones[i] - minPoblacion) * escalaY);
                const distancia = Math.sqrt(Math.pow(x - puntoX, 2) + Math.pow(y - puntoY, 2));
                
                if (distancia < 25 && distancia < distanciaMinima) {
                    distanciaMinima = distancia;
                    puntoCercano = i;
                }
            }
            
            if (puntoCercano !== null) {
                const anio = anios[puntoCercano];
                const poblacion = poblaciones[puntoCercano];
                const evento = eventos[anio];
                
                let texto = `<strong>${anio}</strong><br>Población: ${poblacion} millones`;
                if (evento) {
                    texto += `<br>📌 ${evento}`;
                }
                
                tooltip.innerHTML = texto;
                tooltip.style.display = 'block';
                tooltip.style.left = (event.clientX + 15) + 'px';
                tooltip.style.top = (event.clientY + 15) + 'px';
            } else {
                tooltip.style.display = 'none';
            }
        });

        document.getElementById('graficoPoblacion').addEventListener('mouseleave', function() {
            document.getElementById('tooltipPoblacion').style.display = 'none';
        });

        // Inicializar gráfico al cargar
        document.addEventListener('DOMContentLoaded', function() {
            inicializarGrafico();
            
            // Configurar formulario si hay valores POST
            <?php if ($_SERVER['REQUEST_METHOD'] == 'POST'): ?>
            document.getElementById('tipo_analisis').value = '<?php echo $_POST['tipo_analisis'] ?? ''; ?>';
            document.getElementById('rango_anios').value = '<?php echo $_POST['rango_anios'] ?? ''; ?>';
            document.getElementById('anios_proyeccion').value = '<?php echo $_POST['anios_proyeccion'] ?? ''; ?>';
            
            const tipoAnalisis = document.getElementById('tipo_analisis').value;
            if (tipoAnalisis === 'historico') {
                document.getElementById('rango_anios_group').style.display = 'block';
            } else if (tipoAnalisis === 'proyeccion') {
                document.getElementById('proyeccion_group').style.display = 'block';
            }
            <?php endif; ?>
        });

        // Redibujar gráfico al redimensionar
        window.addEventListener('resize', inicializarGrafico);
    </script>
</body>
</html>