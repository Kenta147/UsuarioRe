<?php

// Definición de rutas del sistema
$routes = [
    'GET' => [
        '/' => [
            'controller' => 'RegistroController',
            'action' => 'mostrar'
        ],
        '/registro' => [
            'controller' => 'RegistroController',
            'action' => 'mostrar'
        ]
    ],
    'POST' => [
    '/registro' => [
        'controller' => 'RegistroController',
        'action' => 'procesar'
    ],
    '/registro/' => [ // <-- Agrega esta línea
        'controller' => 'RegistroController',
        'action' => 'procesar'
    ]
]

];

?>