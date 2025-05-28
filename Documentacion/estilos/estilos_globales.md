# Sistema CSS Global, Dinámico y Reutilizable para ENCASA_DATABASE

## Estructura del Sistema CSS

```
/public/assets/css/
├── main.css                # Archivo principal que importa todos los módulos
├── variables.css           # Variables CSS globales
├── reset.css               # Normalización de estilos
├── utilities/              # Clases utilitarias
│   ├── spacing.css         # Márgenes y padding
│   ├── typography.css      # Estilos de texto
│   ├── colors.css          # Fondos y colores
│   ├── flex.css            # Layout con flexbox
│   └── grid.css            # Layout con grid
└── components/             # Componentes reutilizables
    ├── buttons.css         # Botones
    ├── forms.css           # Formularios e inputs
    ├── tables.css          # Tablas
    ├── cards.css           # Tarjetas
    ├── alerts.css          # Alertas y mensajes
    └── navigation.css      # Navegación
```

## 1. Variables CSS (variables.css)

```css
:root {
  /* Colores principales */
  --primary: #3949ab;         /* Azul primario */
  --primary-light: #6f74dd;
  --primary-dark: #00227b;
  --secondary: #1e88e5;       /* Azul secundario */
  --secondary-light: #6ab7ff;
  --secondary-dark: #005cb2;
  
  /* Colores de estado */
  --success: #43a047;
  --warning: #ffc107;
  --danger: #e53935;
  --info: #039be5;
  
  /* Colores neutros */
  --white: #ffffff;
  --gray-100: #f8f9fa;
  --gray-200: #e9ecef;
  --gray-300: #dee2e6;
  --gray-400: #ced4da;
  --gray-500: #adb5bd;
  --gray-600: #6c757d;
  --gray-700: #495057;
  --gray-800: #343a40;
  --gray-900: #212529;
  --black: #000000;
  
  /* Espaciado */
  --spacing-xs: 0.25rem;    /* 4px */
  --spacing-sm: 0.5rem;     /* 8px */
  --spacing-md: 1rem;       /* 16px */
  --spacing-lg: 1.5rem;     /* 24px */
  --spacing-xl: 2rem;       /* 32px */
  --spacing-xxl: 3rem;      /* 48px */
  
  /* Tipografía */
  --font-family: 'Segoe UI', Roboto, -apple-system, Arial, sans-serif;
  --font-family-heading: var(--font-family);
  
  /* Tamaños de fuente */
  --font-size-xs: 0.75rem;   /* 12px */
  --font-size-sm: 0.875rem;  /* 14px */
  --font-size-md: 1rem;      /* 16px (base) */
  --font-size-lg: 1.125rem;  /* 18px */
  --font-size-xl: 1.25rem;   /* 20px */
  --font-size-2xl: 1.5rem;   /* 24px */
  --font-size-3xl: 2rem;     /* 32px */
  
  /* Bordes */
  --border-radius-sm: 0.25rem;
  --border-radius: 0.375rem;
  --border-radius-lg: 0.5rem;
  --border-width: 1px;
  --border-color: var(--gray-300);
  
  /* Sombras */
  --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
  --shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
  --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
  
  /* Transiciones */
  --transition-fast: 150ms;
  --transition: 300ms;
  --transition-slow: 500ms;
  
  /* Otros */
  --container-width: 1200px;
}
```

## 2. Reset y Normalización (reset.css)

```css
/* Resetear márgenes y padding */
*, *::before, *::after {
  box-sizing: border-box;
  margin: 0;
  padding: 0;
}

/* Mejorar legibilidad y estilo base */
html {
  font-size: 16px;
  line-height: 1.5;
  -webkit-text-size-adjust: 100%;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  scroll-behavior: smooth;
}

body {
  font-family: var(--font-family);
  font-size: var(--font-size-md);
  color: var(--gray-900);
  background-color: var(--gray-100);
}

/* Enlaces limpios */
a {
  color: var(--primary);
  text-decoration: none;
}

a:hover {
  text-decoration: underline;
}

/* Imágenes responsive */
img {
  max-width: 100%;
  height: auto;
}

/* Mejorar estilo de formularios */
button, input, select, textarea {
  font-family: inherit;
  font-size: inherit;
  line-height: inherit;
}

button {
  cursor: pointer;
}
```

## 3. Componente de Botones (components/buttons.css)

