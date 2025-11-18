<?php
class APIClient {
    private $base_url = 'http://localhost:5000/api';
    
    private function makeRequest($endpoint, $method = 'GET', $data = null) {
        $url = $this->base_url . $endpoint;
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        
        $headers = [
            'Content-Type: application/json',
            'Accept: application/json'
        ];
        
        if ($data && in_array($method, ['POST', 'PUT'])) {
            $json_data = json_encode($data);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
            $headers[] = 'Content-Length: ' . strlen($json_data);
        }
        
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);
        
        if ($error) {
            error_log("Error CURL: " . $error);
            return [
                'success' => false,
                'error' => 'Error de conexión con el servidor API'
            ];
        }
        
        if (!$response) {
            return [
                'success' => false,
                'error' => 'No se recibió respuesta del servidor API'
            ];
        }
        
        $result = json_decode($response, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            return [
                'success' => false,
                'error' => 'Error decodificando respuesta JSON'
            ];
        }
        
        if ($http_code >= 400) {
            return [
                'success' => false,
                'error' => $result['error'] ?? 'Error en la solicitud (HTTP ' . $http_code . ')',
                'http_code' => $http_code
            ];
        }
        
        return $result;
    }
    
    public function obtenerEstudiantes() {
        $result = $this->makeRequest('/estudiantes');
        return $result['success'] ? $result['data'] : [];
    }
    
    public function obtenerEstudiante($id) {
        $result = $this->makeRequest('/estudiantes/' . $id);
        return $result['success'] ? $result['data'] : null;
    }
    
    public function obtenerEstudiantePorCedula($cedula) {
        $result = $this->makeRequest('/estudiantes/cedula/' . $cedula);
        return $result['success'] ? $result['data'] : null;
    }
    
    public function crearEstudiante($datos) {
        return $this->makeRequest('/estudiantes', 'POST', $datos);
    }
    
    public function actualizarEstudiante($id, $datos) {
        return $this->makeRequest('/estudiantes/' . $id, 'PUT', $datos);
    }
    
    public function eliminarEstudiante($id) {
        return $this->makeRequest('/estudiantes/' . $id, 'DELETE');
    }
    
    public function eliminarMateria($cedula, $materia_index) {
        return $this->makeRequest("/estudiantes/{$cedula}/materias/{$materia_index}", 'DELETE');
    }
    
    public function obtenerEstadisticas() {
        $result = $this->makeRequest('/estadisticas');
        return $result['success'] ? $result['data'] : [];
    }
    
    public function healthCheck() {
        return $this->makeRequest('/health');
    }
}

// Funciones helper para compatibilidad
$apiClient = new APIClient();

function obtenerEstudiantes() {
    global $apiClient;
    return $apiClient->obtenerEstudiantes();
}

function obtenerEstudiantePorId($id) {
    global $apiClient;
    return $apiClient->obtenerEstudiante($id);
}

function buscarEstudiantePorCedula($cedula) {
    global $apiClient;
    return $apiClient->obtenerEstudiantePorCedula($cedula);
}

function guardarEstudiante($datos) {
    global $apiClient;
    return $apiClient->crearEstudiante($datos);
}

function actualizarEstudiante($id, $datos) {
    global $apiClient;
    return $apiClient->actualizarEstudiante($id, $datos);
}

function eliminarEstudiante($id) {
    global $apiClient;
    return $apiClient->eliminarEstudiante($id);
}

function eliminarMateriaEstudiante($cedula, $materia_index) {
    global $apiClient;
    return $apiClient->eliminarMateria($cedula, $materia_index);
}

function obtenerEstadisticas() {
    global $apiClient;
    return $apiClient->obtenerEstadisticas();
}

function obtenerCarrerasEstudiante($cedula) {
    $estudiante = buscarEstudiantePorCedula($cedula);
    if (!$estudiante || !isset($estudiante['materias'])) {
        return [];
    }
    
    $carreras = [];
    foreach ($estudiante['materias'] as $materia) {
        if (!in_array($materia['carrera'], $carreras)) {
            $carreras[] = $materia['carrera'];
        }
    }
    return $carreras;
}

function materiaExisteEnCarrera($cedula, $materia, $carrera) {
    $estudiante = buscarEstudiantePorCedula($cedula);
    if (!$estudiante || !isset($estudiante['materias'])) {
        return false;
    }
    
    foreach ($estudiante['materias'] as $materia_reg) {
        if ($materia_reg['materia'] == $materia && $materia_reg['carrera'] == $carrera) {
            return true;
        }
    }
    return false;
}

function verificarAPI() {
    global $apiClient;
    $result = $apiClient->healthCheck();
    return $result['success'] ?? false;
}
?>