<?php
// filepath: /Applications/XAMPP/xamppfiles/htdocs/Encasa_Database/app/helpers/Validator.php
namespace App\Helpers;

class Validator {
    private $errors = [];
    
    public function validate($data, $rules) {
        $this->errors = [];
        
        foreach ($rules as $field => $fieldRules) {
            foreach ($fieldRules as $rule) {
                $ruleParts = explode(':', $rule);
                $ruleName = $ruleParts[0];
                $ruleParam = isset($ruleParts[1]) ? $ruleParts[1] : null;
                
                switch ($ruleName) {
                    case 'required':
                        if (!isset($data[$field]) || trim($data[$field]) === '') {
                            $this->errors[$field][] = "El campo {$field} es obligatorio.";
                        }
                        break;
                        
                    case 'email':
                        if (isset($data[$field]) && !filter_var($data[$field], FILTER_VALIDATE_EMAIL)) {
                            $this->errors[$field][] = "El campo {$field} debe ser un email válido.";
                        }
                        break;
                        
                    case 'min':
                        if (isset($data[$field]) && strlen($data[$field]) < $ruleParam) {
                            $this->errors[$field][] = "El campo {$field} debe tener al menos {$ruleParam} caracteres.";
                        }
                        break;
                }
            }
        }
        
        return empty($this->errors);
    }
    
    public function getErrors() {
        return $this->errors;
    }
}