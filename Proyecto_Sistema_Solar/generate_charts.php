<?php
header('Content-Type: application/json');

class ChartGenerator {
    private $dataFile = 'data/celestial_bodies.json';
    private $pythonScript = 'python/charts.py';
    private $chartsDir = 'generated_charts';
    
    public function __construct() {
        // Crear directorio si no existe
        if (!is_dir($this->chartsDir)) {
            mkdir($this->chartsDir, 0755, true);
        }
        
        // Generar gráficos automáticamente si no existen
        $this->generateAllChartsIfNeeded();
    }
    
    private function generateAllChartsIfNeeded() {
        $expectedCharts = [
            'mass_composition.png', 'temperature_area.png', 'size_comparison.png',
            'orbital_periods.png', 'planet_types.png', 'moon_distribution.png', 
            'density_comparison.png'
        ];
        
        $missingCharts = [];
        foreach ($expectedCharts as $chart) {
            if (!file_exists($this->chartsDir . '/' . $chart)) {
                $missingCharts[] = $chart;
            }
        }
        
        if (!empty($missingCharts)) {
            error_log("Faltan gráficos: " . implode(', ', $missingCharts));
            
            // Ejecutar script Python para generar todos los gráficos
            $pythonDir = dirname($this->pythonScript);
            $dataFileRelative = "../" . $this->dataFile;
            
            $command = "cd " . escapeshellarg($pythonDir) . " && python " . 
                      escapeshellarg(basename($this->pythonScript)) . " all " . 
                      escapeshellarg($dataFileRelative) . " 2>&1";
            
            exec($command, $output, $returnCode);
            
            if ($returnCode === 0) {
                error_log("Gráficos generados automáticamente: " . implode(', ', array_slice($output, -3)));
            } else {
                error_log("Error generando gráficos. Código: $returnCode - " . implode(' ', $output));
                
                // Intentar método alternativo
                $this->generateChartsAlternative();
            }
        } else {
            error_log("Todos los gráficos ya existen");
        }
    }
    
    private function generateChartsAlternative() {
        // Método alternativo: crear gráficos placeholder si Python falla
        error_log("Usando método alternativo para generar gráficos...");
        
        $charts = [
            'mass_composition.png', 'temperature_area.png', 'size_comparison.png',
            'orbital_periods.png', 'planet_types.png', 'moon_distribution.png', 
            'density_comparison.png'
        ];
        
        foreach ($charts as $chart) {
            $filePath = $this->chartsDir . '/' . $chart;
            if (!file_exists($filePath)) {
                // Crear un SVG placeholder simple
                $svgContent = '<?xml version="1.0" encoding="UTF-8"?>
                <svg xmlns="http://www.w3.org/2000/svg" width="800" height="600" viewBox="0 0 800 600">
                    <rect width="800" height="600" fill="#1e293b"/>
                    <rect x="50" y="50" width="700" height="500" fill="#0f172a" stroke="#475569" stroke-width="2"/>
                    <text x="400" y="300" text-anchor="middle" fill="#64748b" font-family="Arial" font-size="24" font-weight="bold">
                        Gráfico: ' . pathinfo($chart, PATHINFO_FILENAME) . '
                    </text>
                    <text x="400" y="340" text-anchor="middle" fill="#94a3b8" font-family="Arial" font-size="16">
                        Se generará automáticamente
                    </text>
                </svg>';
                
                file_put_contents($filePath, $svgContent);
                error_log("Creado placeholder para: " . $chart);
            }
        }
    }
    
