# Plan de Implementación de la Etapa 4: Modelos Base y CRUD de Miembros

Vamos a dividir la Etapa 4 en fases claramente definidas para completar el CRUD de miembros en tu aplicación. Cada fase incluirá instrucciones detalladas para implementar y probar en el navegador.

## Fase 1: Completar el Modelo Miembro y Relaciones

### Paso 1: Actualizar/Completar el Modelo Miembro

```php
<?php
namespace App\Models;

class Miembro extends Model {
    protected $table = 'InformacionGeneral';
    protected $fillable = [
        'nombres', 'apellidos', 'celular', 'localidad', 'barrio', 
        'fecha_nacimiento', 'invitado_por', 'conector', 'estado_espiritual',
        'recorrido_espiritual', 'foto', 'habeas_data', 'fecha_ingreso',
        'genero', 'profesion'
    ];
    
    /**
     * Obtiene todos los miembros con información básica
     */
    public function getAllWithBasicInfo() {
        $sql = "SELECT m.id, m.nombres, m.apellidos, m.celular, m.localidad, 
                m.barrio, m.fecha_ingreso, m.estado_espiritual
                FROM {$this->table} m
                ORDER BY m.apellidos, m.nombres";
                
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
     * Obtiene un miembro con toda su información relacionada
     */
    public function getFullProfile($id) {
        // Información general
        $miembro = $this->findById($id);
        
        if (!$miembro) {
            return null;
        }
        
        // Información de contacto
        $sql = "SELECT * FROM Contacto WHERE miembro_id = :id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        $miembro['contacto'] = $stmt->fetch();
        
        // Ministerios
        $sql = "SELECT mm.*, m.nombre as ministerio_nombre, r.nombre as rol_nombre
                FROM MiembrosMinisterios mm
                JOIN Ministerios m ON mm.ministerio_id = m.id
                JOIN Roles r ON mm.rol_id = r.id
                WHERE mm.miembro_id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        $miembro['ministerios'] = $stmt->fetchAll();
        
        return $miembro;
    }
    
    /**
     * Guarda un nuevo miembro con su información de contacto
     */
    public function saveWithContacto($miembroData, $contactoData) {
        try {
            $this->db->beginTransaction();
            
            // Crear miembro
            $miembroId = $this->create($miembroData);
            
            if (!$miembroId) {
                throw new \Exception("Error al crear el miembro");
            }
            
            // Crear contacto
            $contactoData['miembro_id'] = $miembroId;
            $contactoModel = new \App\Models\Contacto();
            $contactoId = $contactoModel->create($contactoData);
            
            if (!$contactoId) {
                throw new \Exception("Error al crear la información de contacto");
            }
            
            $this->db->commit();
            return $miembroId;
            
        } catch (\Exception $e) {
            $this->db->rollBack();
            // Registrar el error
            error_log($e->getMessage());
            return false;
        }
    }
    
    /**
     * Obtiene miembros con filtros y paginación
     */
    public function obtenerMiembrosConFiltros($filtros = [], $pagina = 1, $porPagina = 10) {
        $offset = ($pagina - 1) * $porPagina;
        $whereConditions = [];
        $params = [];
        
        // Construir condiciones de filtrado
        if (!empty($filtros['busqueda'])) {
            $whereConditions[] = "(nombres LIKE ? OR apellidos LIKE ? OR celular LIKE ?)";
            $searchTerm = "%{$filtros['busqueda']}%";
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }
        
        if (!empty($filtros['estado'])) {
            $whereConditions[] = "estado_espiritual = ?";
            $params[] = $filtros['estado'];
        }
        
        // Construir la consulta SQL
        $sql = "SELECT m.*, c.correo_electronico, c.telefono 
                FROM {$this->table} m 
                LEFT JOIN Contacto c ON m.id = c.miembro_id";
        
        if (count($whereConditions) > 0) {
            $sql .= " WHERE " . implode(" AND ", $whereConditions);
        }
        
        $sql .= " ORDER BY m.apellidos, m.nombres LIMIT {$offset}, {$porPagina}";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        
        return $stmt->fetchAll();
    }
    
    /**
     * Cuenta miembros con filtros para paginación
     */
    public function contarMiembrosConFiltros($filtros = []) {
        $whereConditions = [];
        $params = [];
        
        // Construir condiciones de filtrado (igual que en obtenerMiembrosConFiltros)
        if (!empty($filtros['busqueda'])) {
            $whereConditions[] = "(nombres LIKE ? OR apellidos LIKE ? OR celular LIKE ?)";
            $searchTerm = "%{$filtros['busqueda']}%";
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }
        
        if (!empty($filtros['estado'])) {
            $whereConditions[] = "estado_espiritual = ?";
            $params[] = $filtros['estado'];
        }
        
        // Construir la consulta SQL
        $sql = "SELECT COUNT(*) as total FROM {$this->table}";
        
        if (count($whereConditions) > 0) {
            $sql .= " WHERE " . implode(" AND ", $whereConditions);
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetch();
        
        return (int) $result['total'];
    }
    
    /**
     * Obtiene todos los estados espirituales disponibles
     */
    public function obtenerEstadosEspirituales() {
        return [
            'Visitante' => 'Visitante',
            'Interesado' => 'Interesado',
            'Nuevo Creyente' => 'Nuevo Creyente',
            'Miembro' => 'Miembro',
            'Líder' => 'Líder',
            'Pastor' => 'Pastor'
        ];
    }
}
```

