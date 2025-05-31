<?php

namespace App\Controllers;

use App\Models\Miembro;
use App\Models\Contacto;
use App\Models\EstudiosTrabajo;
use App\Models\Tallas;
use App\Models\CarreraBiblica;
use App\Models\SaludEmergencias;
use Exception;

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
    public function ver($id)
    {
        try {
            // Corregir la forma en que se maneja el parámetro ID
            if (is_array($id)) {
                // Si recibe un array asociativo con clave 'id'
                if (isset($id['id'])) {
                    $id = (int)$id['id'];
                } 
                // Si recibe un array indexado
                elseif (isset($id[0])) {
                    $id = (int)$id[0];
                } 
                else {
                    $id = 0;
                }
            }
            
            $id = (int)$id;
            
            if ($id <= 0) {
                $this->renderError(404, 'ID de miembro inválido');
                return;
            }
            
            // Obtener perfil completo
            $miembro = $this->miembroModel->getFullProfile($id);
            
            if (!$miembro) {
                $this->renderError(404, 'Miembro no encontrado');
                return;
            }
            
            // IMPORTANTE: Usar renderWithLayout en lugar de render
            $this->renderWithLayout('miembros/ver', 'default', [
                'miembro' => $miembro,
                'title' => "{$miembro['nombres']} {$miembro['apellidos']}"
            ]);
        } catch (\Exception $e) {
            error_log("Error en ver(): " . $e->getMessage());
            $this->renderError(500, 'Error al cargar los datos del miembro');
        }
    }

    private function obtenerDatosRelacionados($db, $miembroId, $tabla)
    {
        $stmt = $db->prepare("SELECT * FROM {$tabla} WHERE miembro_id = ?");
        $stmt->execute([$miembroId]);
        $datos = $stmt->fetch(\PDO::FETCH_ASSOC);
        
        if ($datos) {
            // Eliminar campos redundantes
            unset($datos['id']);
            unset($datos['miembro_id']);
            unset($datos['fecha_actualizacion']);
            return $datos;
        }
        
        return [];
    }

    /**
     * Muestra el formulario para editar un miembro
     */
    public function editar($id) 
    {
        $miembro = $this->miembroModel->getFullProfile($id);
        
        if (!$miembro) {
            $_SESSION['flash_message'] = 'Miembro no encontrado';
            $_SESSION['flash_type'] = 'danger';
            $this->redirect('miembros');
            return;
        }
        
        return $this->renderWithLayout('miembros/editar', 'default', [
            'miembro' => $miembro,
            'title' => 'Editar Miembro: ' . $miembro['nombres'] . ' ' . $miembro['apellidos']
        ]);
    }

    /**
     * Procesa el formulario de edición y actualiza el miembro
     */
    public function actualizar($id)
    {
        // Limpiar el id
        if (is_array($id)) {
            $id = (int) $id[0];
        }
        $id = (int)$id;
        
        // Para depuración
        error_log("Iniciando actualización para miembro ID: $id");
        error_log("Datos POST: " . json_encode($_POST));
        
        try {
            // Verificar método HTTP
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                $this->responderJSON(['success' => false, 'message' => 'Método no permitido']);
                return;
            }
            
            // Obtener conexión a la BD
            $db = \Database::getInstance()->getConnection();
            
            // Verificar que el miembro existe
            $verificarStmt = $db->prepare("SELECT id FROM InformacionGeneral WHERE id = ?");
            $verificarStmt->execute([$id]);
            
            if ($verificarStmt->rowCount() === 0) {
                $this->responderJSON(['success' => false, 'message' => 'El miembro no existe']);
                return;
            }
            
            // Datos básicos para actualizar
            $datosGenerales = [];
            $camposPermitidos = [
                'nombres', 'apellidos', 'celular', 'localidad', 'barrio', 
                'fecha_nacimiento', 'estado_espiritual', 'recorrido_espiritual'
            ];
            
            foreach ($camposPermitidos as $campo) {
                if (isset($_POST[$campo])) {
                    $datosGenerales[$campo] = $_POST[$campo];
                }
            }
            
            // Actualizar tabla principal si hay datos
            if (!empty($datosGenerales)) {
                $sets = [];
                $params = [];
                
                foreach ($datosGenerales as $campo => $valor) {
                    $sets[] = "$campo = ?";
                    $params[] = $valor;
                }
                
                // Añadir ID al final
                $params[] = $id;
                
                $sql = "UPDATE InformacionGeneral SET " . implode(', ', $sets) . " WHERE id = ?";
                $stmt = $db->prepare($sql);
                $resultado = $stmt->execute($params);
                
                if (!$resultado) {
                    throw new \Exception("Error al actualizar datos generales: " . implode(", ", $stmt->errorInfo()));
                }
            }
            
            // Actualizar tablas relacionadas (sólo si tenemos datos para ellas)
            $this->actualizarContactoSimplificado($id, $_POST);
            $this->actualizarEstudioSimplificado($id, $_POST);
            $this->actualizarTallasSimplificado($id, $_POST);
            $this->actualizarSaludSimplificado($id, $_POST);
            $this->actualizarCarreraSimplificado($id, $_POST);
            
            // Responder con éxito
            $this->responderJSON([
                'success' => true, 
                'message' => 'Miembro actualizado correctamente',
                'id' => $id,
                'redirect' => APP_URL . '/miembros/' . $id
            ]);
            
        } catch (\Exception $e) {
            error_log("Error en actualizar(): " . $e->getMessage());
            $this->responderJSON(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }

    private function responderJSON($data) {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    // Métodos simplificados para actualizar tablas relacionadas
    private function actualizarContactoSimplificado($id, $datos) {
        try {
            $db = \Database::getInstance()->getConnection();
            
            // Campos a actualizar (solo si están presentes en POST)
            $campos = [
                'tipo_documento', 'numero_documento', 'telefono', 
                'correo_electronico', 'pais', 'ciudad', 'direccion', 'estado_civil'
            ];
            
            // Verificar si hay datos para actualizar
            $datosActualizar = [];
            foreach ($campos as $campo) {
                if (isset($datos[$campo])) {
                    $datosActualizar[$campo] = $datos[$campo];
                }
            }
            
            if (empty($datosActualizar)) {
                return true; // No hay datos para actualizar
            }
            
            // Verificar si existe registro
            $verificar = $db->prepare("SELECT id FROM Contacto WHERE miembro_id = ?");
            $verificar->execute([$id]);
            $existe = $verificar->fetch();
            
            if ($existe) {
                // Actualizar
                $sets = [];
                $params = [];
                
                foreach ($datosActualizar as $campo => $valor) {
                    $sets[] = "$campo = ?";
                    $params[] = $valor;
                }
                
                $params[] = $id;
                $sql = "UPDATE Contacto SET " . implode(', ', $sets) . " WHERE miembro_id = ?";
                
                $stmt = $db->prepare($sql);
                $stmt->execute($params);
            } else {
                // Insertar
                $datosActualizar['miembro_id'] = $id;
                
                $campos = array_keys($datosActualizar);
                $placeholders = array_fill(0, count($campos), '?');
                
                $sql = "INSERT INTO Contacto (" . implode(', ', $campos) . ") 
                        VALUES (" . implode(', ', $placeholders) . ")";
                
                $stmt = $db->prepare($sql);
                $stmt->execute(array_values($datosActualizar));
            }
            
            return true;
        } catch (\Exception $e) {
            error_log("Error al actualizar contacto: " . $e->getMessage());
            return false; // No lanzamos la excepción para que no interrumpa el flujo
        }
    }

    private function actualizarEstudioSimplificado($id, $datos) {
        try {
            $db = \Database::getInstance()->getConnection();
            
            // Campos a actualizar
            $campos = [
                'nivel_estudios', 'profesion', 'otros_estudios', 
                'empresa', 'direccion_empresa', 'emprendimientos'
            ];
            
            // Verificar si hay datos para actualizar
            $datosActualizar = [];
            foreach ($campos as $campo) {
                if (isset($datos[$campo])) {
                    $datosActualizar[$campo] = $datos[$campo];
                }
            }
            
            if (empty($datosActualizar)) {
                return true; // No hay datos para actualizar
            }
            
            // Verificar si existe registro
            $verificar = $db->prepare("SELECT id FROM EstudiosTrabajo WHERE miembro_id = ?");
            $verificar->execute([$id]);
            $existe = $verificar->fetch();
            
            if ($existe) {
                // Actualizar
                $sets = [];
                $params = [];
                
                foreach ($datosActualizar as $campo => $valor) {
                    $sets[] = "$campo = ?";
                    $params[] = $valor;
                }
                
                $params[] = $id;
                $sql = "UPDATE EstudiosTrabajo SET " . implode(', ', $sets) . " WHERE miembro_id = ?";
                
                $stmt = $db->prepare($sql);
                $stmt->execute($params);
            } else {
                // Insertar
                $datosActualizar['miembro_id'] = $id;
                
                $campos = array_keys($datosActualizar);
                $placeholders = array_fill(0, count($campos), '?');
                
                $sql = "INSERT INTO EstudiosTrabajo (" . implode(', ', $campos) . ") 
                        VALUES (" . implode(', ', $placeholders) . ")";
                
                $stmt = $db->prepare($sql);
                $stmt->execute(array_values($datosActualizar));
            }
            
            return true;
        } catch (\Exception $e) {
            error_log("Error al actualizar estudios: " . $e->getMessage());
            return false;
        }
    }

    private function actualizarTallasSimplificado($id, $datos) {
        try {
            $db = \Database::getInstance()->getConnection();
            
            // Campos a actualizar
            $campos = [
                'talla_camisa', 'talla_camiseta', 'talla_pantalon', 'talla_zapatos'
            ];
            
            // Verificar si hay datos para actualizar
            $datosActualizar = [];
            foreach ($campos as $campo) {
                if (isset($datos[$campo])) {
                    $datosActualizar[$campo] = $datos[$campo];
                }
            }
            
            if (empty($datosActualizar)) {
                return true; // No hay datos para actualizar
            }
            
            // Verificar si existe registro
            $verificar = $db->prepare("SELECT id FROM Tallas WHERE miembro_id = ?");
            $verificar->execute([$id]);
            $existe = $verificar->fetch();
            
            if ($existe) {
                // Actualizar
                $sets = [];
                $params = [];
                
                foreach ($datosActualizar as $campo => $valor) {
                    $sets[] = "$campo = ?";
                    $params[] = $valor;
                }
                
                $params[] = $id;
                $sql = "UPDATE Tallas SET " . implode(', ', $sets) . " WHERE miembro_id = ?";
                
                $stmt = $db->prepare($sql);
                $stmt->execute($params);
            } else {
                // Insertar
                $datosActualizar['miembro_id'] = $id;
                
                $campos = array_keys($datosActualizar);
                $placeholders = array_fill(0, count($campos), '?');
                
                $sql = "INSERT INTO Tallas (" . implode(', ', $campos) . ") 
                        VALUES (" . implode(', ', $placeholders) . ")";
                
                $stmt = $db->prepare($sql);
                $stmt->execute(array_values($datosActualizar));
            }
            
            return true;
        } catch (\Exception $e) {
            error_log("Error al actualizar tallas: " . $e->getMessage());
            return false;
        }
    }

    private function actualizarSaludSimplificado($id, $datos) {
        try {
            $db = \Database::getInstance()->getConnection();
            
            // Campos a actualizar
            $campos = [
                'rh', 'eps', 'acudiente1', 'telefono1', 'acudiente2', 'telefono2'
            ];
            
            // Verificar si hay datos para actualizar
            $datosActualizar = [];
            foreach ($campos as $campo) {
                if (isset($datos[$campo])) {
                    $datosActualizar[$campo] = $datos[$campo];
                }
            }
            
            if (empty($datosActualizar)) {
                return true; // No hay datos para actualizar
            }
            
            // Verificar si existe registro
            $verificar = $db->prepare("SELECT id FROM SaludEmergencias WHERE miembro_id = ?");
            $verificar->execute([$id]);
            $existe = $verificar->fetch();
            
            if ($existe) {
                // Actualizar
                $sets = [];
                $params = [];
                
                foreach ($datosActualizar as $campo => $valor) {
                    $sets[] = "$campo = ?";
                    $params[] = $valor;
                }
                
                $params[] = $id;
                $sql = "UPDATE SaludEmergencias SET " . implode(', ', $sets) . " WHERE miembro_id = ?";
                
                $stmt = $db->prepare($sql);
                $stmt->execute($params);
            } else {
                // Insertar
                $datosActualizar['miembro_id'] = $id;
                
                $campos = array_keys($datosActualizar);
                $placeholders = array_fill(0, count($campos), '?');
                
                $sql = "INSERT INTO SaludEmergencias (" . implode(', ', $campos) . ") 
                        VALUES (" . implode(', ', $placeholders) . ")";
                
                $stmt = $db->prepare($sql);
                $stmt->execute(array_values($datosActualizar));
            }
            
            return true;
        } catch (\Exception $e) {
            error_log("Error al actualizar salud: " . $e->getMessage());
            return false;
        }
    }

    private function actualizarCarreraSimplificado($id, $datos) {
        try {
            $db = \Database::getInstance()->getConnection();
            
            // Campos a actualizar
            $campos = [
                'estado', 'carrera_biblica', 'miembro_de', 
                'casa_de_palabra_y_vida', 'cobertura', 'anotaciones'
            ];
            
            // Verificar si hay datos para actualizar
            $datosActualizar = [];
            foreach ($campos as $campo) {
                if (isset($datos[$campo])) {
                    $datosActualizar[$campo] = $datos[$campo];
                }
            }
            
            if (empty($datosActualizar)) {
                return true; // No hay datos para actualizar
            }
            
            // Verificar si existe registro
            $verificar = $db->prepare("SELECT id FROM CarreraBiblica WHERE miembro_id = ?");
            $verificar->execute([$id]);
            $existe = $verificar->fetch();
            
            if ($existe) {
                // Actualizar
                $sets = [];
                $params = [];
                
                foreach ($datosActualizar as $campo => $valor) {
                    $sets[] = "$campo = ?";
                    $params[] = $valor;
                }
                
                $params[] = $id;
                $sql = "UPDATE CarreraBiblica SET " . implode(', ', $sets) . " WHERE miembro_id = ?";
                
                $stmt = $db->prepare($sql);
                $stmt->execute($params);
            } else {
                // Insertar
                $datosActualizar['miembro_id'] = $id;
                
                $campos = array_keys($datosActualizar);
                $placeholders = array_fill(0, count($campos), '?');
                
                $sql = "INSERT INTO CarreraBiblica (" . implode(', ', $campos) . ") 
                        VALUES (" . implode(', ', $placeholders) . ")";
                
                $stmt = $db->prepare($sql);
                $stmt->execute(array_values($datosActualizar));
            }
            
            return true;
        } catch (\Exception $e) {
            error_log("Error al actualizar carrera: " . $e->getMessage());
            return false;
        }
    }
}