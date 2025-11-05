    <?php

return [

    /*
    |--------------------------------------------------------------------------
    | Laravel CORS Configuration
    |--------------------------------------------------------------------------
    |
    | Aquí configuramos los orígenes, métodos y encabezados permitidos
    | para que el frontend (Angular, Ionic, etc.) pueda comunicarse
    | con el backend (Laravel) sin errores de CORS.
    |
    */

    'paths' => [
        'api/*',
        'sanctum/csrf-cookie',
        'login',
        'logout',
        'register',
    ],

    'allowed_methods' => ['*'],

    // 👇 SOLO tu frontend, sin usar '*'
    'allowed_origins' => ['http://localhost:4200'],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    // 👇 Esto es CLAVE para Sanctum (manejo de sesión/cookies)
    'supports_credentials' => true,

];
