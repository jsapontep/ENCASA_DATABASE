# Implementación del Logo en el Sistema ENCASA_DATABASE

Para implementar el logo que has compartido en tu sistema, sigue estos pasos:

## 1. Estructura de archivos para el logo

```
/public/assets/
├── images/
│   ├── logo/
│   │   ├── encasa-logo.svg       # Logo principal (vectorial)
│   │   ├── encasa-logo.png       # Versión PNG con transparencia
│   │   ├── encasa-logo-white.svg # Versión para fondos oscuros
│   │   └── favicon.ico           # Favicon para el navegador
```

## 2. Optimizar el logo

1. **Guarda el logo como SVG** - Este formato vectorial se verá nítido en cualquier tamaño
2. **Crea una versión con texto blanco** para usar sobre fondos oscuros (en el tema dark)

## 3. Agregar estilos para el logo

Añade esto a tu archivo `components/logo.css`:

```css
/* Contenedor del logo */
.logo {
  display: inline-flex;
  align-items: center;
}

/* Tamaños del logo */
.logo-sm {
  height: 30px;
}

.logo-md {
  height: 40px;
}

.logo-lg {
  height: 60px;
}

/* Logo con texto */
.logo-text {
  font-family: var(--font-family);
  font-weight: 700;
  font-size: 1.5rem;
  margin-left: var(--spacing-xs);
}

/* En tema oscuro, cambiar a la versión blanca */
.theme-dark .logo-dark {
  display: none;
}

.logo-light {
  display: none;
}

.theme-dark .logo-light {
  display: inline-block;
}

/* Animación del logo al pasar el cursor */
.logo-animated:hover .logo-arc {
  transform: rotate(10deg);
  transform-origin: center;
  transition: transform var(--transition) ease;
}
```

## 4. Implementar el logo en los layouts

Añade esto a tu archivo default.php en la sección del header:

```php
<header class="navbar">
  <div class="container">
    <a href="<?= APP_URL ?>" class="logo">
      <!-- Logo para tema claro -->
      <img src="<?= APP_URL ?>/public/assets/images/logo/encasa-logo.svg" 
           alt="Iglesia En Casa" 
           class="logo-md logo-dark">
      
      <!-- Logo para tema oscuro -->
      <img src="<?= APP_URL ?>/public/assets/images/logo/encasa-logo-white.svg" 
           alt="Iglesia En Casa" 
           class="logo-md logo-light">
    </a>
    
    <!-- Resto del header -->
  </div>
</header>
```

## 5. Implementar favicon para el navegador

Agrega en el `<head>` de tus layouts:

```php
<link rel="shortcut icon" href="<?= APP_URL ?>/public/assets/images/logo/favicon.ico">
<link rel="apple-touch-icon" href="<?= APP_URL ?>/public/assets/images/logo/apple-touch-icon.png">
```

## 6. Versión responsive del logo

Para mostrar versiones diferentes según el tamaño de pantalla:

```css
/* Versión compacta para móvil (solo el símbolo) */
@media (max-width: 576px) {
  .logo-full {
    display: none;
  }
  
  .logo-symbol {
    display: block;
  }
}

/* Versión completa para escritorio */
@media (min-width: 577px) {
  .logo-full {
    display: block;
  }
  
  .logo-symbol {
    display: none;
  }
}
```

## 7. Ejemplo completo de uso

```html
<div class="navbar bg-white shadow-sm">
  <div class="container d-flex justify-between align-center">
    <a href="/" class="logo">
      <img src="<?= APP_URL ?>/public/assets/images/logo/encasa-logo.svg" 
           alt="Iglesia En Casa" 
           class="logo-md">
      <span class="logo-text d-none d-md-inline">Iglesia En Casa</span>
    </a>
    
    <!-- Botón de tema -->
    <button id="theme-toggle" class="btn btn-light btn-icon">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" 
           stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path>
      </svg>
    </button>
  </div>
</div>
```

¡Con estos pasos tendrás tu logo perfectamente implementado en el sistema CSS global y dinámico que creamos anteriormente!

Código similar encontrado con 1 tipo de licencia