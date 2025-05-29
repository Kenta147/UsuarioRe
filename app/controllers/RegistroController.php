<?php

class RegistroController {
    
    public function mostrar() {
        error_log("DEBUG: RegistroController::mostrar() ejecutado");
        
        $datos = [
            'titulo' => 'Registro de Usuario',
            'errores' => $_SESSION['errores'] ?? [],
            'datos_previos' => $_SESSION['datos_previos'] ?? [],
            'mensaje_exito' => $_SESSION['mensaje_exito'] ?? '',
            'mensaje_error' => $_SESSION['mensaje_error'] ?? ''
        ];
        
        error_log("DEBUG: Datos preparados: " . print_r($datos, true));
        
        // Limpiar mensajes de sesión
        unset($_SESSION['errores'], $_SESSION['datos_previos'], $_SESSION['mensaje_exito'], $_SESSION['mensaje_error']);
        
        $this->render('registro', $datos);
    }
    
    public function procesar() {
        error_log("DEBUG: RegistroController::procesar() iniciado");
        error_log("DEBUG: REQUEST_METHOD = " . $_SERVER['REQUEST_METHOD']);
        error_log("DEBUG: POST data = " . print_r($_POST, true));
        
        $esAjax = $this->esAjax();
        error_log("DEBUG: Es petición AJAX: " . ($esAjax ? 'SI' : 'NO'));
        
        try {
            // Validar que sea una petición POST
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('Método no permitido');
            }
            
            // Obtener datos del formulario
            $nombre = $_POST['nombre'] ?? '';
            $correo = $_POST['correo'] ?? '';
            $password = $_POST['password'] ?? '';
            $confirmar_password = $_POST['confirmar_password'] ?? '';
            $tipo_usuario = $_POST['tipo_usuario'] ?? 'cliente';
            
            error_log("DEBUG: Datos recibidos - Nombre: $nombre, Correo: $correo, Tipo: $tipo_usuario");
            
            // Validar que las contraseñas coincidan
            if ($password !== $confirmar_password) {
                throw new Exception('Las contraseñas no coinciden');
            }
            
            // Validar longitud de contraseña
            if (strlen($password) < 6) {
                throw new Exception('La contraseña debe tener al menos 6 caracteres');
            }
            
            // Verificar si la clase Usuario existe
            if (!class_exists('Usuario')) {
                throw new Exception('La clase Usuario no está disponible');
            }
            
            // Crear instancia del usuario
            $usuario = new Usuario();
            $usuario->setNombre($nombre);
            $usuario->setCorreo($correo);
            $usuario->setPassword($password);
            $usuario->setTipoUsuario($tipo_usuario);
            
            error_log("DEBUG: Usuario creado exitosamente");
            
            // Validar datos del usuario
            $errores = $usuario->validar();
            error_log("DEBUG: Errores de validación: " . print_r($errores, true));
            
            if (!empty($errores)) {
                if ($esAjax) {
                    $this->responderJson([
                        'success' => false,
                        'message' => 'Datos inválidos',
                        'errors' => $errores
                    ]);
                    return;
                }
                
                $_SESSION['errores'] = $errores;
                $_SESSION['datos_previos'] = [
                    'nombre' => $nombre,
                    'correo' => $correo,
                    'tipo_usuario' => $tipo_usuario
                ];
                header('Location: /registro');
                exit;
            }
            
            // Intentar guardar el usuario
            error_log("DEBUG: Intentando guardar usuario");
            $resultado = $usuario->guardar();
            error_log("DEBUG: Resultado del guardado: " . print_r($resultado, true));
            
            if ($resultado['success']) {
                if ($esAjax) {
                    $this->responderJson([
                        'success' => true,
                        'message' => $resultado['message'],
                        'usuario' => [
                            'nombre' => $usuario->getNombre(),
                            'correo' => $usuario->getCorreo(),
                            'tipo_usuario' => $usuario->getTipoUsuario()
                        ]
                    ]);
                    return;
                }
                
                $_SESSION['mensaje_exito'] = $resultado['message'];
            } else {
                throw new Exception($resultado['message']);
            }
            
        } catch (Exception $e) {
            error_log("DEBUG: Excepción capturada: " . $e->getMessage());
            
            if ($esAjax) {
                $this->responderJson([
                    'success' => false,
                    'message' => $e->getMessage()
                ]);
                return;
            }
            
            $_SESSION['mensaje_error'] = $e->getMessage();
            $_SESSION['datos_previos'] = [
                'nombre' => $nombre ?? '',
                'correo' => $correo ?? '',
                'tipo_usuario' => $tipo_usuario ?? 'cliente'
            ];
        }
        
        // Redireccionar solo si no es AJAX
        error_log("DEBUG: Redirigiendo a /registro");
        header('Location: /registro');
        exit;
    }
    
    private function render($vista, $datos = []) {
        error_log("DEBUG: Renderizando vista: $vista");
        
        // Extraer variables para la vista
        extract($datos);
        
        // Incluir la vista
        $archivo_vista = APP_PATH . '/views/' . $vista . '.php';
        error_log("DEBUG: Ruta de vista: $archivo_vista");
        
        if (file_exists($archivo_vista)) {
            error_log("DEBUG: Vista encontrada, incluyendo...");
            include $archivo_vista;
        } else {
            die("Vista no encontrada: $vista en la ruta: $archivo_vista");
        }
    }
    
    private function esAjax() {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }
    
    private function responderJson($datos) {
        error_log("DEBUG: Respondiendo JSON: " . json_encode($datos));
        header('Content-Type: application/json');
        echo json_encode($datos);
        exit;
    }
}

?>