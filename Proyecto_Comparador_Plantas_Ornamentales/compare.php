<?php
require_once 'config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $plantIds = $input['plantIds'] ?? [];
    
    // Validar que se seleccionen exactamente 2 plantas
    if (count($plantIds) !== 2) {
        http_response_code(400);
        echo json_encode(['error' => 'Debes seleccionar exactamente 2 plantas para comparar']);
        exit;
    }
    
    // Obtener las plantas seleccionadas
    $plant1 = getPlantById($plantIds[0]);
    $plant2 = getPlantById($plantIds[1]);
    
    if (!$plant1 || !$plant2) {
        http_response_code(404);
        echo json_encode(['error' => 'No se encontraron las plantas seleccionadas']);
        exit;
    }
    
    // Asegurarnos de que las plantas tengan imágenes
    $plant1 = assignImagePath($plant1);
    $plant2 = assignImagePath($plant2);
    
    // Generar gráfico de radar
    $chartPath = generateRadarChart($plant1, $plant2);
    
    if ($chartPath) {
        echo json_encode([
            'success' => true,
            'chartPath' => $chartPath,
            'plants' => [$plant1, $plant2],
            'comparison' => [
                'categories' => ['Resistencia', 'Mantenimiento', 'Floración', 'Adaptabilidad', 'Duración'],
                'values' => [
                    $plant1['name'] => [
                        $plant1['characteristics']['resistencia'],
                        $plant1['characteristics']['mantenimiento'],
                        $plant1['characteristics']['floracion'],
                        $plant1['characteristics']['adaptabilidad'],
                        $plant1['characteristics']['duracion']
                    ],
                    $plant2['name'] => [
                        $plant2['characteristics']['resistencia'],
                        $plant2['characteristics']['mantenimiento'],
                        $plant2['characteristics']['floracion'],
                        $plant2['characteristics']['adaptabilidad'],
                        $plant2['characteristics']['duracion']
                    ]
                ]
            ]
        ]);
    } else {
        // Fallback: devolver datos sin gráfico
        echo json_encode([
            'success' => true,
            'chartPath' => null,
            'plants' => [$plant1, $plant2],
            'comparison' => [
                'categories' => ['Resistencia', 'Mantenimiento', 'Floración', 'Adaptabilidad', 'Duración'],
                'values' => [
                    $plant1['name'] => [
                        $plant1['characteristics']['resistencia'],
                        $plant1['characteristics']['mantenimiento'],
                        $plant1['characteristics']['floracion'],
                        $plant1['characteristics']['adaptabilidad'],
                        $plant1['characteristics']['duracion']
                    ],
                    $plant2['name'] => [
                        $plant2['characteristics']['resistencia'],
                        $plant2['characteristics']['mantenimiento'],
                        $plant2['characteristics']['floracion'],
                        $plant2['characteristics']['adaptabilidad'],
                        $plant2['characteristics']['duracion']
                    ]
                ]
            ]
        ]);
    }
} else {
    // GET request: devolver todas las plantas
    $plants = getPlants();
    echo json_encode($plants);
}
?>