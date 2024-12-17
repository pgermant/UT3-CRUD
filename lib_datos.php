<?php
include "funciones.php";
$islas = [
    'LPA' => 'Lanzarote',
    'FUE' => 'Fuerteventura',
    'GCR' => 'Gran Canaria',
    'TFN' => 'Tenerife',
    'HIE' => 'El Hierro',
    'GOM' => 'La Gomera',
    'PAL' => 'La Palma'
];

$fecha_hora_salida = [
    "NT223" => "08:00", 
    "NT935" => "10:15", 
    "NT456" => "12:30", 
    "NT789" => "14:45", 
    "NT101" => "17:00", 
    "NT202" => "19:15"
];

$fecha_hora_llegada = [
    "NT223" => "20:00", 
    "NT935" => "21:15", 
    "NT456" => "22:30", 
    "NT789" => "22:45", 
    "NT101" => "23:00", 
    "NT202" => "23:15"
];

$aeropuerto_origen = [
    "NT223" => "LPA", 
    "NT935" => "FUE", 
    "NT456" => "GCR", 
    "NT789" => "TFN", 
    "NT101" => "HIE", 
    "NT202" => "GOM"
];

$aeropuerto_destino = [
    "NT223" => "FUE", 
    "NT935" => "GCR", 
    "NT456" => "TFN", 
    "NT789" => "HIE", 
    "NT101" => "GOM", 
    "NT202" => "PAL"
];

$divisas = [
    'EUR' => ['tasa' => 1, 'simbolo' => '€', 'nombre' => 'Euro'],
    'USD' => ['tasa' => 1.18, 'simbolo' => '$', 'nombre' => 'Dólar estadounidense'],
    'GBP' => ['tasa' => 0.85, 'simbolo' => '£', 'nombre' => 'Libra esterlina'],
    'JPY' => ['tasa' => 130.25, 'simbolo' => '¥', 'nombre' => 'Yen japonés']
];
?>