### Paso 2: Crear el Modelo Contacto

```php
<?php
namespace App\Models;

class Contacto extends Model {
    protected $table = 'Contacto';
    protected $fillable = [
        'miembro_id', 'tipo_documento', 'numero_documento', 
        'telefono', 'pais', 'ciudad', 'direccion', 
        'estado_civil', 'correo_electronico'
    ];
    
    /**
     * Obtiene el contacto de un miembro
     */
    public function findByMiembroId($miembroId) {
        return $this->findOneWhere('miembro_id', $miembroId);
    }
    
    /**
     * Actualiza o crea contacto para un miembro
     */
    public function updateOrCreate($miembroId, $data) {
        $contacto = $this->findByMiembroId($miembroId);
        
        if ($contacto) {
            return $this->update($contacto['id'], $data);
        } else {
            $data['miembro_id'] = $miembroId;
            return $this->create($data);
        }
    }
}
```

### Cómo probarlo:
1. Copia y pega los archivos en sus respectivas ubicaciones
2. En el navegador, accede a: http://localhost/ENCASA_DATABASE/debug.php
3. En el archivo debug.php, añade el siguiente código:

```php
// Probar el modelo Miembro
$miembroModel = new \App\Models\Miembro();
$miembros = $miembroModel->getAllWithBasicInfo();
showResult("Lista básica de miembros", $miembros);
```

## Fase 2: Controlador CRUD de Miembros 

### Paso 1: Implementar el Controlador Completo

