<?php 

return [
    'categorias' => [
        'emergencias' => 'emergencias',
        'reportes' => 'reportes',
        'eventos' => 'eventos',
        'problemas_sociales' => 'problemas_sociales'
    ],
    "overwrite_user_images" => true,
    'roles' => [
        'morador' => 'morador',
        'invitado' => 'invitado',
        'policia' => 'policia'
    ],
    "ONESIGNAL_APP_ID" => env('ONESIGNAL_APP_ID', ''),
    "ONESIGNAL_REST_API_KEY" => env('ONESIGNAL_REST_API_KEY', 'mysql'),
];
