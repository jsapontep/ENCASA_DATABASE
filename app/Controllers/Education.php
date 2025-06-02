<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\EducationModel;

class Education extends Controller {
    private $model;
    
    public function __construct() {
        $this->model = new EducationModel();
    }
    
    // Obtener datos educativos por país
    public function getEducationData($pais) {
        // Obtener datos de instituciones y carreras por país y nivel educativo
        $data = $this->model->getEducationDataByCountry($pais);
        
        // Devolver respuesta JSON
        header('Content-Type: application/json');
        echo json_encode($data);
    }
    
    // Guardar nueva institución
    public function saveInstitution() {
        // Obtener datos del POST (formato JSON)
        $data = json_decode(file_get_contents('php://input'), true);
        
        // Validar datos
        if (!isset($data['pais']) || !isset($data['nivel']) || !isset($data['nombre'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Datos incompletos']);
            return;
        }
        
        // Guardar nueva institución
        $result = $this->model->saveInstitution($data['pais'], $data['nivel'], $data['nombre']);
        
        // Devolver respuesta
        header('Content-Type: application/json');
        echo json_encode($result);
    }
    
    // Guardar nueva profesión
    public function saveProfession() {
        // Obtener datos del POST (formato JSON)
        $data = json_decode(file_get_contents('php://input'), true);
        
        // Validar datos
        if (!isset($data['pais']) || !isset($data['nivel']) || 
            !isset($data['institucionId']) || !isset($data['nombre'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Datos incompletos']);
            return;
        }
        
        // Guardar nueva profesión
        $result = $this->model->saveProfession(
            $data['pais'], 
            $data['nivel'], 
            $data['institucionId'], 
            $data['nombre']
        );
        
        // Devolver respuesta
        header('Content-Type: application/json');
        echo json_encode($result);
    }
}