```php
<?php
namespace App\Controllers;

use App\Models\Miembro;
use App\Models\Contacto;

class MiembrosController extends Controller {
    private $miembroModel;
    private $contactoModel;
    
    public function __construct() {
        parent::__construct();
        $this->miembroModel = new Miembro();
        $this->contactoModel = new Contacto();
    }
    
    /**
     * Lista de miembros con filtros y paginación
     */
    public function index() {
        // Obtener parámetros de filtrado y paginación
        $busqueda = $_GET['buscar'] ?? '';
        $estado = $_GET['estado'] ?? '';
        $pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
        $porPagina = 10;
        
        $filtros = [];
        if (!empty($busqueda)) {
            $filtros['busqueda'] = $busqueda;
        }
        
        if (!empty($estado)) {
            $filtros['estado'] = $estado;
        }
        
        // Obtener datos
        $miembros = $this->miembroModel->obtenerMiembrosConFiltros($filtros, $pagina, $porPagina);
        $totalMiembros = $this->miembroModel->contarMiembrosConFiltros($filtros);
        $totalPaginas = ceil($totalMiembros / $porPagina);
        $estadosEspirituales = $this->miembroModel->obtenerEstadosEspirituales();
        
        // Renderizar vista
        $this->renderWithLayout('miembros/index', 'default', [
            'title' => 'Directorio de Miembros',
            'miembros' => $miembros,
            'estadosEspirituales' => $estadosEspirituales,
            'busqueda' => $busqueda,
            'estado' => $estado,
            'pagina' => $pagina,
            'totalPaginas' => $totalPaginas,
            'totalMiembros' => $totalMiembros
        ]);
    }
    
    /**
     * Mostrar formulario para crear un miembro
     */
    public function crear() {
        $estadosEspirituales = $this->miembroModel->obtenerEstadosEspirituales();
        
        $this->renderWithLayout('miembros/crear', 'default', [
            'title' => 'Registrar Nuevo Miembro',
            'estadosEspirituales' => $estadosEspirituales
        ]);
    }
    
    /**
     * Guardar un nuevo miembro
     */
    public function guardar() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('miembros');
            return;
        }
        
        // Datos del miembro
        $miembroData = [
            'nombres' => $_POST['nombres'] ?? '',
            'apellidos' => $_POST['apellidos'] ?? '',
            'celular' => $_POST['celular'] ?? '',
            'genero' => $_POST['genero'] ?? '',
            'fecha_nacimiento' => $_POST['fecha_nacimiento'] ?? null,
            'localidad' => $_POST['localidad'] ?? '',
            'barrio' => $_POST['barrio'] ?? '',
            'invitado_por' => $_POST['invitado_por'] ?? null,
            'conector' => $_POST['conector'] ?? '',
            'estado_espiritual' => $_POST['estado_espiritual'] ?? 'Visitante',
            'recorrido_espiritual' => $_POST['recorrido_espiritual'] ?? '',
            'profesion' => $_POST['profesion'] ?? '',
            'fecha_ingreso' => date('Y-m-d'),
            'habeas_data' => isset($_POST['habeas_data']) ? 1 : 0
        ];
        
        // Datos de contacto
        $contactoData = [
            'tipo_documento' => $_POST['tipo_documento'] ?? '',
            'numero_documento' => $_POST['numero_documento'] ?? '',
            'telefono' => $_POST['telefono'] ?? '',
            'pais' => $_POST['pais'] ?? 'Colombia',
            'ciudad' => $_POST['ciudad'] ?? '',
            'direccion' => $_POST['direccion'] ?? '',
            'estado_civil' => $_POST['estado_civil'] ?? '',
            'correo_electronico' => $_POST['correo_electronico'] ?? ''
        ];
        
        // Validación
        $errores = $this->validarDatosMiembro($miembroData);
        
        if (!empty($errores)) {
            $_SESSION['errores'] = $errores;
            $_SESSION['datos_form'] = array_merge($miembroData, $contactoData);
            $this->redirect('miembros/crear');
            return;
        }
        
        // Procesar foto si se subió
        if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
            $rutaFoto = $this->procesarImagen($_FILES['foto']);
            if ($rutaFoto) {
                $miembroData['foto'] = $rutaFoto;
            }
        }
        
        // Guardar en la base de datos
        $miembroId = $this->miembroModel->saveWithContacto($miembroData, $contactoData);
        
        if ($miembroId) {
            $_SESSION['flash_message'] = 'Miembro registrado correctamente';
            $_SESSION['flash_type'] = 'success';
            $this->redirect('miembros/' . $miembroId);
        } else {
            $_SESSION['flash_message'] = 'Error al registrar el miembro';
            $_SESSION['flash_type'] = 'danger';
            $_SESSION['datos_form'] = array_merge($miembroData, $contactoData);
            $this->redirect('miembros/crear');
        }
    }
    
    /**
     * Ver detalles de un miembro
     */
    public function ver($params) {
        $id = $params['id'] ?? 0;
        $miembro = $this->miembroModel->getFullProfile($id);
        
        if (!$miembro) {
            $_SESSION['flash_message'] = 'Miembro no encontrado';
            $_SESSION['flash_type'] = 'warning';
            $this->redirect('miembros');
            return;
        }
        
        $this->renderWithLayout('miembros/ver', 'default', [
            'title' => $miembro['nombres'] . ' ' . $miembro['apellidos'],
            'miembro' => $miembro
        ]);
    }
    
    /**
     * Mostrar formulario para editar un miembro
     */
    public function editar($params) {
        $id = $params['id'] ?? 0;
        $miembro = $this->miembroModel->getFullProfile($id);
        
        if (!$miembro) {
            $_SESSION['flash_message'] = 'Miembro no encontrado';
            $_SESSION['flash_type'] = 'warning';
            $this->redirect('miembros');
            return;
        }
        
        $estadosEspirituales = $this->miembroModel->obtenerEstadosEspirituales();
        
        $this->renderWithLayout('miembros/editar', 'default', [
            'title' => 'Editar: ' . $miembro['nombres'] . ' ' . $miembro['apellidos'],
            'miembro' => $miembro,
            'estadosEspirituales' => $estadosEspirituales
        ]);
    }
    
    /**
     * Actualizar un miembro
     */
    public function actualizar($params) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('miembros');
            return;
        }
        
        $id = $params['id'] ?? 0;
        $miembro = $this->miembroModel->findById($id);
        
        if (!$miembro) {
            $_SESSION['flash_message'] = 'Miembro no encontrado';
            $_SESSION['flash_type'] = 'warning';
            $this->redirect('miembros');
            return;
        }
        
        // Datos del miembro
        $miembroData = [
            'nombres' => $_POST['nombres'] ?? '',
            'apellidos' => $_POST['apellidos'] ?? '',
            'celular' => $_POST['celular'] ?? '',
            'genero' => $_POST['genero'] ?? '',
            'fecha_nacimiento' => $_POST['fecha_nacimiento'] ?? null,
            'localidad' => $_POST['localidad'] ?? '',
            'barrio' => $_POST['barrio'] ?? '',
            'invitado_por' => $_POST['invitado_por'] ?? null,
            'conector' => $_POST['conector'] ?? '',
            'estado_espiritual' => $_POST['estado_espiritual'] ?? 'Visitante',
            'recorrido_espiritual' => $_POST['recorrido_espiritual'] ?? '',
            'profesion' => $_POST['profesion'] ?? '',
            'habeas_data' => isset($_POST['habeas_data']) ? 1 : 0
        ];
        
        // Datos de contacto
        $contactoData = [
            'tipo_documento' => $_POST['tipo_documento'] ?? '',
            'numero_documento' => $_POST['numero_documento'] ?? '',
            'telefono' => $_POST['telefono'] ?? '',
            'pais' => $_POST['pais'] ?? 'Colombia',
            'ciudad' => $_POST['ciudad'] ?? '',
            'direccion' => $_POST['direccion'] ?? '',
            'estado_civil' => $_POST['estado_civil'] ?? '',
            'correo_electronico' => $_POST['correo_electronico'] ?? ''
        ];
        
        // Validación
        $errores = $this->validarDatosMiembro($miembroData);
        
        if (!empty($errores)) {
            $_SESSION['errores'] = $errores;
            $this->redirect('miembros/' . $id . '/editar');
            return;
        }
        
        // Procesar foto si se subió
        if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
            $rutaFoto = $this->procesarImagen($_FILES['foto']);
            if ($rutaFoto) {
                $miembroData['foto'] = $rutaFoto;
                // Eliminar foto anterior si existe y no es la default
                if (!empty($miembro['foto']) && $miembro['foto'] !== 'default.jpg' && file_exists('uploads/fotos/' . $miembro['foto'])) {
                    unlink('uploads/fotos/' . $miembro['foto']);
                }
            }
        }
        
        // Actualizar en la base de datos
        $actualizado = $this->miembroModel->update($id, $miembroData);
        $contacto = $this->contactoModel->findByMiembroId($id);
        
        if ($contacto) {
            $this->contactoModel->update($contacto['id'], $contactoData);
        } else {
            $contactoData['miembro_id'] = $id;
            $this->contactoModel->create($contactoData);
        }
        
        if ($actualizado) {
            $_SESSION['flash_message'] = 'Información actualizada correctamente';
            $_SESSION['flash_type'] = 'success';
        } else {
            $_SESSION['flash_message'] = 'No se realizaron cambios o hubo un error';
            $_SESSION['flash_type'] = 'warning';
        }
        
        $this->redirect('miembros/' . $id);
    }
    
    /**
     * Eliminar un miembro
     */
    public function eliminar($params) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('miembros');
            return;
        }
        
        $id = $params['id'] ?? 0;
        $miembro = $this->miembroModel->findById($id);
        
        if (!$miembro) {
            $_SESSION['flash_message'] = 'Miembro no encontrado';
            $_SESSION['flash_type'] = 'warning';
            $this->redirect('miembros');
            return;
        }
        
        // Verificar permisos de eliminación
        // Por ahora asumimos que el usuario tiene permisos
        
        // Eliminar foto si existe y no es la default
        if (!empty($miembro['foto']) && $miembro['foto'] !== 'default.jpg' && file_exists('uploads/fotos/' . $miembro['foto'])) {
            unlink('uploads/fotos/' . $miembro['foto']);
        }
        
        // Eliminar en la base de datos
        $eliminado = $this->miembroModel->delete($id);
        
        if ($eliminado) {
            $_SESSION['flash_message'] = 'Miembro eliminado correctamente';
            $_SESSION['flash_type'] = 'success';
        } else {
            $_SESSION['flash_message'] = 'Error al eliminar el miembro';
            $_SESSION['flash_type'] = 'danger';
        }
        
        $this->redirect('miembros');
    }
    
    /**
     * Validar datos del miembro
     */
    private function validarDatosMiembro($datos) {
        $errores = [];
        
        if (empty($datos['nombres'])) {
            $errores['nombres'] = 'El nombre es obligatorio';
        }
        
        if (empty($datos['apellidos'])) {
            $errores['apellidos'] = 'Los apellidos son obligatorios';
        }
        
        if (empty($datos['celular'])) {
            $errores['celular'] = 'El número de celular es obligatorio';
        } elseif (!preg_match('/^\+?[0-9]{10,15}$/', $datos['celular'])) {
            $errores['celular'] = 'Formato de celular inválido';
        }
        
        return $errores;
    }
    
    /**
     * Procesar imagen subida
     */
    private function procesarImagen($archivo) {
        // Crear directorio si no existe
        $uploadDir = 'uploads/fotos/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        
        // Verificar tipo de archivo
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($archivo['type'], $allowedTypes)) {
            return false;
        }
        
        // Generar nombre único
        $extension = pathinfo($archivo['name'], PATHINFO_EXTENSION);
        $fileName = uniqid() . '.' . $extension;
        $targetPath = $uploadDir . $fileName;
        
        // Mover archivo
        if (move_uploaded_file($archivo['tmp_name'], $targetPath)) {
            return $fileName;
        }
        
        return false;
    }
}
```

