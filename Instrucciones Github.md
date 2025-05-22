# Instrucciones para actualizar tu repositorio en GitHub

Para actualizar tu repositorio ENCASA_DATABASE en GitHub, sigue estos pasos:

## 1. Verifica el estado actual de tu repositorio

```bash
cd /Applications/XAMPP/xamppfiles/htdocs/Encasa_Database
git status
```

Este comando te mostrará qué archivos han sido modificados, añadidos o eliminados.

## 2. Añade los cambios al área de preparación

Para añadir todos los archivos modificados:
```bash
git add .
```

Para añadir archivos específicos:
```bash
git add nombre_del_archivo.md
```

## 3. Realiza un commit con tus cambios

```bash
git commit -m "Descripción de los cambios realizados"
```

## 4. Sube los cambios a GitHub

```bash
git push origin main
```

Si el nombre de tu rama principal es diferente (como "master"), reemplaza "main" por el nombre correcto.

## Solución de problemas comunes

- **Si hay conflictos**: Primero descarga los cambios remotos
  ```bash
  git pull origin main
  ```
  
- **Si olvidaste hacer pull antes de modificar**: 
  ```bash
  git stash
  git pull origin main
  git stash pop
  ```

- **Para ver el historial de commits**:
  ```bash
  git log --oneline
  ```

¿Necesitas ayuda con algún paso específico?