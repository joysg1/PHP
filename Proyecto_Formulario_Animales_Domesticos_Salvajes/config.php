<?php
class JSONDatabase {
    private $file_path;
    
    public function __construct($filename = 'animales.json') {
        $this->file_path = __DIR__ . '/data/' . $filename;
        $this->initializeDatabase();
    }
    
    private function initializeDatabase() {
        // Crear directorio data si no existe
        if (!is_dir(dirname($this->file_path))) {
            mkdir(dirname($this->file_path), 0777, true);
        }
        
        // Crear archivo JSON si no existe
        if (!file_exists($this->file_path)) {
            $initial_data = [
                'animales' => [],
                'metadata' => [
                    'total_registros' => 0,
                    'ultimo_id' => 0,
                    'fecha_creacion' => date('Y-m-d H:i:s')
                ]
            ];
            file_put_contents($this->file_path, json_encode($initial_data, JSON_PRETTY_PRINT));
        }
    }
    
    public function getData() {
        if (!file_exists($this->file_path)) {
            $this->initializeDatabase();
        }
        $json_content = file_get_contents($this->file_path);
        return json_decode($json_content, true);
    }
    
    public function saveData($data) {
        $json_content = json_encode($data, JSON_PRETTY_PRINT);
        return file_put_contents($this->file_path, $json_content) !== false;
    }
    
    public function insertAnimal($animal_data) {
        $data = $this->getData();
        
        if (!isset($data['metadata']['ultimo_id'])) {
            $data['metadata']['ultimo_id'] = 0;
        }
        
        // Generar nuevo ID
        $nuevo_id = $data['metadata']['ultimo_id'] + 1;
        
        // Preparar datos del animal
        $animal = [
            'id' => $nuevo_id,
            'nombre' => $animal_data['nombre'],
            'tipo' => $animal_data['tipo'],
            'especie' => $animal_data['especie'],
            'edad' => $animal_data['edad'],
            'habitat' => $animal_data['habitat'],
            'caracteristicas' => $animal_data['caracteristicas'],
            'fecha_registro' => date('Y-m-d H:i:s')
        ];
        
        // Agregar a la base de datos
        $data['animales'][] = $animal;
        
        // Actualizar metadata
        $data['metadata']['total_registros'] = count($data['animales']);
        $data['metadata']['ultimo_id'] = $nuevo_id;
        $data['metadata']['ultima_actualizacion'] = date('Y-m-d H:i:s');
        
        // Guardar
        return $this->saveData($data);
    }
    
    public function getAllAnimals() {
        $data = $this->getData();
        return isset($data['animales']) ? $data['animales'] : [];
    }
    
    public function countAnimals($tipo = null) {
        $animales = $this->getAllAnimals();
        
        if ($tipo) {
            $filtrados = array_filter($animales, function($animal) use ($tipo) {
                return isset($animal['tipo']) && $animal['tipo'] === $tipo;
            });
            return count($filtrados);
        }
        
        return count($animales);
    }
    
    public function getAnimalStats() {
        $animales = $this->getAllAnimals();
        $stats = [
            'total' => count($animales),
            'domesticos' => 0,
            'salvajes' => 0,
            'por_caracteristica' => []
        ];
        
        foreach ($animales as $animal) {
            // Contar por tipo
            if (isset($animal['tipo'])) {
                if ($animal['tipo'] === 'domestico') {
                    $stats['domesticos']++;
                } else if ($animal['tipo'] === 'salvaje') {
                    $stats['salvajes']++;
                }
            }
            
            // Contar por características
            if (isset($animal['caracteristicas']) && is_array($animal['caracteristicas'])) {
                foreach ($animal['caracteristicas'] as $caracteristica) {
                    if (!isset($stats['por_caracteristica'][$caracteristica])) {
                        $stats['por_caracteristica'][$caracteristica] = 0;
                    }
                    $stats['por_caracteristica'][$caracteristica]++;
                }
            }
        }
        
        return $stats;
    }
}

// Función para simular feof() con archivos de texto
function leerArchivoConFeof($filepath) {
    $content = "";
    if (file_exists($filepath)) {
        $handle = fopen($filepath, 'r');
        if ($handle) {
            while (!feof($handle)) {
                $linea = fgets($handle);
                if ($linea !== false) {
                    $content .= $linea;
                }
            }
            fclose($handle);
        }
    }
    return $content;
}

// Función para generar reporte de animales
function generarReporteAnimales($animales) {
    $reporte = "REPORTE DE ANIMALES REGISTRADOS\n";
    $reporte .= "================================\n\n";
    
    if (empty($animales)) {
        $reporte .= "No hay animales registrados en la base de datos.\n";
        return $reporte;
    }
    
    foreach ($animales as $animal) {
        $reporte .= "ID: " . (isset($animal['id']) ? $animal['id'] : 'N/A') . "\n";
        $reporte .= "Nombre: " . (isset($animal['nombre']) ? $animal['nombre'] : 'N/A') . "\n";
        $reporte .= "Tipo: " . (isset($animal['tipo']) ? ucfirst($animal['tipo']) : 'N/A') . "\n";
        $reporte .= "Especie: " . (isset($animal['especie']) ? $animal['especie'] : 'N/A') . "\n";
        $reporte .= "Edad: " . (isset($animal['edad']) ? $animal['edad'] : 'N/A') . " años\n";
        $reporte .= "Hábitat: " . (isset($animal['habitat']) ? $animal['habitat'] : 'N/A') . "\n";
        
        $caracteristicas = isset($animal['caracteristicas']) && is_array($animal['caracteristicas']) ? 
                          implode(', ', $animal['caracteristicas']) : 'Ninguna';
        $reporte .= "Características: " . $caracteristicas . "\n";
        
        $fecha = isset($animal['fecha_registro']) ? $animal['fecha_registro'] : 'N/A';
        $reporte .= "Fecha Registro: " . $fecha . "\n";
        $reporte .= str_repeat("-", 40) . "\n\n";
    }
    
    return $reporte;
}
?>