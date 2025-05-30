<?php

// Archivo para probar la seguridad del formulario de login
echo "<!DOCTYPE html>
<html>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <meta http-equiv='Content-Security-Policy' content='upgrade-insecure-requests'>
    <meta name='referrer' content='origin'>
    <title>Prueba de Login Seguro</title>
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet'>
</head>
<body>
    <div class='container mt-5'>
        <div class='row justify-content-center'>
            <div class='col-md-6'>
                <div class='card'>
                    <div class='card-header bg-primary text-white'>
                        <h3>Prueba de Login Seguro</h3>
                    </div>
                    <div class='card-body'>
                        <form action='auth/login' method='post' autocomplete='on'>
                            <input type='hidden' name='secure_form' value='1'>
                            
                            <div class='mb-3'>
                                <label for='email_or_username' class='form-label'>Email o nombre de usuario</label>
                                <input type='text' class='form-control' id='email_or_username' 
                                       name='email_or_username' autocomplete='username' required>
                            </div>
                            
                            <div class='mb-3'>
                                <label for='password' class='form-label'>Contraseña</label>
                                <input type='password' class='form-control' id='password' 
                                       name='password' autocomplete='current-password' required>
                            </div>
                            
                            <div class='d-grid'>
                                <button type='submit' class='btn btn-primary'>Iniciar Sesión</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>";
?>