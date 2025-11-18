<?php
class SolarSystemApp {
    private $dataFile = 'data/celestial_bodies.json';
    private $pythonScript = 'python/charts.py';
    private $chartsDir = 'generated_charts';
    
    public function __construct() {
        // Crear directorio de gráficos si no existe
        if (!is_dir($this->chartsDir)) {
            mkdir($this->chartsDir, 0755, true);
        }
    }
    
    public function getCelestialBodies() {
        if (!file_exists($this->dataFile)) {
            return [];
        }
        
        $jsonData = file_get_contents($this->dataFile);
        $data = json_decode($jsonData, true);
        
        return $data['celestial_bodies'] ?? [];
    }
    
    public function formatMass($mass) {
        if ($mass >= 1e24) {
            return number_format($mass / 1e24, 2) . ' × 10²⁴ kg';
        } elseif ($mass >= 1e21) {
            return number_format($mass / 1e21, 2) . ' × 10²¹ kg';
        } else {
            return number_format($mass, 2) . ' kg';
        }
    }
    
    public function formatTemperature($temp) {
        return number_format($temp) . ' K';
    }
    
    public function formatDiameter($diameter) {
        return number_format($diameter) . ' km';
    }
    
    public function getPreloadedCharts() {
        $charts = [
            [
                'url' => 'generated_charts/mass_composition.png',
                'title' => 'Composición de Masas',
                'description' => 'Distribución de masa en el sistema solar'
            ],
            [
                'url' => 'generated_charts/temperature_area.png',
                'title' => 'Temperaturas por Tipo',
                'description' => 'Área bajo la curva de temperaturas por categoría'
            ],
            [
                'url' => 'generated_charts/size_comparison.png',
                'title' => 'Comparación de Tamaños',
                'description' => 'Diámetros relativos de cuerpos celestes'
            ],
            [
                'url' => 'generated_charts/orbital_periods.png',
                'title' => 'Períodos Orbitales',
                'description' => 'Duración de órbitas alrededor del Sol'
            ],
            [
                'url' => 'generated_charts/planet_types.png',
                'title' => 'Tipos de Planetas',
                'description' => 'Distribución por tipo de cuerpo celeste'
            ],
            [
                'url' => 'generated_charts/moon_distribution.png',
                'title' => 'Distribución de Lunas',
                'description' => 'Número de satélites por planeta'
            ],
            [
                'url' => 'generated_charts/density_comparison.png',
                'title' => 'Comparación de Densidades',
                'description' => 'Densidad promedio de los cuerpos celestes'
            ]
        ];
        
        // Filtrar solo los gráficos que existen
        return array_filter($charts, function($chart) {
            return file_exists($chart['url']);
        });
    }
}

