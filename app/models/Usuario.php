<?php

class Usuario {
    private $nombre;
    private $correo;
    private $password;
    private $tipo_usuario;
    private $fecha_registro;
    
    public function __construct($nombre = '', $correo = '', $password = '', $tipo_usuario = 'cliente') {
        error_log("DEBUG: Usuario::__construct() ejecutado");
        $this->nombre = $nombre;
        $this->correo = $correo;  
        $this->password = $password;
        $this->tipo_usuario = $tipo_usuario;
        $this->fecha_registro = date('Y-m-d H:i:s');
    }
    
    // Getters
    public function getNombre() {
        return $this->nombre;
    }
    
    public function getCorreo() {
        return $this->correo;
    }
    
    public function getPassword() {
        return $this->password;
    }
    
    public function getTipoUsuario() {
        return $this->tipo_usuario;
    }
    
    public function getFechaRegistro() {
        return $this->fecha_registro;
    }
    
    // Setters
    public function setNombre($nombre) {
        $this->nombre = trim($nombre);
        error_log("DEBUG: Nombre establecido: " . $this->nombre);
    }
    
    public function setCorreo($correo) {
        $this->correo = strtolower(trim($correo));
        error_log("DEBUG: Correo establecido: " . $this->correo);
    }
    
    public function setPassword($password) {
        $this->password = password_hash($password, PASSWORD_DEFAULT);
        error_log("DEBUG: Password hasheado exitosamente");
    }
    
    public function setTipoUsuario($tipo) {
        $this->tipo_usuario = $tipo;
        error_log("DEBUG: Tipo de usuario establecido: " . $this->tipo_usuario);
    }
    
    // Validaciones
    public function validar() {
        error_log("DEBUG: Usuario::validar() iniciado");
        $errores = [];
        
        if (empty($this->nombre)) {
            $errores[] = 'El nombre es obligatorio';
        } elseif (strlen($this->nombre) < 2) {
            $errores[] = 'El nombre debe tener al menos 2 caracteres';
        }
        
        if (empty($this->correo)) {
            $errores[] = 'El correo electrónico es obligatorio';
        } elseif (!filter_var($this->correo, FILTER_VALIDATE_EMAIL)) {
            $errores[] = 'El formato del correo electrónico no es válido';
        }
        
        if (empty($this->password)) {
            $errores[] = 'La contraseña es obligatoria';
        }
        
        if (!in_array($this->tipo_usuario, ['cliente', 'administrador', 'vendedor'])) {
            $errores[] = 'Tipo de usuario no válido';
        }
        
        error_log("DEBUG: Validación completada. Errores: " . count($errores));
        return $errores;
    }
    
    // Simulación de guardado en archivo
    public function guardar() {
        error_log("DEBUG: Usuario::guardar() iniciado");
        
        // Verificar si BASE_PATH está definido
        if (!defined('BASE_PATH')) {
            error_log("ERROR: BASE_PATH no está definido");
            return ['success' => false, 'message' => 'Error de configuración del sistema'];
        }
        
        $archivo = BASE_PATH . '/storage/usuarios.json';
        error_log("DEBUG: Ruta del archivo: $archivo");
        
        // Crear directorio si no existe
        $directorio = dirname($archivo);
        error_log("DEBUG: Directorio de storage: $directorio");
        
        if (!file_exists($directorio)) {
            error_log("DEBUG: Creando directorio storage");
            if (!mkdir($directorio, 0755, true)) {
                error_log("ERROR: No se pudo crear el directorio storage");
                return ['success' => false, 'message' => 'Error al crear directorio de almacenamiento'];
            }
        }
        
        // Verificar permisos del directorio
        if (!is_writable($directorio)) {
            error_log("ERROR: El directorio storage no tiene permisos de escritura");
            return ['success' => false, 'message' => 'No hay permisos de escritura en el directorio de almacenamiento'];
        }
        
        // Leer usuarios existentes
        $usuarios = [];
        if (file_exists($archivo)) {
            error_log("DEBUG: Archivo usuarios.json existe, leyendo contenido");
            $contenido = file_get_contents($archivo);
            if ($contenido === false) {
                error_log("ERROR: No se pudo leer el archivo usuarios.json");
                return ['success' => false, 'message' => 'Error al leer archivo de usuarios'];
            }
            
            $usuarios = json_decode($contenido, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                error_log("ERROR: Error al decodificar JSON: " . json_last_error_msg());
                $usuarios = []; // Resetear si hay error en JSON
            }
            
            error_log("DEBUG: Usuarios existentes: " . count($usuarios));
        } else {
            error_log("DEBUG: Archivo usuarios.json no existe, se creará nuevo");
        }
        
        // Verificar si el correo ya existe
        foreach ($usuarios as $usuario) {
            if (isset($usuario['correo']) && $usuario['correo'] === $this->correo) {
                error_log("DEBUG: Correo ya existe: " . $this->correo);
                return ['success' => false, 'message' => 'El correo electrónico ya está registrado'];
            }
        }
        
        // Agregar nuevo usuario
        $nuevoUsuario = [
            'id' => count($usuarios) + 1,
            'nombre' => $this->nombre,
            'correo' => $this->correo,
            'password' => $this->password,
            'tipo_usuario' => $this->tipo_usuario,
            'fecha_registro' => $this->fecha_registro
        ];
        
        $usuarios[] = $nuevoUsuario;
        error_log("DEBUG: Nuevo usuario agregado. Total usuarios: " . count($usuarios));
        
        // Guardar en archivo
        $jsonData = json_encode($usuarios, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        if ($jsonData === false) {
            error_log("ERROR: Error al codificar JSON: " . json_last_error_msg());
            return ['success' => false, 'message' => 'Error al preparar datos para guardar'];
        }
        
        $resultado = file_put_contents($archivo, $jsonData, LOCK_EX);
        
        if ($resultado !== false) {
            error_log("DEBUG: Usuario guardado exitosamente. Bytes escritos: $resultado");
            return ['success' => true, 'message' => 'Usuario registrado exitosamente'];
        } else {
            error_log("ERROR: No se pudo escribir el archivo usuarios.json");
            return ['success' => false, 'message' => 'Error al guardar el usuario'];
        }
    }
    
    // Obtener todos los usuarios (para pruebas)
    public static function obtenerTodos() {
        error_log("DEBUG: Usuario::obtenerTodos() ejecutado");
        
        if (!defined('BASE_PATH')) {
            error_log("ERROR: BASE_PATH no está definido en obtenerTodos()");
            return [];
        }
        
        $archivo = BASE_PATH . '/storage/usuarios.json';
        
        if (file_exists($archivo)) {
            $contenido = file_get_contents($archivo);
            $usuarios = json_decode($contenido, true) ?? [];
            error_log("DEBUG: Usuarios obtenidos: " . count($usuarios));
            return $usuarios;
        }
        
        error_log("DEBUG: Archivo usuarios.json no existe");
        return [];
    }
}

?>