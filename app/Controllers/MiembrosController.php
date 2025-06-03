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
    private $contactoModel;
    private $estudiosTrabajoModel;
    private $tallasModel;
    private $saludEmergenciasModel;
    private $carreraBiblicaModel;
    private $db;
    
    public function __construct() {
        parent::__construct();
        $this->miembroModel = new Miembro();
        $this->contactoModel = new Contacto();
        $this->estudiosTrabajoModel = new EstudiosTrabajo();
        $this->tallasModel = new Tallas();
        $this->saludEmergenciasModel = new SaludEmergencias();
        $this->carreraBiblicaModel = new CarreraBiblica();
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
        
        // Guardar información de salud y emergencias
        if (isset($_POST['salud']) && is_array($_POST['salud'])) {
            $salud = $_POST['salud'];
            $salud['miembro_id'] = $miembroId;
            $this->miembroModel->guardarSaludEmergencias($salud);
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
        // Añadir depuración
        error_log("Procesando foto: " . print_r($archivo, true));
        
        // Verificar si hubo errores en la carga
        if ($archivo['error'] !== UPLOAD_ERR_OK) {
            error_log("Error de carga: " . $this->getFileUploadErrorMessage($archivo['error']));
            return false;
        }
        
        // Modificar la ruta para que sea coherente con donde se busca después
        $directorio = '../public/uploads/miembros/';
        
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
    
    // El método ver() se ha movido al final del archivo para incluir funcionalidad mejorada

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
    public function editar($id = null)
    {
        error_log("Iniciando edición para ID: " . print_r($id, true));
        
        try {
            // Si es un array, tomar el primer elemento
            if (is_array($id)) {
                $id = isset($id[0]) ? $id[0] : null;
            }
            
            // Verificar que tenemos un ID válido
            if (!$id || !is_numeric($id)) {
                $_SESSION['flash_message'] = "ID de miembro no válido";
                $_SESSION['flash_type'] = "danger";
                $this->redirect('miembros');
                return;
            }
            
            $id = (int)$id;
            
            // Antes de buscar, verificar si el ID existe
            $existe = $this->miembroModel->existeId($id);
            error_log("Verificación de existencia para ID $id: " . ($existe ? 'Existe' : 'No existe'));
            
            if (!$existe) {
                $_SESSION['flash_message'] = "El miembro con ID $id no existe. Por favor utilice uno de los miembros disponibles.";
                $_SESSION['flash_type'] = "danger";
                
                // Redirigir a la lista de miembros
                $this->redirect('miembros');
                return;
            }

            // Si existe, continuar con el proceso normal
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
        } catch (\Exception $e) {
            error_log("Error en editar: " . $e->getMessage());
            $_SESSION['flash_message'] = "Error al cargar el formulario de edición: " . $e->getMessage();
            $_SESSION['flash_type'] = "danger";
            $this->redirect('miembros');
        }
    }

    /**
     * Procesa el formulario de edición y actualiza el miembro
     */
    public function actualizar($id)
    {
        try {
            // Limpiar y verificar ID
            $id = (int)$id;
            
            // Registrar datos recibidos para depuración avanzada
            error_log("POST completo recibido: " . print_r($_POST, true));
            
            // Verificar la estructura de los datos para cada tabla
            if (isset($_POST['estudios'])) {
                error_log("Datos de estudios: " . print_r($_POST['estudios'], true));
            } else {
                error_log("No se recibieron datos de estudios");
            }
            
            if (isset($_POST['tallas'])) {
                error_log("Datos de tallas: " . print_r($_POST['tallas'], true));
            } else {
                error_log("No se recibieron datos de tallas");
            }
            
            // Verificar método de solicitud
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new \Exception("Método no permitido");
            }
            
            // Verificar que el miembro existe
            $miembro = $this->miembroModel->getFullProfile($id);
            
            if (!$miembro) {
                throw new \Exception("El miembro no existe");
            }
            
            // Preparar los datos para actualizar la tabla principal
            $datosGenerales = [
                'nombres' => isset($_POST['nombres']) ? trim($_POST['nombres']) : $miembro['nombres'],
                'apellidos' => isset($_POST['apellidos']) ? trim($_POST['apellidos']) : $miembro['apellidos'],
                'celular' => isset($_POST['celular']) ? trim($_POST['celular']) : $miembro['celular'],
                'localidad' => isset($_POST['localidad']) ? trim($_POST['localidad']) : $miembro['localidad'],
                'barrio' => isset($_POST['barrio']) ? trim($_POST['barrio']) : $miembro['barrio'],
                'fecha_nacimiento' => isset($_POST['fecha_nacimiento']) ? trim($_POST['fecha_nacimiento']) : $miembro['fecha_nacimiento'],
                'conector' => isset($_POST['conector']) ? trim($_POST['conector']) : $miembro['conector'],
                'estado_espiritual' => isset($_POST['estado_espiritual']) ? trim($_POST['estado_espiritual']) : $miembro['estado_espiritual'],
                'fecha_ingreso_iglesia' => isset($_POST['fecha_ingreso_iglesia']) ? trim($_POST['fecha_ingreso_iglesia']) : $miembro['fecha_ingreso_iglesia'],
                'estado_miembro' => isset($_POST['estado_miembro']) ? trim($_POST['estado_miembro']) : $miembro['estado_miembro'],
            ];
            
            error_log("Datos preparados para actualizar: " . print_r($datosGenerales, true));
            
            // Ejecutar directamente la consulta SQL para mayor control
            $db = \Database::getInstance()->getConnection();
            
            // Construir la consulta SQL dinámicamente
            $setClausulas = [];
            $valores = [];
            
            foreach ($datosGenerales as $campo => $valor) {
                $setClausulas[] = "$campo = ?";
                $valores[] = $valor;
            }
            
            // Añadir ID al final de los valores
            $valores[] = $id;
            
            $sql = "UPDATE informaciongeneral SET " . implode(', ', $setClausulas) . " WHERE id = ?";
            error_log("SQL a ejecutar: " . $sql);
            error_log("Valores: " . implode(', ', $valores));
            
            $stmt = $db->prepare($sql);
            $resultadoGeneral = $stmt->execute($valores);
            error_log("Resultado de actualización: " . ($resultadoGeneral ? "Éxito" : "Fallido") . " - Filas afectadas: " . $stmt->rowCount());
            
            if (!$resultadoGeneral) {
                throw new \Exception("Error al actualizar la información general del miembro");
            }
            
            // AÑADIR ESTE BLOQUE - Procesar la foto si se subió una nueva
            if (!empty($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
                $foto = $this->procesarFoto($_FILES['foto']);
                if ($foto) {
                    $datosGenerales['foto'] = $foto;
                    
                    // Si había una foto anterior, eliminarla
                    if (!empty($miembro['foto'])) {
                        $rutaAnterior = '../public/uploads/miembros/' . $miembro['foto'];
                        if (file_exists($rutaAnterior)) {
                            unlink($rutaAnterior);
                        }
                    }
                } else {
                    error_log("Error procesando la foto subida");
                }
            }
            
            // Comprobar si se debe eliminar la foto actual
            if (isset($_POST['eliminar_foto']) && $_POST['eliminar_foto'] == '1' && !empty($miembro['foto'])) {
                $rutaAnterior = '../public/uploads/miembros/' . $miembro['foto'];
                if (file_exists($rutaAnterior)) {
                    unlink($rutaAnterior);
                }
                $datosGenerales['foto'] = null; // Establecer foto como nula en la base de datos
            }
            
            // Actualizar tablas relacionadas
            $this->actualizarTablasRelacionadas($id, $_POST);
            
            // Respuesta según tipo de petición
            if ($this->esAjax()) {
                $this->responderJson([
                    'success' => true,
                    'message' => 'Miembro actualizado correctamente',
                    'redirect' => url('miembros/'.$id)
                ]);
                return; // No continuar ejecución
            } else {
                // Para formularios tradicionales
                $_SESSION['flash_message'] = "Miembro actualizado correctamente";
                $_SESSION['flash_type'] = "success";
                $this->redirect('miembros/'.$id);
            }
            
        } catch (\Exception $e) {
            error_log("Error en actualización: " . $e->getMessage());
            
            if ($this->esAjax()) {
                $this->responderJson([
                    'success' => false,
                    'message' => 'Error: ' . $e->getMessage()
                ]);
                return; // No continuar ejecución
            } else {
                $_SESSION['flash_message'] = "Error: " . $e->getMessage();
                $_SESSION['flash_type'] = "danger";
                $this->redirect('miembros/editar/'.$id);
            }
        }
    }

    // Método auxiliar para detectar si es una petición AJAX
    private function esAjax() {
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            return true;
        }
        
        if (isset($_SERVER['HTTP_ACCEPT']) && 
            strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false) {
            return true;
        }
        
        // Comprobar si el cliente está esperando JSON explícitamente
        if (isset($_SERVER['CONTENT_TYPE']) && 
            strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false) {
            return true;
        }
        
        return false;
    }

    // Método para actualizar tablas relacionadas
    private function actualizarTablasRelacionadas($id, $datos) {
        try {
            $id = (int)$id;
            $db = \Database::getInstance()->getConnection();
            $tablasActualizadas = [];
            
            // Mapeo de nombres de campos
            $mapeoNombres = [
                'estudios' => [
                    'nivel_educativo' => 'nivel_estudios',
                    // Otros mapeos necesarios
                ],
                'salud' => [
                    'grupo_sanguineo' => 'tipo_sangre',
                    // Otros mapeos necesarios
                ]
            ];
            
            // 1. Actualizar tabla contacto (sin cambios)
            
            // 2. Actualizar tabla estudios y trabajo
            if (isset($datos['estudios']) && is_array($datos['estudios'])) {
                // Convertir nombres de campos si es necesario
                $datosEstudios = $datos['estudios'];
                foreach ($mapeoNombres['estudios'] as $nombreVista => $nombreDB) {
                    if (isset($datosEstudios[$nombreVista])) {
                        $datosEstudios[$nombreDB] = $datosEstudios[$nombreVista];
                        unset($datosEstudios[$nombreVista]);
                    }
                }
                
                $tablasActualizadas[] = $this->actualizarTablaRelacionada(
                    $id, 'estudiostrabajo', $datosEstudios
                );
            }
            
            // 3. Actualizar tabla tallas
            if (isset($datos['tallas']) && is_array($datos['tallas'])) {
                $datosTallas = [];
                
                // Mapear los nombres del formulario a los nombres de columnas
                if (isset($datos['tallas']['camisa'])) $datosTallas['talla_camisa'] = $datos['tallas']['camisa'];
                if (isset($datos['tallas']['camiseta'])) $datosTallas['talla_camiseta'] = $datos['tallas']['camiseta'];
                if (isset($datos['tallas']['pantalon'])) $datosTallas['talla_pantalon'] = $datos['tallas']['pantalon'];
                if (isset($datos['tallas']['zapatos'])) $datosTallas['talla_zapatos'] = $datos['tallas']['zapatos'];
                
                $tablasActualizadas[] = $this->actualizarTablaRelacionada(
                    $id, 'tallas', $datosTallas
                );
            }
            
            // 4. Actualizar tabla salud y emergencias
            if (isset($datos['salud']) && is_array($datos['salud'])) {
                // Mapear 'grupo_sanguineo' a 'rh'
                if (isset($datos['salud']['grupo_sanguineo'])) {
                    $datos['salud']['rh'] = $datos['salud']['grupo_sanguineo'];
                    unset($datos['salud']['grupo_sanguineo']);
                }
                
                $tablasActualizadas[] = $this->actualizarTablaRelacionada(
                    $id, 'saludemergencias', $datos['salud']
                );
            }
            
            // 5. Actualizar tabla carrera bíblica
            if (isset($datos['carrera']) && is_array($datos['carrera'])) {
                $datosCarrera = $datos['carrera'];
                $tablasActualizadas[] = $this->actualizarTablaRelacionada(
                    $id, 'carrerabiblica', $datosCarrera
                );
            }
            
            return true;
        } catch (\Exception $e) {
            error_log("Error al actualizar tablas relacionadas: " . $e->getMessage());
            return false;
        }
    }

    // Método auxiliar para actualizar cada tabla relacionada
    private function actualizarTablaRelacionada($miembroId, $tabla, $datos) {
        try {
            // Filtrar campos que no corresponden a columnas en la base de datos
            if ($tabla == 'estudiostrabajo') {
                // Eliminar campos auxiliares
                unset($datos['institucion_educativa_select']);
                unset($datos['institucion_personalizada']);
                unset($datos['profesion_select']);
                unset($datos['profesion_personalizada']);
            }
            
            $db = \Database::getInstance()->getConnection();
            
            // Añadir el ID del miembro a los datos
            $datos['miembro_id'] = $miembroId;
            
            // Verificar si ya existe registro para este miembro en esta tabla
            $checkStmt = $db->prepare("SELECT COUNT(*) FROM {$tabla} WHERE miembro_id = ?");
            $checkStmt->execute([$miembroId]);
            $existe = (int)$checkStmt->fetchColumn() > 0;
            
            // Preparar los campos y valores para la consulta
            $setClausulas = [];
            $campos = [];
            $valores = [];
            $placeholders = [];
            
            // Procesar cada campo, excepto 'miembro_id' que ya lo manejamos por separado
            foreach ($datos as $campo => $valor) {
                // Validación básica de los datos
                if ($campo === 'miembro_id') {
                    continue; // Lo manejamos por separado para evitar duplicación
                }
                
                // Añadir a la lista correspondiente según operación
                if ($existe) {
                    $setClausulas[] = "$campo = ?";
                } else {
                    $campos[] = $campo;
                    $placeholders[] = "?";
                }
                
                // Añadir valor a la lista de valores
                $valores[] = $valor;
            }
            
            // Si existe registro, actualizar
            if ($existe) {
                if (empty($setClausulas)) {
                    error_log("No hay campos para actualizar en tabla $tabla");
                    return null;
                }
                
                // Añadir el miembro_id al final de los valores para el WHERE
                $valores[] = $miembroId;
                
                $sql = "UPDATE {$tabla} SET " . implode(', ', $setClausulas) . " WHERE miembro_id = ?";
                $stmt = $db->prepare($sql);
                $resultado = $stmt->execute($valores);
                
                error_log("SQL UPDATE para tabla $tabla: " . $sql);
                error_log("Valores: " . implode(", ", $valores));
                error_log("Resultado actualización $tabla: " . ($resultado ? "Éxito" : "Error") . " - Filas: " . $stmt->rowCount());
                
                return $resultado ? $tabla : null;
            } 
            // Si no existe, crear nuevo registro
            else {
                // Añadir miembro_id a la lista de campos y valores
                $campos[] = 'miembro_id';
                $placeholders[] = "?";
                $valores[] = $miembroId;
                
                $sql = "INSERT INTO {$tabla} (" . implode(', ', $campos) . ") VALUES (" . implode(', ', $placeholders) . ")";
                $stmt = $db->prepare($sql);
                $resultado = $stmt->execute($valores);
                
                error_log("SQL INSERT para tabla $tabla: " . $sql);
                error_log("Valores: " . implode(", ", $valores));
                error_log("Resultado inserción $tabla: " . ($resultado ? "Éxito" : "Error"));
                
                return $resultado ? $tabla : null;
            }
        } catch (\Exception $e) {
            error_log("Error al actualizar tabla $tabla: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Elimina un miembro y todos sus datos relacionados
     */
    public function eliminar($id)
    {
        // Añadir estos logs para depuración
        error_log("Iniciando eliminación, ID recibido: " . print_r($id, true));
        
        // Mejorar el manejo del ID
        if (is_array($id)) {
            $id = isset($id[0]) && !empty($id[0]) ? $id[0] : null;
            error_log("ID procesado de array: " . ($id ?? 'null'));
        }
        
        // Validar que el ID sea un valor numérico válido
        if ($id === null || !is_numeric($id) || (int)$id <= 0) {
            $_SESSION['flash_message'] = 'ID de miembro inválido o no proporcionado';
            $_SESSION['flash_type'] = 'danger';
            $this->redirect('miembros');
            return;
        }
        
        $id = (int)$id;
        error_log("ID final validado a procesar: $id");
        
        // Obtener el miembro completo
        $miembro = $this->miembroModel->getFullProfile($id);
        error_log("Resultado de getFullProfile: " . ($miembro ? "Miembro encontrado" : "Miembro NO encontrado"));
        
        if (!$miembro) {
            $_SESSION['flash_message'] = "Miembro con ID $id no encontrado";
            $_SESSION['flash_type'] = 'danger';
            $this->redirect('miembros');
            return;
        }
        
        // Intentar eliminar el miembro y sus datos relacionados
        try {
            // Comenzar una transacción
            $this->db->beginTransaction();
            
            // 1. Eliminar registros de tablas relacionadas
            $tablasRelacionadas = [
                'contacto',
                'estudiostrabajo',
                'tallas',
                'saludemergencias',
                'carrerabiblica'
            ];
            
            foreach ($tablasRelacionadas as $tabla) {
                $stmt = $this->db->prepare("DELETE FROM {$tabla} WHERE miembro_id = ?");
                $stmt->execute([$id]);
                error_log("Eliminación de $tabla: " . ($stmt->rowCount() > 0 ? "Éxito" : "Sin registros"));
            }
            
            // 2. Eliminar foto si existe
            if (!empty($miembro['foto'])) {
                $rutaFoto = BASE_PATH . '/uploads/miembros/' . $miembro['foto'];
                if (file_exists($rutaFoto)) {
                    unlink($rutaFoto);
                    error_log("Foto eliminada: $rutaFoto");
                } else {
                    error_log("No se encontró la foto: $rutaFoto");
                }
            }
            
            // 3. Eliminar el registro principal
            $stmt = $this->db->prepare("DELETE FROM informaciongeneral WHERE id = ?");
            $resultado = $stmt->execute([$id]);
            error_log("Eliminación de registro principal: " . ($resultado ? "Éxito" : "Fallido"));
            
            // Si todo salió bien, confirmar transacción
            if ($resultado) {
                $this->db->commit();
                $_SESSION['flash_message'] = 'Miembro eliminado correctamente';
                $_SESSION['flash_type'] = 'success';
                error_log("Transacción confirmada, miembro eliminado");
            } else {
                // Si hubo algún problema
                $this->db->rollBack();
                $_SESSION['flash_message'] = 'Error al eliminar el miembro';
                $_SESSION['flash_type'] = 'danger';
                error_log("Falló la eliminación del registro principal, transacción revertida");
            }
        } catch (\Exception $e) {
            // En caso de excepción, deshacer cambios
            $this->db->rollBack();
            $_SESSION['flash_message'] = 'Error: ' . $e->getMessage();
            $_SESSION['flash_type'] = 'danger';
            error_log("Excepción al eliminar miembro: " . $e->getMessage());
        }
        
        // Redireccionar a la lista de miembros
        $this->redirect('miembros');
    }

    // Método auxiliar para responder en formato JSON
    private function responderJson($data)
    {
        // Limpiar cualquier salida previa
        if (ob_get_contents()) ob_clean();
        
        // Establecer encabezados correctos
        header('Content-Type: application/json');
        header('Cache-Control: no-cache, must-revalidate');
        
        // Asegurar que no hay salida de errores PHP
        ini_set('display_errors', 0);
        
        // Enviar respuesta JSON
        echo json_encode($data);
        exit; // Importante: detener ejecución
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

    /**
     * API para actualizar una sección específica
     */
    public function apiActualizarSeccion() 
    {
        // Asegurar que la respuesta sea JSON
        header('Content-Type: application/json');
        
        try {
            // Verificar método
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                echo json_encode(['success' => false, 'message' => 'Método no permitido']);
                return;
            }
            
            // Obtener parámetros
            $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
            $tabla = $_POST['tabla'] ?? '';
            $datos = $_POST['datos'] ?? [];
            
            error_log("API actualizar sección - ID: $id, Tabla: $tabla, Datos: " . print_r($datos, true));
            
            // Validaciones
            if (!$id || $id <= 0) {
                echo json_encode(['success' => false, 'message' => 'ID de miembro inválido']);
                return;
            }
            
            if (empty($tabla)) {
                echo json_encode(['success' => false, 'message' => 'Nombre de tabla no especificado']);
                return;
            }
            
            // Verificar que el miembro existe
            if (!$this->miembroModel->existeId($id)) {
                echo json_encode(['success' => false, 'message' => 'El miembro no existe']);
                return;
            }
            
            // Procesar según la tabla
            $resultado = false;
            
            switch ($tabla) {
                case 'informaciongeneral':
                    $resultado = $this->miembroModel->actualizar($id, $datos);
                    break;
                    
                case 'contacto':
                    $resultado = $this->contactoModel->actualizarPorMiembroId($id, $datos);
                    break;
                    
                case 'estudiostrabajo':
                    $resultado = $this->estudiosTrabajoModel->actualizarPorMiembroId($id, $datos);
                    break;
                    
                case 'tallas':
                    $resultado = $this->tallasModel->actualizarPorMiembroId($id, $datos);
                    break;
                    
                case 'saludemergencias':
                    $resultado = $this->saludEmergenciasModel->actualizarPorMiembroId($id, $datos);
                    break;
                    
                case 'carrerabiblica':
                    $resultado = $this->carreraBiblicaModel->actualizarPorMiembroId($id, $datos);
                    break;
                    
                default:
                    echo json_encode(['success' => false, 'message' => 'Tabla no reconocida']);
                    return;
            }
            
            if ($resultado) {
                echo json_encode([
                    'success' => true,
                    'message' => "Sección actualizada correctamente",
                    'tabla' => $tabla
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => "Error al actualizar la sección: $tabla"
                ]);
            }
            
        } catch (\Exception $e) {
            error_log("Error en API actualizar sección: " . $e->getMessage());
            echo json_encode([
                'success' => false,
                'message' => "Error: " . $e->getMessage()
            ]);
        }
    }

    private function getFileUploadErrorMessage($error_code) {
        switch ($error_code) {
            case UPLOAD_ERR_INI_SIZE:
                return 'El archivo excede el tamaño máximo permitido en php.ini';
            case UPLOAD_ERR_FORM_SIZE:
                return 'El archivo excede el tamaño máximo permitido en el formulario';
            case UPLOAD_ERR_PARTIAL:
                return 'El archivo fue cargado parcialmente';
            case UPLOAD_ERR_NO_FILE:
                return 'No se seleccionó ningún archivo';
            case UPLOAD_ERR_NO_TMP_DIR:
                return 'Falta la carpeta temporal';
            case UPLOAD_ERR_CANT_WRITE:
                return 'Error al escribir el archivo en el disco';
            case UPLOAD_ERR_EXTENSION:
                return 'La carga fue detenida por una extensión PHP';
            default:
                return 'Error desconocido en la carga: ' . $error_code;
        }
    }

    /**
     * Método para encontrar el primer miembro disponible
     * @return int|null ID del primer miembro o null si no hay miembros
     */
    private function encontrarPrimerMiembroID() {
        try {
            $stmt = $this->db->query("SELECT MIN(id) as min_id FROM informaciongeneral");
            $resultado = $stmt->fetch();
            return $resultado['min_id'];
        } catch (\Exception $e) {
            error_log("Error al buscar el primer ID: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Muestra el perfil de un miembro específico
     * Si no se proporciona ID, muestra el primer miembro disponible
     */
    public function ver($id = null) {
        try {
            // Si no se proporciona ID o es inválido, buscar el primer miembro
            if (empty($id) || !is_numeric($id)) {
                $id = $this->encontrarPrimerMiembroID();
                
                // Si aún no hay ID, redirigir a la lista
                if (empty($id)) {
                    $_SESSION['mensaje'] = [
                        'tipo' => 'warning',
                        'texto' => 'No se encontraron miembros en el sistema.'
                    ];
                    redirect('miembros');
                    return;
                }
                
                // Redirigir a la página del primer miembro
                redirect("miembros/{$id}");
                return;
            }
            
            // Continuar con el código existente para mostrar el perfil
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
        } catch (\Exception $e) {
            error_log("Error al mostrar perfil: " . $e->getMessage());
            $_SESSION['flash_message'] = "Error al mostrar el perfil: " . $e->getMessage();
            $_SESSION['flash_type'] = "danger";
            redirect('miembros');
        }
    }
    
    /**
     * Busca miembros por nombre, apellido o ID
     */
    public function buscar() {
        try {
            $busqueda = $_GET['q'] ?? '';
            
            // Si la búsqueda parece ser un ID (solo números), redirigir directamente
            if (preg_match('/^(\d+)(\s|$)/', $busqueda, $matches)) {
                $id = $matches[1];
                redirect("miembros/{$id}");
                return;
            }
            
            // Si la búsqueda incluye formato "ID - Nombre" extraer el ID
            if (preg_match('/^(\d+)\s*-\s*/', $busqueda, $matches)) {
                $id = $matches[1];
                redirect("miembros/{$id}");
                return;
            }
            
            // Continuar con la búsqueda normal por nombre/apellido
            $miembros = \App\Models\Miembro::buscar($busqueda);
            
            // Si solo hay un resultado, ir directamente a ese perfil
            if (count($miembros) == 1) {
                redirect("miembros/{$miembros[0]['id']}");
                return;
            }
            
            // Mostrar resultados de búsqueda
            $this->renderView('miembros/resultados', [
                'miembros' => $miembros,
                'busqueda' => $busqueda
            ]);
        } catch (\Exception $e) {
            // Manejo de errores
        }
    }
}