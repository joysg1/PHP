<?php

// DEFINIENDO CONSTANTES CORRECTAMENTE

// Opción 1: define() con mayúsculas (RECOMENDADO)
define("NUM", 750);
echo "Constante NUM: " . NUM . "<br>";

// Opción 2: const (para valores escalares)
const NUMERO = 750;
echo "Constante NUMERO: " . NUMERO . "<br>";

// OPERACIONES
$addition = 5 + NUM;
echo "5 + NUM = " . $addition . "<br>";

$multiplicacion = 3 * NUMERO;
echo "3 * NUMERO = " . $multiplicacion . "<br>";

// VERIFICANDO QUE LAS CONSTANTES EXISTEN
echo "<br>--- Verificación ---<br>";
if (defined("NUM")) {
    echo "✅ La constante NUM está definida<br>";
} else {
    echo "❌ La constante NUM NO está definida<br>";
}

if (defined("NUMERO")) {
    echo "✅ La constante NUMERO está definida<br>";
} else {
    echo "❌ La constante NUMERO NO está definida<br>";
}

// EJEMPLO DE ERROR (descomenta para probar)
// echo Num . "<br>"; // ❌ Error case-sensitive
// NUM = 800; // ❌ Error: no se puede redefinir

?>