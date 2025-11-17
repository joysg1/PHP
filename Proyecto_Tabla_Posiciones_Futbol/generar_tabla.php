<?php
function cargarDatosFutbol() {
    $archivo_csv = 'datos/premier_league.csv';
    $datos = [];
    
    if (($handle = fopen($archivo_csv, 'r')) !== FALSE) {
        $encabezados = fgetcsv($handle);
        
        while (($fila = fgetcsv($handle)) !== FALSE) {
            $datos[] = array_combine($encabezados, $fila);
        }
        fclose($handle);
    }
    
    // Ordenar por puntos
    usort($datos, function($a, $b) {
        return $b['puntos'] - $a['puntos'];
    });
    
    return $datos;
}

function obtenerClasePosicion($posicion) {
    if ($posicion <= 4) return 'pos-champions';
    if ($posicion <= 6) return 'pos-europa';
    if ($posicion <= 7) return 'pos-conference';
    if ($posicion >= 18) return 'pos-descenso';
    return '';
}

$equipos = cargarDatosFutbol();
?>

<div class="table-container">
    <h2 style="text-align: center; margin: 1rem 0; color: #d01012;">📊 Tabla de Posiciones Completa</h2>
    
    <table class="stats-table">
        <thead>
            <tr>
                <th>Pos</th>
                <th>Equipo</th>
                <th>PJ</th>
                <th>PG</th>
                <th>PE</th>
                <th>PP</th>
                <th>GF</th>
                <th>GC</th>
                <th>DG</th>
                <th>Pts</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($equipos as $index => $equipo): ?>
            <tr class="<?php echo obtenerClasePosicion($index + 1); ?>">
                <td><strong><?php echo $index + 1; ?></strong></td>
                <td class="team-name"><?php echo htmlspecialchars($equipo['equipo']); ?></td>
                <td><?php echo $equipo['partidos_jugados']; ?></td>
                <td><?php echo $equipo['partidos_ganados']; ?></td>
                <td><?php echo $equipo['partidos_empatados']; ?></td>
                <td><?php echo $equipo['partidos_perdidos']; ?></td>
                <td><strong><?php echo $equipo['goles_favor']; ?></strong></td>
                <td><?php echo $equipo['goles_contra']; ?></td>
                <td><strong><?php echo $equipo['goles_favor'] - $equipo['goles_contra']; ?></strong></td>
                <td><strong style="font-size: 1.1em;"><?php echo $equipo['puntos']; ?></strong></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>