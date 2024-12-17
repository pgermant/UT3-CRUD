<?php

function obtenerOpcionesRespuesta($respuestaCorrecta, $localidades)
{
    $opciones = [$respuestaCorrecta];
    while (count($opciones) < 3) {
        $opcion = $localidades[array_rand($localidades)];
        if (!in_array($opcion, $opciones)) {
            $opciones[] = $opcion;
        }
    }
    shuffle($opciones);
    return $opciones;
}

function generarOpcionesMonedas($divisas, $seleccionada = '') {
    $opciones = '';
    foreach ($divisas as $codigo => $info) {
        $selected = ($seleccionada == $codigo) ? 'selected' : '';
        $opciones .= "<option value=\"$codigo\" $selected>$codigo ({$info['simbolo']})</option>";
    }
    return $opciones;
}

function generarOpcionesIslas($islas, $seleccionada = '')
{
    $opciones = '';
    foreach ($islas as $codigo => $nombre) {
        $selected = ($seleccionada == $codigo) ? 'selected' : '';
        $opciones .= '<option value="' . $codigo . '"' . $selected . '>' . $nombre . '</option>';
    }
    return $opciones;
}

// Función para generar la tabla de vuelos
function generarTablaVuelos($vuelosEncontrados)
{
    $tabla = '';
    foreach ($vuelosEncontrados as $vuelo => $info) {
        $tabla .= "<tr>
            <td>" . htmlspecialchars($vuelo) . "</td>
            <td>" . htmlspecialchars($info['fecha_hora_salida']) . "</td>
            <td>" . htmlspecialchars($info['fecha_hora_lleagda']) . "</td>
            <td>" . htmlspecialchars($info['aeropuerto_origen']) . "</td>
            <td>" . htmlspecialchars($info['aeropuerto_destino']) . "</td>

        </tr>";
    }
    return $tabla;
}

function buscarVuelosDisponibles($conexion, $aeropuerto_origen, $aeropuerto_destino, $hora_salida, $hora_llegada) {
    $query = "SELECT * FROM vuelos 
              WHERE aeropuerto_origen = ? 
              AND aeropuerto_destino = ? 
              AND TIME(fecha_hora_salida) >= ? 
              AND TIME(fecha_hora_llegada) <= ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("ssss", $aeropuerto_origen, $aeropuerto_destino, $hora_salida, $hora_llegada);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}

function crearReserva($conexion, $user_id, $vuelo_id, $tipo_tarifa) {
    $query = "INSERT INTO reservas (usuario_id, vuelo_id, tipo_tarifa, fecha_reserva)
              VALUES (?, ?, ?, NOW())";

    if ($stmt = $conexion->prepare($query)) {
        $stmt->bind_param("iis", $user_id, $vuelo_id, $tipo_tarifa);
        if ($stmt->execute()) {
            $stmt->close();
            return true;
        }
        $stmt->close();
    }

    return false;
}