### Paso 2: Actualiza las rutas para el controlador MiembrosController

Verifica que las siguientes rutas existan en routes.php o añádelas si no están:

```php
// Rutas de miembros con el nuevo controlador
$router->get('miembros', 'Miembros', 'index', ['Auth']);
$router->get('miembros/crear', 'Miembros', 'crear', ['Auth']);
$router->post('miembros/guardar', 'Miembros', 'guardar', ['Auth']);
$router->get('miembros/{id}', 'Miembros', 'ver', ['Auth']);
$router->get('miembros/{id}/editar', 'Miembros', 'editar', ['Auth']);
$router->post('miembros/{id}/actualizar', 'Miembros', 'actualizar', ['Auth']);
$router->post('miembros/{id}/eliminar', 'Miembros', 'eliminar', ['Auth', 'AdminOnly']);
```

### Cómo probarlo:
1. Asegúrate de que estás autenticado
2. En el navegador, ve a: http://localhost/ENCASA_DATABASE/miembros

## Fase 3: Implementar Vistas para el CRUD

### Paso 1: Vista del listado de miembros

```php
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Directorio de Miembros</h1>
        <a href="<?= APP_URL ?>/miembros/crear" class="btn btn-primary">
            <i class="fas fa-user-plus"></i> Nuevo Miembro
        </a>
    </div>
    
    <!-- Filtros -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="<?= APP_URL ?>/miembros" class="row g-3">
                <div class="col-md-4">
                    <label for="buscar" class="form-label">Buscar</label>
                    <input type="text" class="form-control" id="buscar" name="buscar" 
                           placeholder="Nombre, apellido o celular" value="<?= htmlspecialchars($busqueda) ?>">
                </div>
                <div class="col-md-3">
                    <label for="estado" class="form-label">Estado Espiritual</label>
                    <select class="form-select" id="estado" name="estado">
                        <option value="">Todos</option>
                        <?php foreach ($estadosEspirituales as $key => $val): ?>
                            <option value="<?= $key ?>" <?= $estado == $key ? 'selected' : '' ?>>
                                <?= $val ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search"></i> Filtrar
                    </button>
                </div>
                <?php if (!empty($busqueda) || !empty($estado)): ?>
                <div class="col-md-2 d-flex align-items-end">
                    <a href="<?= APP_URL ?>/miembros" class="btn btn-outline-secondary w-100">
                        <i class="fas fa-times"></i> Limpiar
                    </a>
                </div>
                <?php endif; ?>
            </form>
        </div>
    </div>
    
    <!-- Resultados -->
    <div class="card">
        <div class="card-body">
            <?php if (count($miembros) > 0): ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Contacto</th>
                                <th>Ubicación</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($miembros as $miembro): ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <?php if (!empty($miembro['foto'])): ?>
                                                <img src="<?= APP_URL ?>/uploads/fotos/<?= $miembro['foto'] ?>" 
                                                     class="rounded-circle me-2" width="40" height="40">
                                            <?php else: ?>
                                                <div class="avatar-placeholder rounded-circle me-2">
                                                    <?= strtoupper(substr($miembro['nombres'], 0, 1)) ?>
                                                </div>
                                            <?php endif; ?>
                                            <div>
                                                <div class="fw-bold"><?= htmlspecialchars($miembro['nombres'] . ' ' . $miembro['apellidos']) ?></div>
                                                <?php if (!empty($miembro['fecha_ingreso'])): ?>
                                                    <small class="text-muted">Desde: <?= date('d/m/Y', strtotime($miembro['fecha_ingreso'])) ?></small>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <?php if (!empty($miembro['celular'])): ?>
                                            <div><i class="fas fa-phone-alt text-muted me-1"></i> <?= htmlspecialchars($miembro['celular']) ?></div>
                                        <?php endif; ?>
                                        <?php if (!empty($miembro['correo_electronico'])): ?>
                                            <div><i class="fas fa-envelope text-muted me-1"></i> <?= htmlspecialchars($miembro['correo_electronico']) ?></div>
                                        <?php endif; ?>
                                        <?php if (!empty($miembro['telefono'])): ?>
                                            <div><i class="fas fa-home text-muted me-1"></i> <?= htmlspecialchars($miembro['telefono']) ?></div>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if (!empty($miembro['localidad']) || !empty($miembro['barrio'])): ?>
                                            <div><i class="fas fa-map-marker-alt text-muted me-1"></i> 
                                                <?= htmlspecialchars($miembro['localidad']) ?>
                                                <?= !empty($miembro['barrio']) ? ' - ' . htmlspecialchars($miembro['barrio']) : '' ?>
                                            </div>
                                        <?php else: ?>
                                            <span class="text-muted">Sin registrar</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="badge bg-<?= $miembro['estado_espiritual'] == 'Miembro' ? 'success' : 
                                               ($miembro['estado_espiritual'] == 'Líder' || $miembro['estado_espiritual'] == 'Pastor' ? 'primary' : 
                                               ($miembro['estado_espiritual'] == 'Visitante' ? 'warning' : 'info')) ?>">
                                            <?= htmlspecialchars($miembro['estado_espiritual']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="<?= APP_URL ?>/miembros/<?= $miembro['id'] ?>" class="btn btn-sm btn-outline-primary" title="Ver detalles">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="<?= APP_URL ?>/miembros/<?= $miembro['id'] ?>/editar" class="btn btn-sm btn-outline-secondary" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-outline-danger" 
                                                    onclick="confirmarEliminar(<?= $miembro['id'] ?>, '<?= htmlspecialchars(addslashes($miembro['nombres'] . ' ' . $miembro['apellidos'])) ?>')" 
                                                    title="Eliminar">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                
                <!-- Paginación -->
                <?php if ($totalPaginas > 1): ?>
                    <nav aria-label="Navegación de páginas">
                        <ul class="pagination justify-content-center mt-4">
                            <li class="page-item <?= $pagina <= 1 ? 'disabled' : '' ?>">
                                <a class="page-link" href="<?= APP_URL ?>/miembros?pagina=<?= $pagina-1 ?>&buscar=<?= urlencode($busqueda) ?>&estado=<?= urlencode($estado) ?>">Anterior</a>
                            </li>
                            <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
                                <li class="page-item <?= $pagina == $i ? 'active' : '' ?>">
                                    <a class="page-link" href="<?= APP_URL ?>/miembros?pagina=<?= $i ?>&buscar=<?= urlencode($busqueda) ?>&estado=<?= urlencode($estado) ?>"><?= $i ?></a>
                                </li>
                            <?php endfor; ?>
                            <li class="page-item <?= $pagina >= $totalPaginas ? 'disabled' : '' ?>">
                                <a class="page-link" href="<?= APP_URL ?>/miembros?pagina=<?= $pagina+1 ?>&buscar=<?= urlencode($busqueda) ?>&estado=<?= urlencode($estado) ?>">Siguiente</a>
                            </li>
                        </ul>
                    </nav>
                <?php endif; ?>
                
                <div class="text-muted text-center mt-2">
                    Mostrando <?= count($miembros) ?> de <?= $totalMiembros ?> miembros
                </div>
                
            <?php else: ?>
                <div class="text-center py-5">
                    <i class="fas fa-search fa-3x text-muted mb-3"></i>
                    <h5>No se encontraron miembros</h5>
                    <p class="text-muted">Intenta con otros filtros o <a href="<?= APP_URL ?>/miembros/crear">registra un nuevo miembro</a></p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Modal confirmar eliminación -->
<div class="modal fade" id="modalEliminar" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Confirmar eliminación</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p>¿Estás seguro que deseas eliminar a <span id="nombreMiembro"></span>?</p>
        <p class="text-danger">Esta acción no se puede deshacer.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <form id="formEliminar" method="POST">
          <button type="submit" class="btn btn-danger">Eliminar</button>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
function confirmarEliminar(id, nombre) {
    document.getElementById('nombreMiembro').textContent = nombre;
    document.getElementById('formEliminar').action = '<?= APP_URL ?>/miembros/' + id + '/eliminar';
    
    var modal = new bootstrap.Modal(document.getElementById('modalEliminar'));
    modal.show();
}
</script>

<style>
.avatar-placeholder {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    background-color: #6c757d;
    color: white;
    font-weight: bold;
}
</style>
```

