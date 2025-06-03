# Script PowerShell para exportar archivos PHP y JS modificados a un TXT

Aquí tienes un script PowerShell que identificará todos los archivos PHP y JS creados o modificados en tu repositorio Git y los exportará a un único archivo de texto para revisión:

````powershell
# Script para exportar archivos PHP y JS modificados o creados a un archivo TXT
# Guardar como: ExportarCambios.ps1

# Configurar la ruta del proyecto
$rutaProyecto = "c:\xampp\htdocs\ENCASA_DATABASE"
$archivoSalida = "c:\xampp\htdocs\ENCASA_DATABASE\archivos_modificados.txt"

# Cambiar al directorio del proyecto
Set-Location -Path $rutaProyecto

# Crear o limpiar el archivo de salida
"# Archivos PHP y JS modificados o creados" | Out-File -FilePath $archivoSalida

# Obtener archivos modificados o creados en Git (no en stage y en stage)
$archivosModificados = git status --porcelain | Where-Object { $_ -match "^\s*[AM].*\.(php|js)$" } | ForEach-Object { $_.Substring(2).Trim() }
$archivosCommit = git diff --name-only HEAD~5 HEAD | Where-Object { $_ -match "\.(php|js)$" }

# Combinar las listas y eliminar duplicados
$todosArchivos = $archivosModificados + $archivosCommit | Select-Object -Unique

# Iterar sobre cada archivo y añadir su contenido al archivo de salida
foreach ($archivo in $todosArchivos) {
    # Verificar que el archivo existe
    if (Test-Path -Path $archivo) {
        # Añadir separador con nombre de archivo
        "`n`n=============================================================`n" | Out-File -FilePath $archivoSalida -Append
        "ARCHIVO: $rutaProyecto\$archivo" | Out-File -FilePath $archivoSalida -Append
        "=============================================================`n" | Out-File -FilePath $archivoSalida -Append
        
        # Añadir el contenido del archivo
        Get-Content -Path $archivo -Raw | Out-File -FilePath $archivoSalida -Append
    }
}

Write-Host "Exportación completada. Los archivos se han guardado en: $archivoSalida"
````

## Instrucciones de uso:

1. Crea un nuevo archivo llamado `ExportarCambios.ps1` con el contenido de arriba
2. Abre PowerShell como administrador
3. Navega hasta la ubicación del script:
   ```powershell
   cd c:\ruta\donde\guardaste\el\script
   ```
4. Ejecuta el script:
   ```powershell
   .\ExportarCambios.ps1
   ```

## Alternativa sin Git

Si prefieres buscar archivos modificados recientemente sin usar Git:

````powershell
# Script alternativo usando fechas de modificación en lugar de Git
# Guardar como: ExportarRecientes.ps1

# Configurar la ruta del proyecto y días hacia atrás para buscar
$rutaProyecto = "c:\xampp\htdocs\ENCASA_DATABASE"
$archivoSalida = "c:\xampp\htdocs\ENCASA_DATABASE\archivos_recientes.txt"
$diasAtras = 3 # Buscar archivos modificados en los últimos 3 días

# Crear o limpiar el archivo de salida
"# Archivos PHP y JS modificados en los últimos $diasAtras días" | Out-File -FilePath $archivoSalida

# Obtener la fecha límite
$fechaLimite = (Get-Date).AddDays(-$diasAtras)

# Encontrar todos los archivos PHP y JS modificados después de la fecha límite
$archivosRecientes = Get-ChildItem -Path $rutaProyecto -Recurse -Include "*.php","*.js" | 
    Where-Object { $_.LastWriteTime -gt $fechaLimite }

# Iterar sobre cada archivo y añadir su contenido al archivo de salida
foreach ($archivo in $archivosRecientes) {
    # Añadir separador con nombre de archivo
    "`n`n=============================================================`n" | Out-File -FilePath $archivoSalida -Append
    "ARCHIVO: $($archivo.FullName)" | Out-File -FilePath $archivoSalida -Append
    "ÚLTIMA MODIFICACIÓN: $($archivo.LastWriteTime)" | Out-File -FilePath $archivoSalida -Append
    "=============================================================`n" | Out-File -FilePath $archivoSalida -Append
    
    # Añadir el contenido del archivo
    Get-Content -Path $archivo.FullName -Raw | Out-File -FilePath $archivoSalida -Append
}

Write-Host "Exportación completada. Los archivos se han guardado en: $archivoSalida"
````

Con estos scripts podrás identificar fácilmente los archivos que podrían estar causando el problema y revisarlos en un solo lugar para encontrar posibles conflictos o errores que estén provocando el problema "La respuesta del servidor no es JSON válido".