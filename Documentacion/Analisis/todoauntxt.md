# Script PowerShell para copiar todos los archivos PHP en un único archivo TXT

Aquí tienes un script PowerShell para copiar todos los archivos PHP de tu proyecto a un único archivo TXT con codificación UTF-8:

```powershell
# Ruta donde se encuentra el proyecto
$rutaProyecto = "C:\xampp\htdocs\ENCASA_DATABASE"

# Ruta donde se guardará el archivo de salida
$archivoSalida = "C:\xampp\htdocs\ENCASA_DATABASE\todos_archivos_php.txt"

# Crear o vaciar el archivo de salida
"" | Out-File -FilePath $archivoSalida -Encoding UTF8

# Obtener todos los archivos PHP de forma recursiva
$archivosPhp = Get-ChildItem -Path $rutaProyecto -Filter "*.php" -Recurse

# Recorrer cada archivo y añadir su contenido al archivo de salida
foreach ($archivo in $archivosPhp) {
    # Añadir separador con información del archivo
    "=============================================================" | Out-File -FilePath $archivoSalida -Append -Encoding UTF8
    "ARCHIVO: $($archivo.FullName)" | Out-File -FilePath $archivoSalida -Append -Encoding UTF8
    "=============================================================" | Out-File -FilePath $archivoSalida -Append -Encoding UTF8
    "" | Out-File -FilePath $archivoSalida -Append -Encoding UTF8
    
    # Añadir el contenido del archivo
    Get-Content -Path $archivo.FullName -Encoding UTF8 | Out-File -FilePath $archivoSalida -Append -Encoding UTF8
    
    # Añadir separación entre archivos
    "" | Out-File -FilePath $archivoSalida -Append -Encoding UTF8
    "" | Out-File -FilePath $archivoSalida -Append -Encoding UTF8
}

Write-Host "Proceso completado. Archivo generado en: $archivoSalida"
```

## Instrucciones de uso:

1. Copia todo el código anterior
2. Abre PowerShell (puedes buscarlo en el menú inicio o presionar Win+X y seleccionar Windows PowerShell)
3. Pega el código y presiona Enter
4. El script generará un archivo llamado `todos_archivos_php.txt` en la raíz de tu proyecto

El archivo resultante contendrá todos los archivos PHP del proyecto, separados claramente con el nombre de cada archivo, y se guardará con codificación UTF-8 para preservar caracteres especiales.

# Ruta donde se encuentra el proyecto
$rutaProyecto = "C:\xampp\htdocs\ENCASA_DATABASE"

# Ruta donde se guardará el archivo de salida
$archivoSalida = "C:\xampp\htdocs\ENCASA_DATABASE\todos_archivos_php.txt"

# Crear o vaciar el archivo de salida
"" | Out-File -FilePath $archivoSalida -Encoding UTF8

# Obtener todos los archivos PHP de forma recursiva
$archivosPhp = Get-ChildItem -Path $rutaProyecto -Filter "*.php" -Recurse | 
               Where-Object { $_.FullName -notmatch "phpmailer" }

# Recorrer cada archivo y añadir su contenido al archivo de salida
foreach ($archivo in $archivosPhp) {
    # Añadir separador con información del archivo
    "=============================================================" | Out-File -FilePath $archivoSalida -Append -Encoding UTF8
    "ARCHIVO: $($archivo.FullName)" | Out-File -FilePath $archivoSalida -Append -Encoding UTF8
    "=============================================================" | Out-File -FilePath $archivoSalida -Append -Encoding UTF8
    "" | Out-File -FilePath $archivoSalida -Append -Encoding UTF8
    
    # Añadir el contenido del archivo
    Get-Content -Path $archivo.FullName -Encoding UTF8 | Out-File -FilePath $archivoSalida -Append -Encoding UTF8
    
    # Añadir separación entre archivos
    "" | Out-File -FilePath $archivoSalida -Append -Encoding UTF8
    "" | Out-File -FilePath $archivoSalida -Append -Encoding UTF8
}

Write-Host "Proceso completado. Archivo generado en: $archivoSalida"