```css
/* Estilos base para todos los botones */
.btn {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  padding: var(--spacing-sm) var(--spacing-md);
  border-radius: var(--border-radius);
  font-weight: 500;
  text-align: center;
  cursor: pointer;
  transition: all var(--transition-fast) ease-in-out;
  border: var(--border-width) solid transparent;
  text-decoration: none;
}

.btn:hover {
  opacity: 0.9;
  transform: translateY(-1px);
  text-decoration: none;
}

.btn:focus {
  outline: none;
  box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.5);
}

/* Variantes por color */
.btn-primary {
  background-color: var(--primary);
  color: var(--white);
}

.btn-secondary {
  background-color: var(--secondary);
  color: var(--white);
}

.btn-success {
  background-color: var(--success);
  color: var(--white);
}

.btn-danger {
  background-color: var(--danger);
  color: var(--white);
}

.btn-warning {
  background-color: var(--warning);
  color: var(--gray-900);
}

.btn-info {
  background-color: var(--info);
  color: var(--white);
}

.btn-light {
  background-color: var(--gray-100);
  color: var(--gray-900);
  border-color: var(--gray-300);
}

.btn-dark {
  background-color: var(--gray-800);
  color: var(--white);
}

/* Variantes de botones outline */
.btn-outline-primary {
  color: var(--primary);
  border-color: var(--primary);
  background-color: transparent;
}

.btn-outline-primary:hover {
  background-color: var(--primary);
  color: var(--white);
}

/* Tamaños */
.btn-sm {
  padding: calc(var(--spacing-xs) * 0.75) var(--spacing-sm);
  font-size: var(--font-size-sm);
}

.btn-lg {
  padding: var(--spacing-sm) var(--spacing-lg);
  font-size: var(--font-size-lg);
}

/* Ancho completo */
.btn-block {
  display: block;
  width: 100%;
}

/* Botón con icono */
.btn-icon {
  padding: var(--spacing-sm);
  border-radius: 50%;
}

.btn-icon svg {
  width: 1.25rem;
  height: 1.25rem;
}

/* Estados de botón */
.btn:disabled, .btn.disabled {
  opacity: 0.65;
  pointer-events: none;
}
```

## 4. Utilidades de Espaciado (utilities/spacing.css)

```css
/* Márgenes */
.m-0 { margin: 0 !important; }
.m-xs { margin: var(--spacing-xs) !important; }
.m-sm { margin: var(--spacing-sm) !important; }
.m-md { margin: var(--spacing-md) !important; }
.m-lg { margin: var(--spacing-lg) !important; }
.m-xl { margin: var(--spacing-xl) !important; }

.mt-0 { margin-top: 0 !important; }
.mt-xs { margin-top: var(--spacing-xs) !important; }
.mt-sm { margin-top: var(--spacing-sm) !important; }
.mt-md { margin-top: var(--spacing-md) !important; }
.mt-lg { margin-top: var(--spacing-lg) !important; }
.mt-xl { margin-top: var(--spacing-xl) !important; }

.mr-0 { margin-right: 0 !important; }
.mr-xs { margin-right: var(--spacing-xs) !important; }
.mr-sm { margin-right: var(--spacing-sm) !important; }
.mr-md { margin-right: var(--spacing-md) !important; }
.mr-lg { margin-right: var(--spacing-lg) !important; }
.mr-xl { margin-right: var(--spacing-xl) !important; }

.mb-0 { margin-bottom: 0 !important; }
.mb-xs { margin-bottom: var(--spacing-xs) !important; }
.mb-sm { margin-bottom: var(--spacing-sm) !important; }
.mb-md { margin-bottom: var(--spacing-md) !important; }
.mb-lg { margin-bottom: var(--spacing-lg) !important; }
.mb-xl { margin-bottom: var(--spacing-xl) !important; }

.ml-0 { margin-left: 0 !important; }
.ml-xs { margin-left: var(--spacing-xs) !important; }
.ml-sm { margin-left: var(--spacing-sm) !important; }
.ml-md { margin-left: var(--spacing-md) !important; }
.ml-lg { margin-left: var(--spacing-lg) !important; }
.ml-xl { margin-left: var(--spacing-xl) !important; }

/* Márgenes en eje X e Y */
.mx-0 { margin-left: 0 !important; margin-right: 0 !important; }
.mx-xs { margin-left: var(--spacing-xs) !important; margin-right: var(--spacing-xs) !important; }
.mx-sm { margin-left: var(--spacing-sm) !important; margin-right: var(--spacing-sm) !important; }
.mx-md { margin-left: var(--spacing-md) !important; margin-right: var(--spacing-md) !important; }
.mx-lg { margin-left: var(--spacing-lg) !important; margin-right: var(--spacing-lg) !important; }
.mx-xl { margin-left: var(--spacing-xl) !important; margin-right: var(--spacing-xl) !important; }

.my-0 { margin-top: 0 !important; margin-bottom: 0 !important; }
.my-xs { margin-top: var(--spacing-xs) !important; margin-bottom: var(--spacing-xs) !important; }
.my-sm { margin-top: var(--spacing-sm) !important; margin-bottom: var(--spacing-sm) !important; }
.my-md { margin-top: var(--spacing-md) !important; margin-bottom: var(--spacing-md) !important; }
.my-lg { margin-top: var(--spacing-lg) !important; margin-bottom: var(--spacing-lg) !important; }
.my-xl { margin-top: var(--spacing-xl) !important; margin-bottom: var(--spacing-xl) !important; }

/* Padding */
.p-0 { padding: 0 !important; }
.p-xs { padding: var(--spacing-xs) !important; }
.p-sm { padding: var(--spacing-sm) !important; }
.p-md { padding: var(--spacing-md) !important; }
.p-lg { padding: var(--spacing-lg) !important; }
.p-xl { padding: var(--spacing-xl) !important; }

/* [Continúa con los mismos patrones para pt, pr, pb, pl, px, py] */
```

