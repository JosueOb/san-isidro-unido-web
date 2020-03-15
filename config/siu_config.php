<?php 

return [
    'categorias' => [
        'emergencias' => 'emergencia',
        'reportes' => 'informe',
        'eventos' => 'evento',
        'problemas_sociales' => 'problema_social'
    ],
    "overwrite_user_images" => true,
    'roles' => [
        'morador' => 'morador',
        'invitado' => 'invitado',
        'policia' => 'policia'
    ],
    "ONESIGNAL_APP_ID" => env('ONESIGNAL_APP_ID', ''),
    "ONESIGNAL_REST_API_KEY" => env('ONESIGNAL_REST_API_KEY', 'mysql'),
    "API_IMAGES_DISK" => env('API_IMAGES_DISK', "images")
];
