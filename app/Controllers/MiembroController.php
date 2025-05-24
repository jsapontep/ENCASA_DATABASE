<?php

namespace App\Controllers;

use App\Models\InformacionGeneral;
use App\Models\Contacto;

class MiembroController extends Controller {
    private $miembroModel;
    private $contactoModel;
    
    public function __construct() {
        parent::__construct();
        $this->miembroModel = new InformacionGeneral();
        $this->contactoModel = new Contacto();
    }
    
    public function index() {
        // Verificar permisos
        if (!$this->tienePermiso('ver_miembros')) {
            $this->redirect('acceso-denegado');
            return;
        }
        
        $filtros = [];
        $busqueda = $_GET['buscar'] ?? '';
        $estado = $_GET['estado'] ?? '';
        
        if (!empty($busqueda)) {
            $filtros['busqueda'] = $busqueda;
        }
        
        if (!empty($estado)) {
            $filtros['estado'] = $estado;
        }
        
        // Paginación
        $pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
        $porPagina = 10;
        
        $miembros = $this->miembroModel->obtenerMiembrosConFiltros($filtros, $pagina, $porPagina);
        $totalMiembros = $this->miembroModel->contarMiembrosConFiltros($filtros);
        $totalPaginas = ceil($totalMiembros / $porPagina);
        
        $this->view('miembros/index', [
            'miembros' => $miembros,
            'busqueda' => $busqueda,
            'estado' => $estado,
            'pagina' => $pagina,
            'totalPaginas' => $totalPaginas,
            'title' => 'Listado de Miembros'
        ]);
    }
    
    public function crear() {
        // Verificar permisos
        if (!$this->tienePermiso('crear_miembro')) {
            $this->redirect('acceso-denegado');
            return;
        }
        
        $this->view('miembros/crear', [
            'title' => 'Registro de Nuevo Miembro'
        ]);
    }
    
    public function guardar() {
        // Verificar que sea POST y tenga permisos
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !$this->tienePermiso('crear_miembro')) {
            $this->redirect('acceso-denegado');
            return;
        }
        
        $datos = [
            'nombres' => $_POST['nombres'] ?? '',
            'apellidos' => $_POST['apellidos'] ?? '',
            'fecha_nacimiento' => $_POST['fecha_nacimiento'] ?? null,
            'genero' => $_POST['genero'] ?? '',
            'estado_civil' => $_POST['estado_civil'] ?? '',
            'profesion' => $_POST['profesion'] ?? '',
            'estado_espiritual' => $_POST['estado_espiritual'] ?? 'Visitante',
            // Otros campos
        ];
        
        // Validar datos
        $errores = $this->validarDatosMiembro($datos);
        
        if (!empty($errores)) {
            $_SESSION['errores'] = $errores;
            $_SESSION['datos_form'] = $datos;
            $this->redirect('miembros/crear');
            return;
        }
        
        // Procesar imagen si se subió
        if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
            $foto = $this->procesarImagen($_FILES['foto']);
            if ($foto) {
                $datos['foto'] = $foto;
            }
        }
        
        // Crear miembro
        $miembroId = $this->miembroModel->create($datos);
        
        if (!$miembroId) {
            $_SESSION['flash_message'] = 'Error al crear el miembro';
            $_SESSION['flash_type'] = 'danger';
            $this->redirect('miembros/crear');
            return;
        }
        
        // Crear contacto relacionado
        $datosContacto = [
            'miembro_id' => $miembroId,
            'telefono' => $_POST['telefono'] ?? '',
            'celular' => $_POST['celular'] ?? '',
            'email' => $_POST['email'] ?? '',
            'direccion' => $_POST['direccion'] ?? '',
            // Otros datos de contacto
        ];
        
        $this->contactoModel->create($datosContacto);
        
        $_SESSION['flash_message'] = 'Miembro registrado correctamente';
        $_SESSION['flash_type'] = 'success';
        $this->redirect('miembros');
    }
    
    // Implementar los métodos restantes: ver, editar, actualizar, eliminar, etc.
    
    private function validarDatosMiembro($datos) {
        $errores = [];
        
        if (empty($datos['nombres'])) {
            $errores['nombres'] = 'El nombre es obligatorio';
        }
        
        if (empty($datos['apellidos'])) {
            $errores['apellidos'] = 'Los apellidos son obligatorios';
        }
        
        return $errores;
    }
    
    private function procesarImagen($archivo) {
        // Implementar procesamiento de imagen
        // Validar tipo, tamaño, etc.
        // Guardar y retornar path
        return 'default.jpg'; // Placeholder
    }
    
    private function tienePermiso($permiso) {
        // Implementar verificación de permisos
        return true; // Placeholder
    }
}