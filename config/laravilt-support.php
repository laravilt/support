<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Plugin Settings
    |--------------------------------------------------------------------------
    |
    | Configure your plugin settings here.
    |
    */

    'enabled' => env('LARAVILT_SUPPORT_ENABLED', true),

    /*
    |--------------------------------------------------------------------------
    | Frontend Stack
    |--------------------------------------------------------------------------
    |
    | The frontend stack the application renders Laravilt with: "vue" or
    | "react". `php artisan laravilt:install` writes LARAVILT_FRONTEND to
    | your .env. When empty, the stack is detected from package.json.
    |
    */

    'frontend' => env('LARAVILT_FRONTEND'),
];
