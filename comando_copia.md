# Script para crear un archivo con todos los archivos PHP

Para crear un archivo de texto que contenga todos los archivos PHP de tu proyecto ENCASA_DATABASE, puedes utilizar el siguiente script de PowerShell:

```powershell
# Navega a la raíz del proyecto
cd C:\xampp\htdocs\ENCASA_DATABASE

# Crear o sobrescribir el archivo consolidado con un encabezado
$timestamp = Get-Date -Format "yyyy-MM-dd HH:mm:ss"
$totalArchivos = (Get-ChildItem -Path . -Filter "*.php" -Recurse).Count

@"
# CÓDIGO CONSOLIDADO DEL PROYECTO ENCASA_DATABASE
# Generado: $timestamp
# Total de archivos: $totalArchivos

"@ | Set-Content -Path "todos_los_archivos_php.txt"

# Encontrar todos los archivos PHP y añadirlos al archivo consolidado
Get-ChildItem -Path . -Filter "*.php" -Recurse | Sort-Object FullName | ForEach-Object {
    $fileContent = Get-Content -Path $_.FullName -Raw
    $fileLastModified = $_.LastWriteTime.ToString("yyyy-MM-dd HH:mm:ss")
    $fileInfo = @"
###############################################################################
# ARCHIVO: $($_.FullName)
# Última modificación: $fileLastModified
# Tamaño: $([math]::Round($_.Length / 1KB, 2)) KB
###############################################################################

$fileContent

###############################################################################
# FIN DEL ARCHIVO: $($_.FullName)
###############################################################################


"@
    Add-Content -Path "todos_los_archivos_php.txt" -Value $fileInfo -Encoding UTF8
}

Write-Host "Archivo 'todos_los_archivos_php.txt' creado exitosamente"
Write-Host "Se han incluido $totalArchivos archivos PHP"
```

## Características del script:

- Crea un archivo llamado `todos_los_archivos_php.txt` en la raíz del proyecto
- Incluye un encabezado con fecha y número total de archivos
- Para cada archivo PHP, añade:
  - La ruta completa del archivo
  - La fecha de última modificación
  - El tamaño en KB
  - El contenido completo del archivo
- Utiliza codificación UTF8 para preservar caracteres especiales
- Organiza los archivos alfabéticamente por ruta

Copia y pega este script en una terminal PowerShell para ejecutarlo.