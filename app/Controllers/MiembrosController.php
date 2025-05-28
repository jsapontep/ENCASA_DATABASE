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
        
        $_SESSION['flash_message'] = 'Miembro registrado exitosamente';
        $_SESSION['flash_type'] = 'success';
        $this->redirect('miembros/' . $miembroId);
    }

    /**
     * Procesa y guarda la foto subida
     */
    private function procesarFoto($archivo) {
        // Directorio para guardar las fotos
        $directorio = __DIR__ . '/../../public/uploads/miembros/';
        
        // Crear directorio si no existe
        if (!file_exists($directorio)) {
            mkdir($directorio, 0777, true);
        }
        
        // Validar el archivo
        $tiposPermitidos = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($archivo['type'], $tiposPermitidos)) {
            return false;
        }
        
        // Generar nombre único
        $nombreArchivo = uniqid() . '_' . basename($archivo['name']);
        $rutaArchivo = $directorio . $nombreArchivo;
        
        // Mover el archivo
        if (move_uploaded_file($archivo['tmp_name'], $rutaArchivo)) {
            return $nombreArchivo;
        }
        
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
     * Obtiene los parámetros de la URL
     */
    private function getParams() {
        return isset($GLOBALS['router']) ? $GLOBALS['router']->getParams() : [];
    }
}