<?php
// Configuración de la aplicación
define('DATA_FILE', 'data/plants.json');
define('PYTHON_SCRIPT', 'python/generate_radar.py');
define('PYTHON_IMAGE_SCRIPT', 'python/generate_plant_images.py');
define('CHARTS_DIR', 'assets/images/radar_charts/');
define('PLANT_IMAGES_DIR', 'assets/images/plants/');

// Función para obtener todas las plantas
function getPlants() {
    if (!file_exists(DATA_FILE)) {
        return [];
    }
    
    $jsonData = file_get_contents(DATA_FILE);
    $plants = json_decode($jsonData, true) ?: [];
    
    // Asignar rutas de imágenes a cada planta
    foreach ($plants as &$plant) {
        $plant = assignImagePath($plant);
    }
    
    return $plants;
}

// Función para asignar ruta de imagen a una planta
function assignImagePath($plant) {
    $imagePath = PLANT_IMAGES_DIR . "plant_{$plant['id']}.png";
    
    // Si la imagen generada existe, usar esa
    if (file_exists($imagePath)) {
        $plant['image'] = $imagePath;
    } else {
        // Si no existe, usar imagen por defecto
        $plant['image'] = 'assets/images/default-plant.svg';
    }
    
    return $plant;
}

// Función para generar imágenes de plantas si no existen
function generatePlantImagesIfNeeded($plants) {
    $imagesDir = PLANT_IMAGES_DIR;
    
    // Verificar si el directorio de imágenes existe
    if (!is_dir($imagesDir)) {
        mkdir($imagesDir, 0777, true);
    }
    
    $allImagesExist = true;
    
    // Verificar si todas las imágenes existen
    foreach ($plants as $plant) {
        $expectedImage = $imagesDir . "plant_{$plant['id']}.png";
        if (!file_exists($expectedImage)) {
            $allImagesExist = false;
            break;
        }
    }
    
    // Generar imágenes si faltan algunas
    if (!$allImagesExist) {
        return generatePlantImages($plants);
    }
    
    return true;
}

// Función para generar imágenes usando Python
function generatePlantImages($plants) {
    $plantsJson = json_encode($plants, JSON_UNESCAPED_UNICODE);
    $tempFile = tempnam(sys_get_temp_dir(), 'plants_');
    file_put_contents($tempFile, $plantsJson);
    
    $outputDir = escapeshellarg(PLANT_IMAGES_DIR);
    $pythonScript = escapeshellarg(PYTHON_IMAGE_SCRIPT);
    $tempFileEscaped = escapeshellarg($tempFile);
    
    $command = "python {$pythonScript} {$tempFileEscaped} {$outputDir} 2>&1";
    exec($command, $output, $returnCode);
    
    // Limpiar archivo temporal
    unlink($tempFile);
    
    return $returnCode === 0;
}

// Función para obtener una planta por ID
function getPlantById($id) {
    $plants = getPlants();
    foreach ($plants as $plant) {
        if ($plant['id'] == $id) {
            return $plant;
        }
    }
    return null;
}

// Función para generar el gráfico de radar
function generateRadarChart($plant1, $plant2) {
    if (!is_dir(CHARTS_DIR)) {
        mkdir(CHARTS_DIR, 0777, true);
    }
    
    $outputFile = CHARTS_DIR . 'comparison_' . time() . '_' . rand(1000, 9999) . '.png';
    
    $plant1Json = escapeshellarg(json_encode($plant1));
    $plant2Json = escapeshellarg(json_encode($plant2));
    $outputPath = escapeshellarg($outputFile);
    
    $command = "python " . PYTHON_SCRIPT . " {$plant1Json} {$plant2Json} {$outputPath}";
    exec($command, $output, $returnCode);
    
    if ($returnCode === 0 && file_exists($outputFile)) {
        // Verificar que el archivo no sea demasiado grande
        $fileSize = filesize($outputFile);
        if ($fileSize > 500000) { // Más de 500KB
            // Regenerar con tamaño más pequeño
            $command = "python " . PYTHON_SCRIPT . " {$plant1Json} {$plant2Json} {$outputPath} 2>&1";
            exec($command, $output, $returnCode);
        }
        return $outputFile;
    }
    
    return false;
}