## 5. Archivo Principal (main.css)

```css
/* Importaciones */
@import 'variables.css';
@import 'reset.css';

/* Utilidades */
@import 'utilities/spacing.css';
@import 'utilities/typography.css';
@import 'utilities/colors.css';
@import 'utilities/flex.css';
@import 'utilities/grid.css';

/* Componentes */
@import 'components/buttons.css';
@import 'components/forms.css';
@import 'components/tables.css';
@import 'components/cards.css';
@import 'components/alerts.css';
@import 'components/navigation.css';

/* Plantillas específicas */
@import 'templates/auth.css';
@import 'templates/dashboard.css';

/* Estilos globales adicionales */
.container {
  width: 100%;
  max-width: var(--container-width);
  margin-left: auto;
  margin-right: auto;
  padding-left: var(--spacing-md);
  padding-right: var(--spacing-md);
}

.page-title {
  font-size: var(--font-size-3xl);
  font-weight: 700;
  margin-bottom: var(--spacing-md);
  color: var(--gray-900);
}

.section {
  margin-bottom: var(--spacing-xl);
}
```

## Implementación en tu Proyecto

1. **Creación de archivos:**

```bash
# Crear estructura de directorios
mkdir -p public/assets/css/utilities public/assets/css/components public/assets/css/templates

# Crear archivos base
touch public/assets/css/main.css
touch public/assets/css/variables.css
touch public/assets/css/reset.css
```

2. **Integrar en tus layouts:**

```php
// En app/views/layouts/default.php
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? $title . ' - ' . APP_NAME : APP_NAME ?></title>
    
    <!-- Sistema CSS Global -->
    <link rel="stylesheet" href="<?= APP_URL ?>/public/assets/css/main.css">
    
    <?php if (isset($page_specific_css)): ?>
    <!-- CSS específico de página -->
    <link rel="stylesheet" href="<?= APP_URL ?>/public/assets/css/pages/<?= $page_specific_css ?>.css">
    <?php endif; ?>
</head>
```

3. **Uso en tus vistas:**

```html
<!-- Ejemplo de uso de botones -->
<div class="container mt-lg">
  <h1 class="page-title">Gestión de Miembros</h1>
  
  <div class="card p-md mb-lg">
    <div class="flex justify-between align-center mb-md">
      <h3>Lista de miembros</h3>
      <a href="/miembros/crear" class="btn btn-primary">
        <svg class="mr-xs" width="16" height="16" viewBox="0 0 24 24" fill="none">
          <path d="M12 4v16m-8-8h16" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
        </svg>
        Nuevo miembro
      </a>
    </div>
    
    <div class="table-container">
      <table class="table">
        <!-- Contenido de la tabla -->
      </table>
    </div>
  </div>
</div>
```

## Temas y Personalización

Para crear un sistema de temas (claro/oscuro), agrega estas variables adicionales:

```css
/* En variables.css */
:root {
  /* Variables básicas... */
  
  /* Colores de tema (modo claro por defecto) */
  --bg-color: var(--white);
  --text-color: var(--gray-900);
  --border-color: var(--gray-300);
  --component-bg: var(--white);
}

/* Tema oscuro */
.theme-dark {
  --bg-color: var(--gray-900);
  --text-color: var(--gray-100);
  --border-color: var(--gray-700);
  --component-bg: var(--gray-800);
  
  /* Redefinir colores para modo oscuro */
  --primary-light: #7986cb;
  --secondary-light: #90caf9;
}
```

Y agregar un script para cambiar de tema:

```javascript
// En tu archivo JavaScript
document.getElementById('theme-toggle').addEventListener('click', () => {
  document.body.classList.toggle('theme-dark');
  
  // Guardar preferencia en localStorage
  const isDarkMode = document.body.classList.contains('theme-dark');
  localStorage.setItem('dark-mode', isDarkMode);
});

// Aplicar tema guardado al cargar
document.addEventListener('DOMContentLoaded', () => {
  if (localStorage.getItem('dark-mode') === 'true') {
    document.body.classList.add('theme-dark');
  }
});
```

## Beneficios de este Enfoque

1. **Mantenibilidad** - Variables CSS centralizadas que facilitan cambios globales
2. **Escalabilidad** - Sistema modular que permite añadir nuevos componentes/utilidades
3. **Consistencia** - Espaciado, colores y tipografía uniforme en toda la aplicación
4. **Rendimiento** - CSS pequeño y específico sin dependencias externas grandes
5. **Flexibilidad** - Compatible con sistemas existentes como Bootstrap
6. **Personalización** - Fácil de adaptar a diferentes temas o marca corporativa

Este sistema te dará una base sólida que puedes ampliar según las necesidades específicas de tu aplicación.

Código similar encontrado con 4 tipos de licencias