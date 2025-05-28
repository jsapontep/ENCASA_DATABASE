<?php

namespace App\Controllers;

use App\Models\Miembro;
use App\Models\Contacto;
use App\Models\EstudiosTrabajo;
use App\Models\Tallas;
use App\Models\CarreraBiblica;

class MiembrosController extends Controller {
    private $miembroModel;
    
    public function __construct() {
        parent::__construct();
        $this->miembroModel = new Miembro();
    }
    
    /**
     * Muestra el listado de miembros
     */
    public function index() {
        // Obtener parámetros de filtrado y paginación
        $busqueda = isset($_GET['busqueda']) ? $_GET['busqueda'] : '';
        $pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
        $porPagina = 10;
        
        // Obtener miembros según filtros
        if (!empty($busqueda)) {
            $miembros = $this->miembroModel->buscar($busqueda);
            $totalMiembros = count($miembros);
        } else {
            $miembros = $this->miembroModel->getAll();
            $totalMiembros = count($miembros);
        }
        
        // Implementar paginación manual básica
        $totalPaginas = ceil($totalMiembros / $porPagina);
        $offset = ($pagina - 1) * $porPagina;
        $miembrosPaginados = array_slice($miembros, $offset, $porPagina);
        
        return $this->renderWithLayout('miembros/index', 'default', [
            'miembros' => $miembrosPaginados,
            'busqueda' => $busqueda,
            'pagina' => $pagina,
            'totalPaginas' => $totalPaginas,
            'title' => 'Directorio de Miembros'
        ]);
    }
    
    /**
     * Muestra el formulario para crear un nuevo miembro
     */
    public function crear() {
        return $this->renderWithLayout('miembros/crear', 'default', [
            'title' => 'Registrar Nuevo Miembro'
        ]);
    }
    
    /**
     * Método para guardar un nuevo miembro
     */
    public function guardar() {
        // Verificar que sea una petición POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('miembros');
            return;
        }
        
        // Obtener y validar datos básicos
        $datos = [
            'nombres' => htmlspecialchars($_POST['nombres'] ?? ''),
            'apellidos' => htmlspecialchars($_POST['apellidos'] ?? ''),
            'celular' => htmlspecialchars($_POST['celular'] ?? ''),
            'localidad' => htmlspecialchars($_POST['localidad'] ?? ''),
            'barrio' => htmlspecialchars($_POST['barrio'] ?? ''),
            'fecha_nacimiento' => $_POST['fecha_nacimiento'] ?? null,
            'invitado_por' => !empty($_POST['invitado_por']) ? (int)$_POST['invitado_por'] : null,
            'conector' => htmlspecialchars($_POST['conector'] ?? ''),
            'recorrido_espiritual' => htmlspecialchars($_POST['recorrido_espiritual'] ?? ''),
            'estado_espiritual' => htmlspecialchars($_POST['estado_espiritual'] ?? ''),
            'habeas_data' => isset($_POST['habeas_data']) ? 1 : 0,
        ];
        
        // Validar campos requeridos
        if (empty($datos['nombres']) || empty($datos['apellidos']) || empty($datos['celular'])) {
            $_SESSION['flash_message'] = 'Por favor complete los campos obligatorios';
            $_SESSION['flash_type'] = 'danger';
            $this->redirect('miembros/crear');
            return;
        }
        
        // Procesar la foto si se subió
        if (!empty($_FILES['foto']['name'])) {
            $foto = $this->procesarFoto($_FILES['foto']);
            if ($foto) {
                $datos['foto'] = $foto;
            }
        }
        
        // Guardar información general y obtener el ID insertado
        $miembroId = $this->miembroModel->crear($datos);
        
        if (!$miembroId) {
            $_SESSION['flash_message'] = 'Error al guardar la información del miembro';
            $_SESSION['flash_type'] = 'danger';
            $this->redirect('miembros/crear');
            return;
        }
        
        // Guardar información de contacto
        if (isset($_POST['contacto']) && is_array($_POST['contacto'])) {
            $contacto = $_POST['contacto'];
            $contacto['miembro_id'] = $miembroId;
            $this->miembroModel->guardarContacto($contacto);
        }
        
        // Guardar información de estudios y trabajo
        if (isset($_POST['estudios']) && is_array($_POST['estudios'])) {
            $estudios = $_POST['estudios'];
            $estudios['miembro_id'] = $miembroId;
            $this->miembroModel->guardarEstudiosTrabajo($estudios);
        }
        
        // Guardar tallas
        if (isset($_POST['tallas']) && is_array($_POST['tallas'])) {
            $tallas = $_POST['tallas'];
            $tallas['miembro_id'] = $miembroId;
            $this->miembroModel->guardarTallas($tallas);
        }
        
        // Guardar información de carrera bíblica
        if (isset($_POST['carrera']) && is_array($_POST['carrera'])) {
            $carrera = $_POST['carrera'];
            $carrera['miembro_id'] = $miembroId;
            $this->miembroModel->guardarCarreraBiblica($carrera);
        }
        