// Crear imagen por defecto si no existe
function createDefaultPlantImage() {
    $defaultImage = 'assets/images/default-plant.svg';
    $dir = dirname($defaultImage);
    
    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
    }
    
    if (!file_exists($defaultImage)) {
        $svgContent = '<?xml version="1.0" encoding="UTF-8"?>
<svg width="400" height="300" xmlns="http://www.w3.org/2000/svg">
    <defs>
        <linearGradient id="bg" x1="0%" y1="0%" x2="100%" y2="100%">
            <stop offset="0%" stop-color="#f8fafc" />
            <stop offset="100%" stop-color="#e2e8f0" />
        </linearGradient>
        <linearGradient id="plant" x1="0%" y1="0%" x2="100%" y2="100%">
            <stop offset="0%" stop-color="#4caf50" />
            <stop offset="100%" stop-color="#388e3c" />
        </linearGradient>
    </defs>
    
    <!-- Fondo -->
    <rect width="100%" height="100%" fill="url(#bg)" />
    
    <!-- Maceta -->
    <rect x="150" y="220" width="100" height="40" fill="#8d6e63" stroke="#5d4037" stroke-width="2" rx="5" />
    
    <!-- Tallo -->
    <rect x="195" y="120" width="10" height="100" fill="#795548" stroke="#5d4037" stroke-width="1" />
    
    <!-- Hojas -->
    <ellipse cx="220" cy="160" rx="25" ry="15" fill="#66bb6a" stroke="#388e3c" stroke-width="1" transform="rotate(45 220 160)" />
    <ellipse cx="180" cy="180" rx="25" ry="15" fill="#66bb6a" stroke="#388e3c" stroke-width="1" transform="rotate(-45 180 180)" />
    
    <!-- Copa -->
    <circle cx="200" cy="100" r="35" fill="url(#plant)" stroke="#2e7d32" stroke-width="2" />
    
    <!-- Texto -->
    <text x="200" y="50" text-anchor="middle" font-family="Arial, sans-serif" font-size="18" font-weight="bold" fill="#374151">
        Planta Decorativa
    </text>
    <text x="200" y="280" text-anchor="middle" font-family="Arial, sans-serif" font-size="12" fill="#6b7280">
        Imagen representativa
    </text>
    
    <!-- Detalles decorativos -->
    <circle cx="190" cy="95" r="3" fill="#ffeb3b" />
    <circle cx="210" cy="105" r="3" fill="#ffeb3b" />
    <circle cx="200" cy="85" r="3" fill="#ffeb3b" />
</svg>';
        
        file_put_contents($defaultImage, $svgContent);
    }
    
    return $defaultImage;
}

// Función mejorada para verificar y crear recursos necesarios
function initializeApplication() {
    // Crear directorios necesarios
    $directories = [
        'assets/images/plants',
        'assets/images/radar_charts', 
        'data',
        'python'
    ];
    
    foreach ($directories as $dir) {
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
    }
    
    // Crear archivo plants.json si no existe
    if (!file_exists(DATA_FILE)) {
        $defaultPlants = [
            [
                "id" => 1,
                "name" => "Rosa",
                "scientific" => "Rosa spp.",
                "description" => "Arbusto espinoso con flores fragantes en diversos colores. Ideal para jardines ornamentales.",
                "characteristics" => [
                    "resistencia" => 8,
                    "mantenimiento" => 6,
                    "floracion" => 9,
                    "adaptabilidad" => 7,
                    "duracion" => 8
                ]
            ],
            [
                "id" => 2,
                "name" => "Lavanda",
                "scientific" => "Lavandula angustifolia",
                "description" => "Planta aromática mediterránea con flores púrpuras. Perfecta para bordes y jardines secos.",
                "characteristics" => [
                    "resistencia" => 9,
                    "mantenimiento" => 8,
                    "floracion" => 7,
                    "adaptabilidad" => 9,
                    "duracion" => 8
                ]
            ],
            [
                "id" => 3,
                "name" => "Orquídea",
                "scientific" => "Orchidaceae",
                "description" => "Flor exótica de gran belleza. Requiere cuidados específicos pero ofrece recompensas únicas.",
                "characteristics" => [
                    "resistencia" => 4,
                    "mantenimiento" => 3,
                    "floracion" => 9,
                    "adaptabilidad" => 5,
                    "duracion" => 7
                ]
            ],
            [
                "id" => 4,
                "name" => "Cactus",
                "scientific" => "Cactaceae",
                "description" => "Planta suculenta extremadamente resistente. Ideal para principiantes y espacios interiores.",
                "characteristics" => [
                    "resistencia" => 10,
                    "mantenimiento" => 9,
                    "floracion" => 5,
                    "adaptabilidad" => 8,
                    "duracion" => 9
                ]
            ],
            [
                "id" => 5,
                "name" => "Hiedra",
                "scientific" => "Hedera helix",
                "description" => "Planta trepadora de crecimiento rápido. Excelente para cubrir muros y crear setos.",
                "characteristics" => [
                    "resistencia" => 9,
                    "mantenimiento" => 8,
                    "floracion" => 3,
                    "adaptabilidad" => 9,
                    "duracion" => 9
                ]
            ],
            [
                "id" => 6,
                "name" => "Bambú",
                "scientific" => "Bambusoideae",
                "description" => "Planta de crecimiento veloz que aporta un toque exótico y moderno al jardín.",
                "characteristics" => [
                    "resistencia" => 8,
                    "mantenimiento" => 7,
                    "floracion" => 2,
                    "adaptabilidad" => 8,
                    "duracion" => 8
                ]
            ]
        ];
        file_put_contents(DATA_FILE, json_encode($defaultPlants, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }
    
    // Crear imagen por defecto
    createDefaultPlantImage();
}

// Inicializar la aplicación al cargar el config
initializeApplication();
?>