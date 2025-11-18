<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PlantCompare | Sistema de Comparación de Plantas Ornamentales</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <!-- Header -->
    <header class="main-header">
        <div class="container">
            <div class="header-content">
                <div class="logo">
                    <i class="fas fa-leaf"></i>
                    <h1>PlantCompare</h1>
                </div>
                <p class="tagline">Compara y encuentra las plantas ornamentales perfectas para tu espacio</p>
            </div>
        </div>
    </header>

    <div class="container">
        <!-- Panel de Control -->
        <section class="control-panel">
            <div class="panel-header">
                <h2><i class="fas fa-sliders-h"></i> Panel de Control</h2>
                <div class="selection-counter">
                    <span id="selectedCount">0</span>/2 plantas seleccionadas
                </div>
            </div>
            
            <div class="selected-plants-preview" id="selectedPlantsPreview">
                <div class="empty-state">
                    <i class="fas fa-seedling"></i>
                    <p>Selecciona hasta 2 plantas para comparar</p>
                </div>
            </div>
            
            <div class="panel-actions">
                <button class="btn btn-primary" id="compareBtn" disabled>
                    <i class="fas fa-chart-radar"></i>
                    Generar Comparación
                </button>
                <button class="btn btn-secondary" id="clearSelection">
                    <i class="fas fa-eraser"></i>
                    Limpiar Selección
                </button>
            </div>
        </section>

        <!-- Catálogo de Plantas -->
        <section class="plants-catalog">
            <div class="section-header">
                <h2><i class="fas fa-spa"></i> Catálogo de Plantas</h2>
                <div class="search-box">
                    <i class="fas fa-search"></i>
                    <input type="text" id="plantSearch" placeholder="Buscar plantas...">
                </div>
            </div>

            <div class="plants-grid" id="plantsGrid">
                <?php
                require_once 'config.php';
                $plants = getPlants();
                foreach ($plants as $plant): 
                    // Asegurarnos de que la imagen siempre tenga un valor
                    $plantImage = $plant['image'] ?? 'assets/images/default-plant.svg';
                    $plantName = $plant['name'] ?? 'Planta sin nombre';
                    $plantScientific = $plant['scientific'] ?? 'Sin nombre científico';
                    $plantDescription = $plant['description'] ?? 'Descripción no disponible';
                    $plantId = $plant['id'] ?? 0;
                ?>
                <div class="plant-card" data-id="<?= $plantId ?>" data-name="<?= strtolower($plantName) ?>">
                    <div class="card-header">
                        <div class="selection-indicator">
                            <i class="fas fa-check"></i>
                        </div>
                        <img src="<?= $plantImage ?>" alt="<?= $plantName ?>" class="plant-image">
                        <div class="plant-overlay">
                            <button class="btn-select-plant" data-id="<?= $plantId ?>">
                                <i class="fas fa-plus"></i>
                                Seleccionar
                            </button>
                        </div>
                    </div>
                    
                    <div class="card-body">
                        <h3 class="plant-name"><?= $plantName ?></h3>
                        <p class="plant-scientific"><?= $plantScientific ?></p>
                        <p class="plant-description"><?= $plantDescription ?></p>
                        
                        <div class="plant-characteristics">
                            <?php if (isset($plant['characteristics'])): ?>
                            <div class="characteristic">
                                <span class="char-label">Resistencia</span>
                                <div class="char-bar">
                                    <div class="char-fill" style="width: <?= ($plant['characteristics']['resistencia'] ?? 0) * 10 ?>%"></div>
                                </div>
                                <span class="char-value"><?= $plant['characteristics']['resistencia'] ?? 0 ?>/10</span>
                            </div>
                            <div class="characteristic">
                                <span class="char-label">Floración</span>
                                <div class="char-bar">
                                    <div class="char-fill" style="width: <?= ($plant['characteristics']['floracion'] ?? 0) * 10 ?>%"></div>
                                </div>
                                <span class="char-value"><?= $plant['characteristics']['floracion'] ?? 0 ?>/10</span>
                            </div>
                            <?php else: ?>
                            <div class="characteristic">
                                <span class="char-label">Características</span>
                                <span class="char-value">No disponibles</span>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="card-footer">
                        <button class="btn-view-details" data-id="<?= $plantId ?>">
                            <i class="fas fa-info-circle"></i>
                            Detalles
                        </button>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </section>

        <!-- Resultados de Comparación -->
        <section class="comparison-results" id="comparisonResults" style="display: none;">
            <div class="section-header">
                <h2><i class="fas fa-chart-line"></i> Resultados de Comparación</h2>
                <button class="btn-close-results" id="closeResults">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <div class="results-content" id="resultsContent">
                <!-- Los resultados se cargarán aquí -->
            </div>
        </section>
    </div>

    <!-- Footer -->
    <footer class="main-footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-logo">
                    <i class="fas fa-leaf"></i>
                    <span>PlantCompare</span>
                </div>
                <p>&copy; 2023 Sistema de Comparación de Plantas Ornamentales. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>

    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="loading-content">
            <div class="loading-spinner"></div>
            <p>Generando comparación...</p>
        </div>
    </div>

    <script src="assets/js/app.js"></script>
</body>
</html>