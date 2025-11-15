<?php
function generarGrafico($tipo) {
    // Configuración
    $script_python = 'python/graficos.py';
    $archivo_datos = 'datos/poblacion_mundial.csv';
    $archivo_salida = 'temp/grafico_' . $tipo . '.png';
    
    // Crear directorio temp si no existe
    if (!is_dir('temp')) {
        mkdir('temp', 0755, true);
    }
    
    // Comando para ejecutar Python
    $comando = "python " . escapeshellarg($script_python) . " " . 
               escapeshellarg($tipo) . " " . 
               escapeshellarg($archivo_datos) . " " . 
               escapeshellarg($archivo_salida);
    
    // Ejecutar y capturar resultado
    $resultado = shell_exec($comando . " 2>&1");
    
    // Verificar si se generó el gráfico
    if (file_exists($archivo_salida)) {
        // Mostrar el gráfico
        $titulos = [
            'barras' => 'Gráfico de Barras - Población por País',
            'torta' => 'Gráfico de Torta - Distribución de Población',
            'continentes' => 'Gráfico de Barras - Población por Continente'
        ];
        
        $titulo = $titulos[$tipo] ?? 'Gráfico de Población';
        
        echo "<h3 class='chart-title'>" . $titulo . "</h3>";
        echo "<img src='" . $archivo_salida . "' alt='" . $titulo . "' class='chart-image'>";
        echo "<p><small>Gráfico generado el: " . date('d/m/Y H:i:s') . "</small></p>";
        
    } else {
        echo "<div style='background: #ffebee; color: #c62828; padding: 1rem; border-radius: 5px;'>";
        echo "<h3>❌ Error al generar el gráfico</h3>";
        echo "<p>No se pudo generar el gráfico. Verifica que:</p>";
        echo "<ul>";
        echo "<li>Python esté instalado</li>";
        echo "<li>Las librerías (seaborn, matplotlib, pandas) estén instaladas</li>";
        echo "<li>El archivo de datos exista</li>";
        echo "</ul>";
        echo "<pre>Error: " . htmlspecialchars($resultado) . "</pre>";
        echo "</div>";
    }
}

// Generar el gráfico seleccionado
if (isset($_GET['grafico'])) {
    $tipo = $_GET['grafico'];
    generarGrafico($tipo);
}
?>
