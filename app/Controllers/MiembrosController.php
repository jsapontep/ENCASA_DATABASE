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
    private $db;
    
    public function __construct() {
        parent::__construct();
        $this->miembroModel = new Miembro();
        $this->db = \Database::getInstance()->getConnection();
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
        // Asegurarse de que el ID sea un entero
        if (is_array($id)) {
            $id = $id[0]; // En caso de que el router pase un array
        }
        $id = (int)$id;
        
        error_log("Controlador: Buscando miembro con ID: $id");
        
        // Obtener datos del miembro desde el modelo
        $miembro = $this->miembroModel->getFullProfile($id);
        
        if (!$miembro) {
            error_log("Controlador: No se encontró miembro con ID: $id");
            $_SESSION['flash_message'] = 'Miembro no encontrado';
            $_SESSION['flash_type'] = 'danger';
            
            // Redirigir a la lista de miembros
            header("Location: " . url('miembros'));
            exit;
        }
        
        error_log("Controlador: Miembro encontrado, renderizando vista");
        
        // Renderizar la vista con los datos del miembro
        return $this->renderWithLayout('miembros/ver', 'default', [
            'title' => $miembro['nombres'] . ' ' . $miembro['apellidos'],
            'miembro' => $miembro
        ]);
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
        // Asegurar que el ID sea un entero (igual que en el método ver)
        if (is_array($id)) {
            $id = $id[0]; // En caso de que el router pase un array
        }
        $id = (int)$id;
        
        // Registrar para diagnóstico
        error_log("Controlador: Buscando miembro para editar con ID: $id");
        
        $miembro = $this->miembroModel->getFullProfile($id);
        
        if (!$miembro) {
            error_log("Controlador: No se encontró miembro con ID: $id para editar");
            $_SESSION['flash_message'] = 'Miembro no encontrado';
            $_SESSION['flash_type'] = 'danger';
            $this->redirect('miembros');
            return;
        }
        
        error_log("Controlador: Miembro encontrado, mostrando formulario de edición");
        
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
        if (is_array($id)) {
            $id = $id[0];
        }
        $id = (int)$id;
        
        // Verificar que el miembro existe
        $miembro = $this->miembroModel->getFullProfile($id);
        if (!$miembro) {
            $this->respondJson([
                'success' => false,
                'message' => 'Miembro no encontrado'
            ]);
            return;
        }
        
        // Iniciar procesamiento
        try {
            // Activar log para debugging
            $resultados = ['general' => false, 'contacto' => false, 'estudios' => false, 
                          'tallas' => false, 'salud' => false, 'carrera' => false];
            
            // 1. Procesar datos básicos
            $datosGenerales = $this->obtenerDatosPost([
                'nombres', 'apellidos', 'celular', 'localidad', 'barrio',
                'fecha_nacimiento', 'invitado_por', 'conector',
                'recorrido_espiritual', 'estado_espiritual'
            ]);
            
            // Procesar checkbox habeas_data
            $datosGenerales['habeas_data'] = isset($_POST['habeas_data']) ? '1' : null;
            
            // 2. Procesar la foto si se ha subido una nueva
            if (!empty($_FILES['foto']['name'])) {
                // Usar directamente el path absoluto para garantizar que funcione
                $uploadDir = __DIR__ . '/../../uploads/miembros/';
                
                // Asegurar que el directorio existe
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }
                
                // Generar nombre único para el archivo
                $extension = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
                $nombreArchivo = uniqid() . '.' . $extension;
                $uploadFile = $uploadDir . $nombreArchivo;
                
                // Mover el archivo cargado al directorio de destino
                if (move_uploaded_file($_FILES['foto']['tmp_name'], $uploadFile)) {
                    $datosGenerales['foto'] = $nombreArchivo;
                }
            }
            
            // 3. Actualizar datos generales
            $resultados['general'] = $this->miembroModel->actualizar($id, $datosGenerales);
            
            // 4. Actualizar tablas relacionadas
            $resultados['contacto'] = $this->actualizarContacto($id);
            $resultados['estudios'] = $this->actualizarEstudiosTrabajo($id);
            $resultados['tallas'] = $this->actualizarTallas($id);
            $resultados['salud'] = $this->actualizarSaludEmergencias($id);
            $resultados['carrera'] = $this->actualizarCarreraBiblica($id);
            
            // Registrar resultados para debugging
            error_log("Resultados de actualización para miembro ID $id: " . json_encode($resultados));
            
            // Responder con éxito
            $this->respondJson([
                'success' => true,
                'message' => 'Miembro actualizado correctamente',
                'redirect' => url("miembros/$id"),
                'id' => $id,
                'debug' => APP_ENV === 'development' ? $resultados : null
            ]);
            
        } catch (\Exception $e) {
            // Registrar error
            error_log("Error al actualizar miembro ID $id: " . $e->getMessage());
            
            // Responder con error
            $this->respondJson([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Actualiza los datos de contacto del miembro
     */
    private function actualizarContacto($id)
    {
        try {
            if (!isset($_POST['contacto']) || !is_array($_POST['contacto'])) {
                error_log("No hay datos de contacto para actualizar, miembro ID: $id");
                return false;
            }

            $contacto = $_POST['contacto'];
            error_log("Datos de contacto recibidos: " . json_encode($contacto));
            
            // Verificar existencia del registro
            $stmt = $this->db->prepare("SELECT id FROM Contacto WHERE miembro_id = ?");
            $stmt->execute([$id]);
            $existente = $stmt->fetch();
            
            if ($existente) {
                // Actualizar registro existente
                $campos = [];
                $valores = [];
                
                foreach ($contacto as $campo => $valor) {
                    if ($campo !== 'miembro_id') {
                        $campos[] = "$campo = ?";
                        $valores[] = $valor;
                    }
                }
                
                if (empty($campos)) {
                    return false; // No hay campos para actualizar
                }
                
                $valores[] = $id; // Para la condición WHERE
                
                $sql = "UPDATE Contacto SET " . implode(', ', $campos) . " WHERE miembro_id = ?";
                error_log("SQL actualizar contacto: $sql");
                $stmt = $this->db->prepare($sql);
                $resultado = $stmt->execute($valores);
                error_log("Resultado actualización contacto: " . ($resultado ? "éxito" : "fallido"));
                return $resultado;
            } else {
                // Insertar nuevo registro
                $contacto['miembro_id'] = $id;
                $campos = array_keys($contacto);
                $placeholders = array_fill(0, count($campos), '?');
                
                $sql = "INSERT INTO Contacto (" . implode(', ', $campos) . ") VALUES (" . implode(', ', $placeholders) . ")";
                error_log("SQL insertar contacto: $sql");
                $stmt = $this->db->prepare($sql);
                $resultado = $stmt->execute(array_values($contacto));
                error_log("Resultado inserción contacto: " . ($resultado ? "éxito" : "fallido"));
                return $resultado;
            }
        } catch (\Exception $e) {
            error_log("Error en actualizarContacto() para miembro ID $id: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Actualiza los datos de estudios y trabajo del miembro
     */
    private function actualizarEstudiosTrabajo($id)
    {
        try {
            if (!isset($_POST['estudios']) || !is_array($_POST['estudios'])) {
                return false;
            }

            $estudios = $_POST['estudios'];
            error_log("Datos de estudios recibidos: " . json_encode($estudios));
            
            // Verificar existencia del registro
            $stmt = $this->db->prepare("SELECT id FROM EstudiosTrabajo WHERE miembro_id = ?");
            $stmt->execute([$id]);
            $existente = $stmt->fetch();
            
            if ($existente) {
                // Actualizar registro existente
                $campos = [];
                $valores = [];
                
                foreach ($estudios as $campo => $valor) {
                    if ($campo !== 'miembro_id') {
                        $campos[] = "$campo = ?";
                        $valores[] = $valor;
                    }
                }
                
                if (empty($campos)) {
                    return false; // No hay campos para actualizar
                }
                
                $valores[] = $id; // Para la condición WHERE
                
                $sql = "UPDATE EstudiosTrabajo SET " . implode(', ', $campos) . " WHERE miembro_id = ?";
                $stmt = $this->db->prepare($sql);
                return $stmt->execute($valores);
            } else {
                // Insertar nuevo registro
                $estudios['miembro_id'] = $id;
                $campos = array_keys($estudios);
                $placeholders = array_fill(0, count($campos), '?');
                
                $sql = "INSERT INTO EstudiosTrabajo (" . implode(', ', $campos) . ") VALUES (" . implode(', ', $placeholders) . ")";
                $stmt = $this->db->prepare($sql);
                return $stmt->execute(array_values($estudios));
            }
        } catch (\Exception $e) {
            error_log("Error en actualizarEstudiosTrabajo() para miembro ID $id: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Actualiza las tallas del miembro
     */
    private function actualizarTallas($id)
    {
        try {
            if (!isset($_POST['tallas']) || !is_array($_POST['tallas'])) {
                return false;
            }

            $tallas = $_POST['tallas'];
            error_log("Datos de tallas recibidos: " . json_encode($tallas));
            
            // Verificar existencia del registro
            $stmt = $this->db->prepare("SELECT id FROM Tallas WHERE miembro_id = ?");
            $stmt->execute([$id]);
            $existente = $stmt->fetch();
            
            if ($existente) {
                // Actualizar registro existente
                $campos = [];
                $valores = [];
                
                foreach ($tallas as $campo => $valor) {
                    if ($campo !== 'miembro_id') {
                        $campos[] = "$campo = ?";
                        $valores[] = $valor;
                    }
                }
                
                if (empty($campos)) {
                    return false; // No hay campos para actualizar
                }
                
                $valores[] = $id; // Para la condición WHERE
                
                $sql = "UPDATE Tallas SET " . implode(', ', $campos) . " WHERE miembro_id = ?";
                $stmt = $this->db->prepare($sql);
                return $stmt->execute($valores);
            } else {
                // Insertar nuevo registro
                $tallas['miembro_id'] = $id;
                $campos = array_keys($tallas);
                $placeholders = array_fill(0, count($campos), '?');
                
                $sql = "INSERT INTO Tallas (" . implode(', ', $campos) . ") VALUES (" . implode(', ', $placeholders) . ")";
                $stmt = $this->db->prepare($sql);
                return $stmt->execute(array_values($tallas));
            }
        } catch (\Exception $e) {
            error_log("Error en actualizarTallas() para miembro ID $id: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Actualiza los datos de salud y emergencias del miembro
     */
    private function actualizarSaludEmergencias($id)
    {
        try {
            if (!isset($_POST['salud']) || !is_array($_POST['salud'])) {
                return false;
            }

            $salud = $_POST['salud'];
            error_log("Datos de salud recibidos: " . json_encode($salud));
            
            // Verificar existencia del registro
            $stmt = $this->db->prepare("SELECT id FROM SaludEmergencias WHERE miembro_id = ?");
            $stmt->execute([$id]);
            $existente = $stmt->fetch();
            
            if ($existente) {
                // Actualizar registro existente
                $campos = [];
                $valores = [];
                
                foreach ($salud as $campo => $valor) {
                    if ($campo !== 'miembro_id') {
                        $campos[] = "$campo = ?";
                        $valores[] = $valor;
                    }
                }
                
                if (empty($campos)) {
                    return false; // No hay campos para actualizar
                }
                
                $valores[] = $id; // Para la condición WHERE
                
                $sql = "UPDATE SaludEmergencias SET " . implode(', ', $campos) . " WHERE miembro_id = ?";
                $stmt = $this->db->prepare($sql);
                return $stmt->execute($valores);
            } else {
                // Insertar nuevo registro
                $salud['miembro_id'] = $id;
                $campos = array_keys($salud);
                $placeholders = array_fill(0, count($campos), '?');
                
                $sql = "INSERT INTO SaludEmergencias (" . implode(', ', $campos) . ") VALUES (" . implode(', ', $placeholders) . ")";
                $stmt = $this->db->prepare($sql);
                return $stmt->execute(array_values($salud));
            }
        } catch (\Exception $e) {
            error_log("Error en actualizarSaludEmergencias() para miembro ID $id: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Actualiza los datos de carrera bíblica del miembro
     */
    private function actualizarCarreraBiblica($id)
    {
        try {
            if (!isset($_POST['carrera']) || !is_array($_POST['carrera'])) {
                return false;
            }

            $carrera = $_POST['carrera'];
            error_log("Datos de carrera recibidos: " . json_encode($carrera));
            
            // Verificar existencia del registro
            $stmt = $this->db->prepare("SELECT id FROM CarreraBiblica WHERE miembro_id = ?");
            $stmt->execute([$id]);
            $existente = $stmt->fetch();
            
            if ($existente) {
                // Actualizar registro existente
                $campos = [];
                $valores = [];
                
                foreach ($carrera as $campo => $valor) {
                    if ($campo !== 'miembro_id') {
                        $campos[] = "$campo = ?";
                        $valores[] = $valor;
                    }
                }
                
                if (empty($campos)) {
                    return false; // No hay campos para actualizar
                }
                
                $valores[] = $id; // Para la condición WHERE
                
                $sql = "UPDATE CarreraBiblica SET " . implode(', ', $campos) . " WHERE miembro_id = ?";
                $stmt = $this->db->prepare($sql);
                return $stmt->execute($valores);
            } else {
                // Insertar nuevo registro
                $carrera['miembro_id'] = $id;
                $campos = array_keys($carrera);
                $placeholders = array_fill(0, count($campos), '?');
                
                $sql = "INSERT INTO CarreraBiblica (" . implode(', ', $campos) . ") VALUES (" . implode(', ', $placeholders) . ")";
                $stmt = $this->db->prepare($sql);
                return $stmt->execute(array_values($carrera));
            }
        } catch (\Exception $e) {
            error_log("Error en actualizarCarreraBiblica() para miembro ID $id: " . $e->getMessage());
            return false;
        }
    }

    // Método auxiliar para responder en formato JSON
    private function respondJson($data)
    {
        header('Content-Type: application/json');
        echo json_encode($data);
    }

    // Método auxiliar para obtener campos del POST
    private function obtenerDatosPost($campos)
    {
        $datos = [];
        foreach ($campos as $campo) {
            if (isset($_POST[$campo])) {
                $datos[$campo] = $_POST[$campo];
            }
        }
        return $datos;
    }
}