    public function getGeneratedCharts() {
        $charts = [];
        $files = glob($this->chartsDir . "/*.png");
        
        // Si no hay archivos PNG, buscar SVG
        if (empty($files)) {
            $files = glob($this->chartsDir . "/*.svg");
        }
        
        // Mapeo completo de nombres de archivo a títulos y descripciones
        $chartInfo = [
            'mass_composition' => [
                'title' => 'Composición de Masas',
                'description' => 'Distribución de masa en el sistema solar'
            ],
            'temperature_area' => [
                'title' => 'Temperaturas por Tipo', 
                'description' => 'Área bajo la curva de temperaturas por categoría'
            ],
            'size_comparison' => [
                'title' => 'Comparación de Tamaños',
                'description' => 'Diámetros relativos de cuerpos celestes'
            ],
            'orbital_periods' => [
                'title' => 'Períodos Orbitales',
                'description' => 'Duración de órbitas alrededor del Sol'
            ],
            'planet_types' => [
                'title' => 'Tipos de Planetas',
                'description' => 'Distribución por tipo de cuerpo celeste'
            ],
            'moon_distribution' => [
                'title' => 'Distribución de Lunas',
                'description' => 'Número de satélites por planeta'
            ],
            'density_comparison' => [
                'title' => 'Comparación de Densidades',
                'description' => 'Densidad promedio de los cuerpos celestes'
            ]
        ];
        
        foreach ($files as $file) {
            if (is_file($file)) {
                $filename = basename($file);
                $nameWithoutExt = pathinfo($filename, PATHINFO_FILENAME);
                
                $info = $chartInfo[$nameWithoutExt] ?? [
                    'title' => $this->formatTitle($nameWithoutExt),
                    'description' => 'Visualización científica del sistema solar'
                ];
                
                $charts[] = [
                    'url' => $this->chartsDir . '/' . $filename,
                    'title' => $info['title'],
                    'description' => $info['description'],
                    'filename' => $filename,
                    'size' => $this->formatBytes(filesize($file)),
                    'modified' => date('Y-m-d H:i:s', filemtime($file)),
                    'type' => pathinfo($filename, PATHINFO_EXTENSION)
                ];
            }
        }
        
        // Ordenar por un orden específico para mejor presentación
        usort($charts, function($a, $b) {
            $order = [
                'mass_composition' => 1,
                'temperature_area' => 2,
                'size_comparison' => 3,
                'orbital_periods' => 4,
                'planet_types' => 5,
                'moon_distribution' => 6,
                'density_comparison' => 7
            ];
            
            $aOrder = $order[pathinfo($a['filename'], PATHINFO_FILENAME)] ?? 999;
            $bOrder = $order[pathinfo($b['filename'], PATHINFO_FILENAME)] ?? 999;
            
            return $aOrder - $bOrder;
        });
        
        return $charts;
    }
    
    public function forceRegenerateCharts() {
        // Forzar regeneración de todos los gráficos
        $pythonDir = dirname($this->pythonScript);
        $dataFileRelative = "../" . $this->dataFile;
        
        $command = "cd " . escapeshellarg($pythonDir) . " && python " . 
                  escapeshellarg(basename($this->pythonScript)) . " all " . 
                  escapeshellarg($dataFileRelative) . " 2>&1";
        
        exec($command, $output, $returnCode);
        
        if ($returnCode === 0) {
            return [
                'success' => true,
                'message' => 'Gráficos regenerados exitosamente: ' . implode(', ', array_slice($output, -3)),
                'output' => $output
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Error regenerando gráficos: ' . implode(' ', $output),
                'output' => $output
            ];
        }
    }
    
    private function formatTitle($filename) {
        return ucwords(str_replace('_', ' ', $filename));
    }
    
    private function formatBytes($bytes, $precision = 2) {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);
        
        return round($bytes, $precision) . ' ' . $units[$pow];
    }
    
    public function getSystemStatus() {
        $status = [
            'data_file_exists' => file_exists($this->dataFile),
            'python_script_exists' => file_exists($this->pythonScript),
            'charts_dir_exists' => is_dir($this->chartsDir),
            'charts_count' => count(glob($this->chartsDir . "/*.{png,svg}", GLOB_BRACE)),
            'expected_charts' => [
                'mass_composition.png', 'temperature_area.png', 'size_comparison.png',
                'orbital_periods.png', 'planet_types.png', 'moon_distribution.png', 
                'density_comparison.png'
            ]
        ];
        
        // Verificar Python
        exec('python --version 2>&1', $pythonOutput, $pythonReturn);
        $status['python_available'] = $pythonReturn === 0;
        $status['python_version'] = $pythonReturn === 0 ? implode(' ', $pythonOutput) : 'No disponible';
        
        return $status;
    }
}

// Manejar CORS si es necesario
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: Content-Type');

try {
    $generator = new ChartGenerator();
    
    // Procesar diferentes acciones
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['action'])) {
            switch ($_POST['action']) {
                case 'regenerate':
                    $result = $generator->forceRegenerateCharts();
                    echo json_encode($result);
                    exit;
                    
                case 'status':
                    $status = $generator->getSystemStatus();
                    echo json_encode([
                        'success' => true,
                        'status' => $status
                    ]);
                    exit;
            }
        }
    }
    
    // Por defecto, devolver los gráficos existentes
    $charts = $generator->getGeneratedCharts();
    $status = $generator->getSystemStatus();
    
    echo json_encode([
        'success' => true,
        'charts' => $charts,
        'count' => count($charts),
        'status' => $status,
        'message' => count($charts) > 0 ? 
            'Gráficos cargados exitosamente' : 
            'No hay gráficos disponibles. Se generarán automáticamente.'
    ]);
    
} catch (Exception $e) {
    // Manejo de errores
    error_log("Error en generate_charts.php: " . $e->getMessage());
    
    echo json_encode([
        'success' => false,
        'message' => 'Error del servidor: ' . $e->getMessage(),
        'charts' => [],
        'count' => 0
    ]);
}
?>