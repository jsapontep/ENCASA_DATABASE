<?php
// Script para verificar y crear carpetas necesarias

// Corrige las rutas para que coincidan con donde se guardan realmente las fotos
$carpetas = [
    __DIR__ . '/public/uploads',
    __DIR__ . '/public/uploads/miembros'
];

foreach ($carpetas as $carpeta) {
    if (!file_exists($carpeta)) {
        if (mkdir($carpeta, 0777, true)) {
            echo "Carpeta creada: $carpeta<br>";
        } else {
            echo "ERROR: No se pudo crear carpeta: $carpeta<br>";
        }
    } else {
        echo "Carpeta ya existe: $carpeta<br>";
    }
}

// Verificar permisos
foreach ($carpetas as $carpeta) {
    echo "Permisos de $carpeta: " . substr(sprintf('%o', fileperms($carpeta)), -4) . "<br>";
}