$app = new SolarSystemApp();
$bodies = $app->getCelestialBodies();
$preloadedCharts = $app->getPreloadedCharts();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🌌 Explorador del Sistema Solar | Visualización Científica</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <!-- Fondo espacial -->
    <div class="space-bg"></div>
    <div class="nebula"></div>
    
    <div class="container">
        <!-- Header Hero -->
        <header class="hero-header">
            <h1>🌌 Explorador del Sistema Solar</h1>
            <p>Descubre y compara los cuerpos celestes de nuestro sistema solar con visualizaciones científicas avanzadas</p>
            <div class="hero-subtitle">Datos astronómicos en tiempo real • Gráficos interactivos • Análisis comparativo</div>
        </header>

        <!-- Navegación por Pestañas -->
        <nav class="tabs-navigation">
            <div class="tabs">
                <button class="tab-btn active" data-tab="bodies">
                    <span>🪐</span> Cuerpos Celestes
                </button>
                <button class="tab-btn" data-tab="charts">
                    <span>📊</span> Gráficos
                </button>
                <button class="tab-btn" data-tab="interactive">
                    <span>🔬</span> Análisis Interactivo
                </button>
            </div>
        </nav>

        <!-- Contenido de Pestañas -->
        
        <!-- Pestaña 1: Cuerpos Celestes -->
        <section id="bodies-tab" class="tab-content active">
            <div class="section-header">
                <h2>🪐 Cuerpos Celestes del Sistema Solar</h2>
            </div>
            
            <div class="bodies-grid">
                <?php foreach ($bodies as $body): ?>
                    <div class="body-card" style="border-left-color: <?php echo $body['color']; ?>">
                        <div class="body-header">
                            <div class="body-icon" style="background: <?php echo $body['color']; ?>">
                                <?php echo substr($body['name'], 0, 1); ?>
                            </div>
                            <div class="body-title">
                                <h3><?php echo $body['name']; ?></h3>
                                <div class="body-type"><?php echo ucfirst($body['type']); ?></div>
                            </div>
                        </div>
                        
                        <div class="body-stats">
                            <div class="stat">
                                <div class="stat-value"><?php echo $app->formatMass($body['mass_kg']); ?></div>
                                <div class="stat-label">Masa</div>
                            </div>
                            <div class="stat">
                                <div class="stat-value"><?php echo $app->formatDiameter($body['diameter_km']); ?></div>
                                <div class="stat-label">Diámetro</div>
                            </div>
                            <div class="stat">
                                <div class="stat-value"><?php echo $app->formatTemperature($body['temperature_k']); ?></div>
                                <div class="stat-label">Temperatura</div>
                            </div>
                            <div class="stat">
                                <div class="stat-value"><?php echo $body['orbital_period_days']; ?> días</div>
                                <div class="stat-label">Periodo Orbital</div>
                            </div>
                        </div>
                        
                        <?php if (!empty($body['moons'])): ?>
                            <div class="moons-section">
                                <strong>🌙 Satélites Naturales:</strong>
                                <div class="moons-grid">
                                    <?php foreach ($body['moons'] as $moon): ?>
                                        <div class="moon-card">
                                            <strong><?php echo $moon['name']; ?></strong><br>
                                            <small>
                                                Masa: <?php echo $app->formatMass($moon['mass_kg']); ?><br>
                                                Diámetro: <?php echo $app->formatDiameter($moon['diameter_km']); ?>
                                            </small>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>

        <!-- Pestaña 2: Gráficos con Carrusel -->
        <section id="charts-tab" class="tab-content">
            <div class="carousel-section">
                <div class="section-header">
                    <h2>📊 Visualizaciones Científicas</h2>
                </div>
                
                <div class="carousel-container">
                    <div class="carousel">
                        <?php if (empty($preloadedCharts)): ?>
                            <div class="carousel-item">
                                <img src="data:image/svg+xml,<?php echo urlencode('<svg xmlns="http://www.w3.org/2000/svg" width="320" height="200" viewBox="0 0 320 200"><rect width="320" height="200" fill="#1e293b"/><text x="160" y="100" text-anchor="middle" fill="#64748b" font-family="Arial" font-size="14">Generando gráficos...</text></svg>'); ?>" 
                                     alt="Generando gráficos">
                                <div class="carousel-content">
                                    <h3>Generando Visualizaciones</h3>
                                    <p>Los gráficos se están generando automáticamente</p>
                                </div>
                            </div>
                        <?php else: ?>
                            <?php foreach ($preloadedCharts as $index => $chart): ?>
                                <div class="carousel-item" data-index="<?php echo $index; ?>">
                                    <img src="<?php echo $chart['url']; ?>" 
                                         alt="<?php echo $chart['title']; ?>"
                                         onerror="this.onerror=null; this.src='data:image/svg+xml,<?php echo urlencode('<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"320\" height=\"200\" viewBox=\"0 0 320 200\"><rect width=\"320\" height=\"200\" fill=\"#1e293b\"/><text x=\"160\" y=\"100\" text-anchor=\"middle\" fill=\"#64748b\" font-family=\"Arial\" font-size=\"14\">Gráfico no disponible</text></svg>'); ?>'">
                                    <div class="carousel-content">
                                        <h3><?php echo $chart['title']; ?></h3>
                                        <p><?php echo $chart['description']; ?></p>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    
                    <div class="carousel-nav">
                        <button class="carousel-btn carousel-prev" aria-label="Gráficos anteriores">
                            ‹
                        </button>
                        <button class="carousel-btn carousel-next" aria-label="Siguientes gráficos">
                            ›
                        </button>
                    </div>
                </div>
            </div>
        </section>

        <!-- Pestaña 3: Análisis Interactivo -->
        <section id="interactive-tab" class="tab-content">
            <div class="section-header">
                <h2>🔬 Análisis Interactivo</h2>
            </div>
            
            <div class="chart-controls">
                <h3>📈 Gráficos en Tiempo Real</h3>
                <p>Visualizaciones interactivas usando Chart.js - Actualizados instantáneamente</p>
            </div>
            
            <div class="interactive-grid">
                <div class="interactive-card">
                    <h3>📊 Comparación de Masas</h3>
                    <p class="chart-description">Masa de cuerpos celestes en escala logarítmica para mejor visualización</p>
                    <div class="chart-container">
                        <div id="massChartLoading" class="chart-loading">
                            <div class="spinner"></div>
                            <p>Cargando gráfico de masas...</p>
                        </div>
                        <canvas id="massChart"></canvas>
                    </div>
                </div>
                
                <div class="interactive-card">
                    <h3>🌡️ Comparación de Temperaturas</h3>
                    <p class="chart-description">Temperaturas superficiales de los cuerpos celestes del sistema solar</p>
                    <div class="chart-container">
                        <div id="temperatureChartLoading" class="chart-loading">
                            <div class="spinner"></div>
                            <p>Cargando gráfico de temperaturas...</p>
                        </div>
                        <canvas id="temperatureChart"></canvas>
                    </div>
                </div>
            </div>
            
            <div class="chart-controls">
                <h3>🔍 Herramientas de Análisis</h3>
                <div class="control-group">
                    <button class="btn btn-secondary" onclick="exportData()">
                        <span>📥</span> Exportar Datos
                    </button>
                    <button class="btn btn-secondary" onclick="printCharts()">
                        <span>🖨️</span> Imprimir Reporte
                    </button>
                    <button class="btn" onclick="refreshInteractiveCharts()">
                        <span>🔄</span> Actualizar Gráficos
                    </button>
                </div>
            </div>
        </section>
    </div>

    <!-- Modal para gráficos -->
    <div id="chartModal" class="modal">
        <div class="modal-content">
            <button class="modal-close" aria-label="Cerrar modal">×</button>
            <div class="modal-body">
                <img id="modalChartImg" src="" alt="Gráfico en tamaño completo" style="opacity: 0; transition: opacity 0.3s ease;">
                <h3 id="modalChartTitle" class="modal-title"></h3>
                <div class="modal-nav">
                    <button class="nav-btn prev-chart">
                        ‹ Anterior
                    </button>
                    <span id="modalChartInfo" class="nav-info"></span>
                    <button class="nav-btn next-chart">
                        Siguiente ›
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="js/script.js"></script>
    <script>
        // Datos para gráficos interactivos
        const bodiesData = <?php echo json_encode($bodies); ?>;
        const preloadedCharts = <?php echo json_encode($preloadedCharts); ?>;
        
        // Verificar que los datos estén disponibles
        console.log('Datos cargados:', {
            bodiesCount: bodiesData?.length || 0,
            chartsCount: preloadedCharts?.length || 0
        });
    </script>
</body>
</html>