# Automatización para actualizar todos_los_archivos_php.txt

Para mantener actualizado el archivo consolidado cada vez que se realicen cambios en el código, tienes varias opciones:

## Opción 1: Script PowerShell para ejecución manual

Crea un archivo `actualizar-codigo.ps1` en la raíz del proyecto:

````powershell
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
    Add-Content -Path "todos_los_archivos_php.txt" -Value $fileInfo
}

Write-Host "Archivo 'todos_los_archivos_php.txt' actualizado con éxito"
````

## Opción 2: Hook de Git Post-Commit

Para actualizar automáticamente después de cada commit:

1. Crea el archivo `.git/hooks/post-commit`:

````bash
#!/bin/sh
# Ejecutar el script de PowerShell para actualizar el archivo consolidado
powershell -ExecutionPolicy Bypass -File ./actualizar-codigo.ps1

# Opcionalmente, añadir el archivo actualizado al commit
git add todos_los_archivos_php.txt
git commit --amend --no-edit
````

2. Haz que el hook sea ejecutable (en WSL o Git Bash):

```bash
chmod +x .git/hooks/post-commit
```

## Opción 3: Tarea programada de Windows

Para actualización periódica:

1. Abre el Programador de tareas de Windows
2. Crea una tarea nueva:
   - Nombre: "Actualizar código ENCASA_DATABASE"
   - Acción: Iniciar un programa
   - Programa/script: `powershell.exe`
   - Argumentos: `-ExecutionPolicy Bypass -File "C:\xampp\htdocs\ENCASA_DATABASE\actualizar-codigo.ps1"`
   - Programación: Diaria, Semanal o cuando inicies sesión

## Opción 4: Script BAT para actualización con un solo clic

Crea un archivo `actualizar-codigo.bat` para ejecutar con un simple doble clic:

````batch
@echo off
echo Actualizando archivo de código consolidado...
powershell -ExecutionPolicy Bypass -File "%~dp0actualizar-codigo.ps1"
echo.
echo Proceso completado.
pause
````

## Opción 5: Integración con VS Code

Añade esta tarea a VS Code:

1. Crea o edita el archivo `.vscode/tasks.json`:

````json
{
  "version": "2.0.0",
  "tasks": [
    {
      "label": "Actualizar archivo de código consolidado",
      "type": "shell",
      "command": "powershell",
      "args": [
        "-ExecutionPolicy",
        "Bypass",
        "-File",
        "${workspaceFolder}/actualizar-codigo.ps1"
      ],
      "group": {
        "kind": "build",
        "isDefault": true
      },
      "presentation": {
        "reveal": "always",
        "panel": "new"
      },
      "problemMatcher": []
    }
  ]
}
````

Después podrás ejecutar esta tarea desde VS Code con `Ctrl+Shift+B` o desde la paleta de comandos con `Tareas: Ejecutar tarea de compilación`.

¿Cuál de estas opciones prefieres implementar para mantener actualizado tu archivo?