        // Guardar información de salud y emergencias
        if (isset($_POST['salud']) && is_array($_POST['salud'])) {
            $salud = $_POST['salud'];
            $salud['miembro_id'] = $miembroId;
            $this->miembroModel->guardarSaludEmergencias($salud);
        }
        
        $_SESSION['flash_message'] = 'Miembro registrado exitosamente';
        $_SESSION['flash_type'] = 'success';
        $this->redirect('miembros/' . $miembroId);
    }

    /**
     * Procesa y guarda la foto subida
     */
    private function procesarFoto($archivo) {
        // Directorio absoluto para guardar las fotos
        $directorio = BASE_PATH . '/public/uploads/miembros/';
        
        // Crear la estructura completa de directorios si no existe
        if (!file_exists($directorio)) {
            if (!mkdir($directorio, 0777, true)) {
                error_log("Error al crear directorio para fotos: $directorio");
                return false;
            }
        }
        
        // Validar el archivo
        $tiposPermitidos = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($archivo['type'], $tiposPermitidos)) {
            error_log("Tipo de archivo no permitido: " . $archivo['type']);
            return false;
        }
        
        // Generar nombre único
        $nombreArchivo = uniqid() . '_' . basename($archivo['name']);
        $rutaArchivo = $directorio . $nombreArchivo;
        
        // Mover el archivo
        if (move_uploaded_file($archivo['tmp_name'], $rutaArchivo)) {
            // Log de éxito para depuración
            error_log("Foto guardada exitosamente en: $rutaArchivo");
            return $nombreArchivo;
        }
        
        error_log("Error al mover archivo subido: " . $archivo['name']);
        return false;
    }
    
    /**
     * Obtiene y muestra el perfil completo del miembro
     */
    public function ver($id = null) {
        // Validar y sanitizar ID
        if (!$id) {
            $uri = $_SERVER['REQUEST_URI'];
            if (preg_match('#/miembros/(\d+)#', $uri, $matches)) {
                $id = (int)$matches[1];
            } else {
                $_SESSION['flash_message'] = 'ID de miembro no especificado';
                $_SESSION['flash_type'] = 'danger';
                $this->redirect('miembros');
                return;
            }
        }
        
        // Asegurar que ID es entero
        $id = (int)$id;
        
        // Obtener datos del miembro
        $miembro = $this->miembroModel->getFullProfile($id);
        
        // Log seguro usando json_encode para arrays
        error_log("Datos del miembro: " . ($miembro ? json_encode($miembro) : "no encontrado"));
        
        if (!$miembro) {
            $_SESSION['flash_message'] = 'Miembro no encontrado';
            $_SESSION['flash_type'] = 'danger';
            $this->redirect('miembros');
            return;
        }
        
        // Renderizar la vista con los datos
        return $this->renderWithLayout('miembros/ver', 'default', [
            'miembro' => $miembro,
            'title' => "Perfil de {$miembro['nombres']} {$miembro['apellidos']}"
        ]);
    }

    /**
     * Muestra el formulario para editar un miembro existente
     */
    public function editar($id = null) {
        // Depuración para ver el ID recibido
        error_log("ID recibido en editar(): " . ($id ?? 'null'));
        
        // Capturar el ID directamente de la URL para asegurar que no hay errores
        $uri = $_SERVER['REQUEST_URI'];
        if (preg_match('#/miembros/editar/(\d+)#', $uri, $matches)) {
            $id_from_url = (int)$matches[1];
            error_log("ID extraído de URL: $id_from_url");
            // Usar el ID de la URL directamente, ignorando el parámetro 
            $id = $id_from_url;
        }
        
        // Asegurar que ID es entero y mayor que cero
        $id = (int)$id;
        if ($id <= 0) {
            $_SESSION['flash_message'] = 'ID de miembro inválido';
            $_SESSION['flash_type'] = 'danger';
            $this->redirect('miembros');
            return;
        }
        
        error_log("Buscando miembro con ID: $id");
        
        // Obtener datos del miembro directamente
        try {
            $miembro = $this->miembroModel->getFullProfile($id);
            
            // Verificar que el miembro obtenido coincide con el ID solicitado
            if ($miembro && $miembro['id'] != $id) {
                error_log("¡ERROR! ID solicitado ($id) no coincide con ID obtenido ({$miembro['id']})");
                // Forzar consulta directa como solución
                $db = \Database::getInstance()->getConnection();
                $stmt = $db->prepare("SELECT * FROM InformacionGeneral WHERE id = ?");
                $stmt->execute([$id]);
                $miembro_correcto = $stmt->fetch(\PDO::FETCH_ASSOC);
                
                if ($miembro_correcto) {
                    $miembro = $this->miembroModel->getFullProfile($miembro_correcto['id']);
                }
            }
        } catch (\Exception $e) {
            error_log("Error al obtener miembro: " . $e->getMessage());
            $miembro = null;
        }
        
        if (!$miembro) {
            $_SESSION['flash_message'] = 'Miembro no encontrado';
            $_SESSION['flash_type'] = 'danger';
            $this->redirect('miembros');
            return;
        }
        
        // Verificación final
        error_log("Mostrando formulario de edición para miembro ID: {$miembro['id']}");
        
        // Obtener lista de miembros para el selector de "invitado por"
        $miembros = $this->miembroModel->getAll();
        
        // Renderizar la vista de edición (reutilizamos el formulario de crear)
        return $this->renderWithLayout('miembros/crear', 'default', [
            'miembro' => $miembro,
            'miembros' => $miembros,
            'title' => "Editar perfil de {$miembro['nombres']} {$miembro['apellidos']}"
        ]);
    }

    /**
     * Procesa la actualización de un miembro existente
     */
    public function actualizar($id = null) {
        // Verificar que sea una petición POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('miembros');
            return;
        }
        
        // Validar y sanitizar ID
        if (!$id) {
            $uri = $_SERVER['REQUEST_URI'];
            if (preg_match('#/miembros/actualizar/(\d+)#', $uri, $matches)) {
                $id = (int)$matches[1];
            } else {
                $_SESSION['flash_message'] = 'ID de miembro no especificado';
                $_SESSION['flash_type'] = 'danger';
                $this->redirect('miembros');
                return;
            }
        }
        
        // Asegurar que ID es entero
        $id = (int)$id;
        
        // Verificar que el miembro existe
        if (!$this->miembroModel->checkMemberExists($id)) {
            $_SESSION['flash_message'] = 'Miembro no encontrado';
            $_SESSION['flash_type'] = 'danger';
            $this->redirect('miembros');
            return;
        }
        
        // Obtener y validar datos básicos
        $datos = [
            'nombres' => htmlspecialchars($_POST['nombres'] ?? ''),
            'apellidos' => htmlspecialchars($_POST['apellidos'] ?? ''),
            'celular' => htmlspecialchars($_POST['celular'] ?? ''),
            'localidad' => htmlspecialchars($_POST['localidad'] ?? ''),
            'barrio' => htmlspecialchars($_POST['barrio'] ?? ''),
            'fecha_nacimiento' => $_POST['fecha_nacimiento'] ?? null,
            'invitado_por' => !empty($_POST['invitado_por']) ? (int)$_POST['invitado_por'] : null,
            'conector' => htmlspecialchars($_POST['conector'] ?? ''),
            'recorrido_espiritual' => htmlspecialchars($_POST['recorrido_espiritual'] ?? ''),
            'estado_espiritual' => htmlspecialchars($_POST['estado_espiritual'] ?? ''),
            'habeas_data' => isset($_POST['habeas_data']) ? 1 : 0,
        ];
        
        // Validar campos requeridos
        if (empty($datos['nombres']) || empty($datos['apellidos']) || empty($datos['celular'])) {
            $_SESSION['flash_message'] = 'Por favor complete los campos obligatorios';
            $_SESSION['flash_type'] = 'danger';
            $this->redirect("miembros/editar/{$id}");
            return;
        }
        
        // Procesar la foto si se subió
        if (!empty($_FILES['foto']['name'])) {
            $foto = $this->procesarFoto($_FILES['foto']);
            if ($foto) {
                $datos['foto'] = $foto;
            }
        }
        
        // Actualizar información general
        $actualizado = $this->miembroModel->update($id, $datos);
        
        if (!$actualizado) {
            $_SESSION['flash_message'] = 'Error al actualizar la información del miembro';
            $_SESSION['flash_type'] = 'danger';
            $this->redirect("miembros/editar/{$id}");
            return;
        }
        
        // Actualizar información de contacto
        if (isset($_POST['contacto']) && is_array($_POST['contacto'])) {
            $contacto = $_POST['contacto'];
            $contacto['miembro_id'] = $id;
            $this->miembroModel->guardarContacto($contacto);
        }
        
        // Actualizar información de estudios y trabajo
        if (isset($_POST['estudios']) && is_array($_POST['estudios'])) {
            $estudios = $_POST['estudios'];
            $estudios['miembro_id'] = $id;
            $this->miembroModel->guardarEstudiosTrabajo($estudios);
        }
        
        // Actualizar tallas
        if (isset($_POST['tallas']) && is_array($_POST['tallas'])) {
            $tallas = $_POST['tallas'];
            $tallas['miembro_id'] = $id;
            $this->miembroModel->guardarTallas($tallas);
        }
        
        // Actualizar información de carrera bíblica
        if (isset($_POST['carrera']) && is_array($_POST['carrera'])) {
            $carrera = $_POST['carrera'];
            $carrera['miembro_id'] = $id;
            $this->miembroModel->guardarCarreraBiblica($carrera);
        }
        
        // Actualizar información de salud y emergencias
        if (isset($_POST['salud']) && is_array($_POST['salud'])) {
            $salud = $_POST['salud'];
            $salud['miembro_id'] = $id;
            $this->miembroModel->guardarSaludEmergencias($salud);
        }
        
        $_SESSION['flash_message'] = 'Información del miembro actualizada exitosamente';
        $_SESSION['flash_type'] = 'success';
        $this->redirect("miembros/{$id}");
    }

    /**
     * Obtiene los parámetros de la URL
     */
    private function getParams() {
        return isset($GLOBALS['router']) ? $GLOBALS['router']->getParams() : [];
    }
}