### Paso 2: Vista de formulario para crear miembro

```php
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Registrar Nuevo Miembro</h1>
        <a href="<?= APP_URL ?>/miembros" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Volver al listado
        </a>
    </div>
    
    <?php if (isset($_SESSION['errores'])): ?>
        <div class="alert alert-danger">
            <h5>Se encontraron errores:</h5>
            <ul class="mb-0">
                <?php foreach ($_SESSION['errores'] as $campo => $mensaje): ?>
                    <li><?= $mensaje ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php unset($_SESSION['errores']); ?>
    <?php endif; ?>
    
    <div class="card">
        <div class="card-body">
            <form action="<?= APP_URL ?>/miembros/guardar" method="POST" enctype="multipart/form-data">
                <div class="row">
                    <!-- Primera columna -->
                    <div class="col-md-6">
                        <h5 class="mb-3">Información Personal</h5>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="nombres" class="form-label">Nombres <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="nombres" name="nombres" required
                                       value="<?= htmlspecialchars($_SESSION['datos_form']['nombres'] ?? '') ?>">
                            </div>
                            <div class="col-md-6">
                                <label for="apellidos" class="form-label">Apellidos <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="apellidos" name="apellidos" required
                                       value="<?= htmlspecialchars($_SESSION['datos_form']['apellidos'] ?? '') ?>">
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="celular" class="form-label">Celular <span class="text-danger">*</span></label>
                                <input type="tel" class="form-control" id="celular" name="celular" required
                                       value="<?= htmlspecialchars($_SESSION['datos_form']['celular'] ?? '') ?>">
                            </div>
                            <div class="col-md-6">
                                <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento</label>
                                <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento"
                                       value="<?= htmlspecialchars($_SESSION['datos_form']['fecha_nacimiento'] ?? '') ?>">
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="genero" class="form-label">Género</label>
                                <select class="form-select" id="genero" name="genero">
                                    <option value="">Seleccionar</option>
                                    <option value="Masculino" <?= isset($_SESSION['datos_form']['genero']) && $_SESSION['datos_form']['genero'] == 'Masculino' ? 'selected' : '' ?>>Masculino</option>
                                    <option value="Femenino" <?= isset($_SESSION['datos_form']['genero']) && $_SESSION['datos_form']['genero'] == 'Femenino' ? 'selected' : '' ?>>Femenino</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="profesion" class="form-label">Profesión u Ocupación</label>
                                <input type="text" class="form-control" id="profesion" name="profesion"
                                       value="<?= htmlspecialchars($_SESSION['datos_form']['profesion'] ?? '') ?>">
                            </div>
                        </div>
                        
                        <!-- Sección de Contacto -->
                        <h5 class="mt-4 mb-3">Información de Contacto</h5>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="correo_electronico" class="form-label">Email</label>
                                <input type="email" class="form-control" id="correo_electronico" name="correo_electronico"
                                       value="<?= htmlspecialchars($_SESSION['datos_form']['correo_electronico'] ?? '') ?>">
                            </div>
                            <div class="col-md-6">
                                <label for="telefono" class="form-label">Teléfono Fijo</label>
                                <input type="tel" class="form-control" id="telefono" name="telefono"
                                       value="<?= htmlspecialchars($_SESSION['datos_form']['telefono'] ?? '') ?>">
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="tipo_documento" class="form-label">Tipo Documento</label>
                                <select class="form-select" id="tipo_documento" name="tipo_documento">
                                    <option value="">Seleccionar</option>
                                    <option value="CC" <?= isset($_SESSION['datos_form']['tipo_documento']) && $_SESSION['datos_form']['tipo_documento'] == 'CC' ? 'selected' : '' ?>>Cédula de Ciudadanía</option>
                                    <option value="CE" <?= isset($_SESSION['datos_form']['tipo_documento']) && $_SESSION['datos_form']['tipo_documento'] == 'CE' ? 'selected' : '' ?>>Cédula de Extranjería</option>
                                    <option value="TI" <?= isset($_SESSION['datos_form']['tipo_documento']) && $_SESSION['datos_form']['tipo_documento'] == 'TI' ? 'selected' : '' ?>>Tarjeta de Identidad</option>
                                    <option value="OTRO" <?= isset($_SESSION['datos_form']['tipo_documento']) && $_SESSION['datos_form']['tipo_documento'] == 'OTRO' ? 'selected' : '' ?>>Otro</option>
                                </select>
                            </div>
                            <div class="col-md-8">
                                <label for="numero_documento" class="form-label">Número de Documento</label>
                                <input type="text" class="form-control" id="numero_documento" name="numero_documento"
                                       value="<?= htmlspecialchars($_SESSION['datos_form']['numero_documento'] ?? '') ?>">
                            </div>
                        </div>
                    </div>
                    
                    <!-- Segunda columna -->
                    <div class="col-md-6">
                        <h5 class="mb-3">Ubicación y Estado Civil</h5>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="localidad" class="form-label">Localidad/Zona</label>
                                <input type="text" class="form-control" id="localidad" name="localidad"
                                       value="<?= htmlspecialchars($_SESSION['datos_form']['localidad'] ?? '') ?>">
                            </div>
                            <div class="col-md-6">
                                <label for="barrio" class="form-label">Barrio</label>
                                <input type="text" class="form-control" id="barrio" name="barrio"
                                       value="<?= htmlspecialchars($_SESSION['datos_form']['barrio'] ?? '') ?>">
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="pais" class="form-label">País</label>
                                <input type="text" class="form-control" id="pais" name="pais" value="Colombia">
                            </div>
                            <div class="col-md-8">
                                <label for="ciudad" class="form-label">Ciudad</label>
                                <input type="text" class="form-control" id="ciudad" name="ciudad" value="Bogotá">
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="direccion" class="form-label">Dirección</label>
                            <input type="text" class="form-control" id="direccion" name="direccion"
                                   value="<?= htmlspecialchars($_SESSION['datos_form']['direccion'] ?? '') ?>">
                        </div>
                        
                        <div class="mb-3">
                            <label for="estado_civil" class="form-label">Estado Civil</label>
                            <select class="form-select" id="estado_civil" name="estado_civil">
                                <option value="">Seleccionar</option>
                                <option value="Soltero" <?= isset($_SESSION['datos_form']['estado_civil']) && $_SESSION['datos_form']['estado_civil'] == 'Soltero' ? 'selected' : '' ?>>Soltero/a</option>
                                <option value="Casado" <?= isset($_SESSION['datos_form']['estado_civil']) && $_SESSION['datos_form']['estado_civil'] == 'Casado' ? 'selected' : '' ?>>Casado/a</option>
                                <option value="Divorciado" <?= isset($_SESSION['datos_form']['estado_civil']) && $_SESSION['datos_form']['estado_civil'] == 'Divorciado' ? 'selected' : '' ?>>Divorciado/a</option>
                                <option value="Viudo" <?= isset($_SESSION['datos_form']['estado_civil']) && $_SESSION['datos_form']['estado_civil'] == 'Viudo' ? 'selected' : '' ?>>Viudo/a</option>
                                <option value="Unión Libre" <?= isset($_SESSION['datos_form']['estado_civil']) && $_SESSION['datos_form']['estado_civil'] == 'Unión Libre' ? 'selected' : '' ?>>Unión Libre</option>
                            </select>
                        </div>
                        
                        <!-- Sección de Estado Espiritual -->
                        <h5 class="mt-4 mb-3">Estado Espiritual e Incorporación</h5>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="estado_espiritual" class="form-label">Estado Espiritual</label>
                                <select class="form-select" id="estado_espir// filepath: c:\xampp\htdocs\ENCASA_DATABASE\app\views\miembros\crear.php
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Registrar Nuevo Miembro</h1>
        <a href="<?= APP_URL ?>/miembros" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Volver al listado
        </a>
    </div>
    
    <?php if (isset($_SESSION['errores'])): ?>
        <div class="alert alert-danger">
            <h5>Se encontraron errores:</h5>
            <ul class="mb-0">
                <?php foreach ($_SESSION['errores'] as $campo => $mensaje): ?>
                    <li><?= $mensaje ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php unset($_SESSION['errores']); ?>
    <?php endif; ?>
    
    <div class="card">
        <div class="card-body">
            <form action="<?= APP_URL ?>/miembros/guardar" method="POST" enctype="multipart/form-data">
                <div class="row">
                    <!-- Primera columna -->
                    <div class="col-md-6">
                        <h5 class="mb-3">Información Personal</h5>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="nombres" class="form-label">Nombres <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="nombres" name="nombres" required
                                       value="<?= htmlspecialchars($_SESSION['datos_form']['nombres'] ?? '') ?>">
                            </div>
                            <div class="col-md-6">
                                <label for="apellidos" class="form-label">Apellidos <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="apellidos" name="apellidos" required
                                       value="<?= htmlspecialchars($_SESSION['datos_form']['apellidos'] ?? '') ?>">
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="celular" class="form-label">Celular <span class="text-danger">*</span></label>
                                <input type="tel" class="form-control" id="celular" name="celular" required
                                       value="<?= htmlspecialchars($_SESSION['datos_form']['celular'] ?? '') ?>">
                            </div>
                            <div class="col-md-6">
                                <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento</label>
                                <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento"
                                       value="<?= htmlspecialchars($_SESSION['datos_form']['fecha_nacimiento'] ?? '') ?>">
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="genero" class="form-label">Género</label>
                                <select class="form-select" id="genero" name="genero">
                                    <option value="">Seleccionar</option>
                                    <option value="Masculino" <?= isset($_SESSION['datos_form']['genero']) && $_SESSION['datos_form']['genero'] == 'Masculino' ? 'selected' : '' ?>>Masculino</option>
                                    <option value="Femenino" <?= isset($_SESSION['datos_form']['genero']) && $_SESSION['datos_form']['genero'] == 'Femenino' ? 'selected' : '' ?>>Femenino</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="profesion" class="form-label">Profesión u Ocupación</label>
                                <input type="text" class="form-control" id="profesion" name="profesion"
                                       value="<?= htmlspecialchars($_SESSION['datos_form']['profesion'] ?? '') ?>">
                            </div>
                        </div>
                        
                        <!-- Sección de Contacto -->
                        <h5 class="mt-4 mb-3">Información de Contacto</h5>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="correo_electronico" class="form-label">Email</label>
                                <input type="email" class="form-control" id="correo_electronico" name="correo_electronico"
                                       value="<?= htmlspecialchars($_SESSION['datos_form']['correo_electronico'] ?? '') ?>">
                            </div>
                            <div class="col-md-6">
                                <label for="telefono" class="form-label">Teléfono Fijo</label>
                                <input type="tel" class="form-control" id="telefono" name="telefono"
                                       value="<?= htmlspecialchars($_SESSION['datos_form']['telefono'] ?? '') ?>">
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="tipo_documento" class="form-label">Tipo Documento</label>
                                <select class="form-select" id="tipo_documento" name="tipo_documento">
                                    <option value="">Seleccionar</option>
                                    <option value="CC" <?= isset($_SESSION['datos_form']['tipo_documento']) && $_SESSION['datos_form']['tipo_documento'] == 'CC' ? 'selected' : '' ?>>Cédula de Ciudadanía</option>
                                    <option value="CE" <?= isset($_SESSION['datos_form']['tipo_documento']) && $_SESSION['datos_form']['tipo_documento'] == 'CE' ? 'selected' : '' ?>>Cédula de Extranjería</option>
                                    <option value="TI" <?= isset($_SESSION['datos_form']['tipo_documento']) && $_SESSION['datos_form']['tipo_documento'] == 'TI' ? 'selected' : '' ?>>Tarjeta de Identidad</option>
                                    <option value="OTRO" <?= isset($_SESSION['datos_form']['tipo_documento']) && $_SESSION['datos_form']['tipo_documento'] == 'OTRO' ? 'selected' : '' ?>>Otro</option>
                                </select>
                            </div>
                            <div class="col-md-8">
                                <label for="numero_documento" class="form-label">Número de Documento</label>
                                <input type="text" class="form-control" id="numero_documento" name="numero_documento"
                                       value="<?= htmlspecialchars($_SESSION['datos_form']['numero_documento'] ?? '') ?>">
                            </div>
                        </div>
                    </div>
                    
                    <!-- Segunda columna -->
                    <div class="col-md-6">
                        <h5 class="mb-3">Ubicación y Estado Civil</h5>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="localidad" class="form-label">Localidad/Zona</label>
                                <input type="text" class="form-control" id="localidad" name="localidad"
                                       value="<?= htmlspecialchars($_SESSION['datos_form']['localidad'] ?? '') ?>">
                            </div>
                            <div class="col-md-6">
                                <label for="barrio" class="form-label">Barrio</label>
                                <input type="text" class="form-control" id="barrio" name="barrio"
                                       value="<?= htmlspecialchars($_SESSION['datos_form']['barrio'] ?? '') ?>">
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="pais" class="form-label">País</label>
                                <input type="text" class="form-control" id="pais" name="pais" value="Colombia">
                            </div>
                            <div class="col-md-8">
                                <label for="ciudad" class="form-label">Ciudad</label>
                                <input type="text" class="form-control" id="ciudad" name="ciudad" value="Bogotá">
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="direccion" class="form-label">Dirección</label>
                            <input type="text" class="form-control" id="direccion" name="direccion"
                                   value="<?= htmlspecialchars($_SESSION['datos_form']['direccion'] ?? '') ?>">
                        </div>
                        
                        <div class="mb-3">
                            <label for="estado_civil" class="form-label">Estado Civil</label>
                            <select class="form-select" id="estado_civil" name="estado_civil">
                                <option value="">Seleccionar</option>
                                <option value="Soltero" <?= isset($_SESSION['datos_form']['estado_civil']) && $_SESSION['datos_form']['estado_civil'] == 'Soltero' ? 'selected' : '' ?>>Soltero/a</option>
                                <option value="Casado" <?= isset($_SESSION['datos_form']['estado_civil']) && $_SESSION['datos_form']['estado_civil'] == 'Casado' ? 'selected' : '' ?>>Casado/a</option>
                                <option value="Divorciado" <?= isset($_SESSION['datos_form']['estado_civil']) && $_SESSION['datos_form']['estado_civil'] == 'Divorciado' ? 'selected' : '' ?>>Divorciado/a</option>
                                <option value="Viudo" <?= isset($_SESSION['datos_form']['estado_civil']) && $_SESSION['datos_form']['estado_civil'] == 'Viudo' ? 'selected' : '' ?>>Viudo/a</option>
                                <option value="Unión Libre" <?= isset($_SESSION['datos_form']['estado_civil']) && $_SESSION['datos_form']['estado_civil'] == 'Unión Libre' ? 'selected' : '' ?>>Unión Libre</option>
                            </select>
                        </div>
                        
                        <!-- Sección de Estado Espiritual -->
                        <h5 class="mt-4 mb-3">Estado Espiritual e Incorporación</h5>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="estado_espiritual" class="form-label">Estado Espiritual</label>
                                <select class="form-select" id="estado_espir

Código similar encontrado con 4 tipos de licencias