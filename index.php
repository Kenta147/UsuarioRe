<?php
// Activar debugging temporal
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

// Configuración básica
define('BASE_PATH', __DIR__);
define('APP_PATH', BASE_PATH . '/app');

echo "<!-- DEBUG: BASE_PATH = " . BASE_PATH . " -->\n";
echo "<!-- DEBUG: APP_PATH = " . APP_PATH . " -->\n";

// Verificar archivos críticos
$archivos_criticos = [
    BASE_PATH . '/routes/web.php',
    APP_PATH . '/controllers/RegistroController.php',
    APP_PATH . '/models/Usuario.php',
    APP_PATH . '/views/registro.php'
];

foreach ($archivos_criticos as $archivo) {
    if (!file_exists($archivo)) {
        die("ERROR: No se encuentra el archivo: $archivo");
    }
    echo "<!-- DEBUG: Archivo OK: $archivo -->\n";
}

// Autoload simple para las clases
spl_autoload_register(function ($class) {
    $paths = [
        APP_PATH . '/controllers/',
        APP_PATH . '/models/'
    ];
    
    foreach ($paths as $path) {
        $file = $path . $class . '.php';
        echo "<!-- DEBUG: Intentando cargar: $file -->\n";
        if (file_exists($file)) {
            require_once $file;
            echo "<!-- DEBUG: Cargado exitosamente: $file -->\n";
            break;
        }
    }
});

// Router simple
$request = $_SERVER['REQUEST_URI'];
$path = parse_url($request, PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

echo "<!-- DEBUG: REQUEST_URI = $request -->\n";
echo "<!-- DEBUG: PATH = $path -->\n";
echo "<!-- DEBUG: METHOD = $method -->\n";

// Incluir rutas
require_once BASE_PATH . '/routes/web.php';
echo "<!-- DEBUG: Rutas cargadas -->\n";

// Verificar si existe la variable $routes
if (!isset($routes)) {
    die('ERROR: La variable $routes no está definida en web.php');
}

echo "<!-- DEBUG: Rutas disponibles: " . print_r($routes, true) . " -->\n";

// Ejecutar la ruta correspondiente
if (isset($routes[$method][$path])) {
    echo "<!-- DEBUG: Ruta encontrada para $method $path -->\n";
    $route = $routes[$method][$path];
    
    // Verificar si la clase del controlador existe
    if (!class_exists($route['controller'])) {
        die("ERROR: La clase {$route['controller']} no existe");
    }
    
    echo "<!-- DEBUG: Creando controlador: {$route['controller']} -->\n";
    $controller = new $route['controller']();
    
    // Verificar si el método existe
    if (!method_exists($controller, $route['action'])) {
        die("ERROR: El método {$route['action']} no existe en {$route['controller']}");
    }
    
    echo "<!-- DEBUG: Ejecutando método: {$route['action']} -->\n";
    $controller->{$route['action']}();
} else {
    echo "<!-- DEBUG: Ruta no encontrada, usando ruta por defecto -->\n";
    // Ruta por defecto - mostrar formulario de registro
    if (!class_exists('RegistroController')) {
        die('ERROR: La clase RegistroController no existe');
    }
    
    $controller = new RegistroController();
    $controller->mostrar();
}
?>