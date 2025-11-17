<?php
function generarGraficoFutbol($tipo) {
    $script_python = 'python/procesar_futbol.py';
    $archivo_datos = 'datos/premier_league.csv';
    $archivo_salida = 'temp/grafico_' . $tipo . '.png';
    
    // Crear directorio temp si no existe
    if (!is_dir('temp')) {
        mkdir('temp', 0755, true);
    }
    
    $comando = "python " . escapeshellarg($script_python) . " " . 
               escapeshellarg($tipo) . " " . 
               escapeshellarg($archivo_datos) . " " . 
               escapeshellarg($archivo_salida);
    
    $resultado = shell_exec($comando . " 2>&1");
    
    if (file_exists($archivo_salida)) {
        $titulos = [
            'tabla_barras' => 'Puntos por Equipo - Premier League',
            'goles' => 'Goles a Favor vs Goles en Contra',
            'efectividad' => 'Porcentaje de Efectividad'
        ];
        
        $titulo = $titulos[$tipo] ?? 'Estadísticas de Fútbol';
        
        echo "<div class='chart-container'>";
        echo "<h3>" . $titulo . "</h3>";
        echo "<img src='" . $archivo_salida . "' alt='" . $titulo . "' class='chart-image'>";
        echo "</div>";
        
    } else {
        echo "<div style='background: #ffebee; color: #c62828; padding: 1rem; border-radius: 5px; text-align: center;'>";
        echo "<h3>❌ Error al generar el gráfico</h3>";
        echo "<pre>Error: " . htmlspecialchars($resultado) . "</pre>";
        echo "</div>";
    }
}

if (isset($_GET['vista']) && $_GET['vista'] !== 'tabla') {
    generarGraficoFutbol($_GET['vista']);
}
?>