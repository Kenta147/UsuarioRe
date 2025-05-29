<?php
echo "<h1>Test del Sistema</h1>";

// Test 1: Configuración básica
echo "<h2>1. Configuración básica</h2>";
define('BASE_PATH', __DIR__);
define('APP_PATH', BASE_PATH . '/app');
echo "BASE_PATH: " . BASE_PATH . "<br>";
echo "APP_PATH: " . APP_PATH . "<br>";

// Test 2: Archivos existentes
echo "<h2>2. Verificación de archivos</h2>";
$archivos = [
    'routes/web.php',
    'app/controllers/RegistroController.php',
    'app/models/Usuario.php',
    'app/views/registro.php'
];

foreach ($archivos as $archivo) {
    $ruta_completa = BASE_PATH . '/' . $archivo;
    $existe = file_exists($ruta_completa) ? '✅' : '❌';
    echo "$existe $archivo<br>";
}

// Test 3: Directorio storage
echo "<h2>3. Directorio storage</h2>";
$storage_dir = BASE_PATH . '/storage';
if (!file_exists($storage_dir)) {
    mkdir($storage_dir, 0755, true);
    echo "✅ Directorio storage creado<br>";
} else {
    echo "✅ Directorio storage existe<br>";
}

if (is_writable($storage_dir)) {
    echo "✅ Directorio storage tiene permisos de escritura<br>";
} else {
    echo "❌ Directorio storage NO tiene permisos de escritura<br>";
}

// Test 4: Autoload
echo "<h2>4. Test de autoload</h2>";
spl_autoload_register(function ($class) {
    $paths = [
        APP_PATH . '/controllers/',
        APP_PATH . '/models/'
    ];
    
    foreach ($paths as $path) {
        $file = $path . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            echo "✅ Clase $class cargada desde $file<br>";
            return;
        }
    }
    echo "❌ No se pudo cargar la clase $class<br>";
});

// Test 5: Clases
echo "<h2>5. Test de clases</h2>";
try {
    $controller = new RegistroController();
    echo "✅ RegistroController creado exitosamente<br>";
} catch (Exception $e) {
    echo "❌ Error creando RegistroController: " . $e->getMessage() . "<br>";
}

try {
    $usuario = new Usuario();
    echo "✅ Usuario creado exitosamente<br>";
} catch (Exception $e) {
    echo "❌ Error creando Usuario: " . $e->getMessage() . "<br>";
}

// Test 6: Rutas
echo "<h2>6. Test de rutas</h2>";
require_once BASE_PATH . '/routes/web.php';
if (isset($routes)) {
    echo "✅ Variable \$routes definida<br>";
    echo "<pre>" . print_r($routes, true) . "</pre>";
} else {
    echo "❌ Variable \$routes NO definida<br>";
}

// Test 7: Simulación de guardado
echo "<h2>7. Test de guardado</h2>";
try {
    $usuario = new Usuario();
    $usuario->setNombre('Test User');
    $usuario->setCorreo('test@example.com');
    $usuario->setPassword('123456');
    $usuario->setTipoUsuario('cliente');
    
    $resultado = $usuario->guardar();
    if ($resultado['success']) {
        echo "✅ Usuario de prueba guardado exitosamente<br>";
        echo "Mensaje: " . $resultado['message'] . "<br>";
    } else {
        echo "❌ Error guardando usuario de prueba: " . $resultado['message'] . "<br>";
    }
} catch (Exception $e) {
    echo "❌ Excepción guardando usuario: " . $e->getMessage() . "<br>";
}

echo "<h2>8. Información del servidor</h2>";
echo "PHP Version: " . phpversion() . "<br>";
echo "Server Software: " . ($_SERVER['SERVER_SOFTWARE'] ?? 'N/A') . "<br>";
echo "Document Root: " . ($_SERVER['DOCUMENT_ROOT'] ?? 'N/A') . "<br>";
echo "Script Name: " . ($_SERVER['SCRIPT_NAME'] ?? 'N/A') . "<br>";
?>