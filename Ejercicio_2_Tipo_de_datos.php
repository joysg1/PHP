<?php
// =============================================
// EJEMPLO COMPLETO DE TIPOS DE DATOS EN PHP
// =============================================

echo "<h2>Tipos de Datos en PHP</h2>";

// =============================================
// 1. TIPOS ESCALARES (SIMPLES)
// =============================================

echo "<h3>1. Tipos Escalares</h3>";

// ENTEROS (Integer)
$entero = 42;
$entero_negativo = -15;
$entero_hex = 0x1A; // 26 en decimal
$entero_octal = 012; // 10 en decimal

echo "Entero: $entero (tipo: " . gettype($entero) . ")<br>";
echo "Entero negativo: $entero_negativo (tipo: " . gettype($entero_negativo) . ")<br>";
echo "Entero hexadecimal: $entero_hex (tipo: " . gettype($entero_hex) . ")<br>";

// FLOTANTES (Float/Double)
$flotante = 3.1416;
$flotante_negativo = -2.5;
$notacion_cientifica = 1.2e3; // 1200

echo "<br>Flotante: $flotante (tipo: " . gettype($flotante) . ")<br>";
echo "Notación científica: $notacion_cientifica (tipo: " . gettype($notacion_cientifica) . ")<br>";

// CADENAS (String)
$cadena = "Hola Mundo";
$cadena_comillas = 'Texto con comillas simples';
$cadena_multilinea = "Línea 1
Línea 2
Línea 3";

echo "<br>Cadena: $cadena (tipo: " . gettype($cadena) . ")<br>";
echo "Cadena comillas simples: $cadena_comillas (tipo: " . gettype($cadena_comillas) . ")<br>";

// BOOLEANOS (Boolean)
$verdadero = true;
$falso = false;

echo "<br>Verdadero: "; var_dump($verdadero); echo " (tipo: " . gettype($verdadero) . ")<br>";
echo "Falso: "; var_dump($falso); echo " (tipo: " . gettype($falso) . ")<br>";

// =============================================
// 2. TIPOS COMPUESTOS
// =============================================

echo "<h3>2. Tipos Compuestos</h3>";

// ARRAYS
$array_indexado = array("manzana", "banana", "naranja");
$array_asociativo = [
    "nombre" => "Juan",
    "edad" => 25,
    "ciudad" => "Madrid"
];
$array_multidimensional = [
    [1, 2, 3],
    [4, 5, 6],
    [7, 8, 9]
];

echo "Array indexado: "; print_r($array_indexado); echo " (tipo: " . gettype($array_indexado) . ")<br>";
echo "Array asociativo: "; print_r($array_asociativo); echo "<br>";

// OBJETOS
class Persona {
    public $nombre;
    public $edad;
    
    public function __construct($nombre, $edad) {
        $this->nombre = $nombre;
        $this->edad = $edad;
    }
    
    public function presentarse() {
        return "Hola, soy {$this->nombre} y tengo {$this->edad} años";
    }
}

$persona = new Persona("María", 30);
echo "<br>Objeto Persona: "; var_dump($persona);
echo "Método del objeto: " . $persona->presentarse() . "<br>";

// =============================================
// 3. TIPOS ESPECIALES
// =============================================

echo "<h3>3. Tipos Especiales</h3>";

// NULL
$variable_nula = null;
$variable_no_definida; // también será null

echo "Variable nula: "; var_dump($variable_nula); echo " (tipo: " . gettype($variable_nula) . ")<br>";

// RECURSOS (Resources)
$archivo = fopen("ejemplo.txt", "w"); // Abrir un archivo (crea un recurso)
if ($archivo) {
    echo "Recurso (archivo): "; var_dump($archivo); echo "<br>";
    fclose($archivo); // Siempre cerrar el recurso
} else {
    echo "No se pudo crear el recurso de archivo<br>";
}

// =============================================
// 4. FUNCIONES ÚTILES PARA VERIFICAR TIPOS
// =============================================

echo "<h3>4. Funciones de Verificación de Tipos</h3>";

$ejemplo = "123";

echo "Valor: '$ejemplo'<br>";
echo "gettype(): " . gettype($ejemplo) . "<br>";
echo "is_string(): " . (is_string($ejemplo) ? "true" : "false") . "<br>";
echo "is_int(): " . (is_int($ejemplo) ? "true" : "false") . "<br>";
echo "is_numeric(): " . (is_numeric($ejemplo) ? "true" : "false") . "<br>";

// =============================================
// 5. CONVERSIÓN DE TIPOS (TYPE CASTING)
// =============================================

echo "<h3>5. Conversión de Tipos (Type Casting)</h3>";

$numero_como_string = "123.45";
$numero_entero = (int) $numero_como_string;
$numero_float = (float) $numero_como_string;
$numero_string = (string) $numero_entero;
$valor_booleano = (bool) $numero_entero;

echo "Original: '$numero_como_string' (tipo: " . gettype($numero_como_string) . ")<br>";
echo "A entero: $numero_entero (tipo: " . gettype($numero_entero) . ")<br>";
echo "A float: $numero_float (tipo: " . gettype($numero_float) . ")<br>";
echo "A string: '$numero_string' (tipo: " . gettype($numero_string) . ")<br>";
echo "A boolean: "; var_dump($valor_booleano); echo "<br>";

// =============================================
// 6. EJEMPLOS PRÁCTICOS
// =============================================

echo "<h3>6. Ejemplos Prácticos</h3>";

// Calculadora simple
$precio = 19.99;
$cantidad = 3;
$total = $precio * $cantidad;

echo "Precio unitario: \$$precio<br>";
echo "Cantidad: $cantidad<br>";
echo "Total: \$$total<br>";

// Manejo de datos de usuario
$usuario = [
    "username" => "juan_perez",
    "email" => "juan@example.com",
    "activo" => true,
    "puntos" => 1500,
    "ultimo_acceso" => null
];

echo "<br>Datos de usuario:<br>";
foreach ($usuario as $clave => $valor) {
    echo "- $clave: $valor (tipo: " . gettype($valor) . ")<br>";
}

?>
