<?php
// Añadir estas líneas donde corresponda

// API para datos educativos
$router->get('/api/education-data/:country', 'EducationController@getEducationData');
$router->post('/api/education-data/save-institution', 'EducationController@saveInstitution');
$router->post('/api/education-data/save-profession', 'EducationController@saveProfession');