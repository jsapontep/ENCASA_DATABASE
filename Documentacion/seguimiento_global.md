# Comando para actualizar todos_los_archivos.txt

Para integrar todos los archivos PHP del proyecto en un solo archivo consolidado, puedes utilizar el siguiente comando PowerShell que incluye más metadatos y mejor formato:

```powershell
# Navega a la raíz del proyecto
cd C:\xampp\htdocs\ENCASA_DATABASE

# Crear o sobrescribir el archivo consolidado con un encabezado
$timestamp = Get-Date -Format "yyyy-MM-dd HH:mm:ss"
@"
# CÓDIGO CONSOLIDADO DEL PROYECTO ENCASA_DATABASE
# Generado: $timestamp
# Total de archivos: $((Get-ChildItem -Path . -Filter "*.php" -Recurse).Count)

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
    Add-Content -Path "todos_los_archivos_php.txt" -Value $fileInfo
}

Write-Host "Archivo consolidado creado exitosamente en: $(Get-Location)\todos_los_archivos_php.txt"
Write-Host "Contiene $(Get-ChildItem -Path . -Filter "*.php" -Recurse).Count archivos PHP"
```

Este comando:

1. Crea un encabezado con la fecha actual y el número total de archivos PHP
2. Encuentra todos los archivos PHP en el proyecto, ordenados alfabéticamente 
3. Para cada archivo, añade:
   - Una sección de metadatos con la ruta completa, fecha de modificación y tamaño
   - El contenido completo del archivo
   - Un separador claro de fin de archivo
4. Muestra un mensaje confirmando que se ha creado el archivo correctamente

Si prefieres ejecutarlo directamente desde el terminal de VS Code, simplemente copia y pega todo el bloque en una terminal PowerShell.