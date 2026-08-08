<?php

declare(strict_types=1);

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Global CORS handling for Vercel serverless environment
$allowedOrigins = [
    'https://saloonerp.vercel.app',
    'http://localhost:5173',
    'http://127.0.0.1:5173',
];
$origin = $_SERVER['HTTP_ORIGIN'] ?? '';
if (in_array($origin, $allowedOrigins, true) || str_ends_with($origin, '.vercel.app')) {
    header("Access-Control-Allow-Origin: {$origin}");
} else {
    header('Access-Control-Allow-Origin: *');
}
header('Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With, X-CSRF-TOKEN, Accept, Origin');
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Max-Age: 86400');

if (($_SERVER['REQUEST_METHOD'] ?? '') === 'OPTIONS') {
    http_response_code(200);
    exit(0);
}

try {
    /*
    |--------------------------------------------------------------------------
    | Maintenance Mode
    |--------------------------------------------------------------------------
    */
    $maintenanceFile = __DIR__ . '/../storage/framework/maintenance.php';

    if (is_file($maintenanceFile)) {
        require $maintenanceFile;
        exit;
    }

    /*
    |--------------------------------------------------------------------------
    | Composer Autoloader
    |--------------------------------------------------------------------------
    */
    $autoloadFile = __DIR__ . '/../vendor/autoload.php';

    if (!is_file($autoloadFile)) {
        throw new RuntimeException(
            'Composer autoloader was not found. Run "composer install".'
        );
    }

    require_once $autoloadFile;

    /*
    |--------------------------------------------------------------------------
    | Bootstrap Laravel
    |--------------------------------------------------------------------------
    */
    $bootstrapFile = __DIR__ . '/../bootstrap/app.php';

    if (!is_file($bootstrapFile)) {
        throw new RuntimeException(
            'Laravel bootstrap file was not found.'
        );
    }

    /** @var Application $app */
    $app = require_once $bootstrapFile;

    if (!$app instanceof Application) {
        throw new RuntimeException(
            'The Laravel application was not initialized correctly.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Handle HTTP Request
    |--------------------------------------------------------------------------
    */
    $request = Request::capture();

    $app->handleRequest($request);
} catch (Throwable $exception) {
    /*
    |--------------------------------------------------------------------------
    | Fallback Error Handling
    |--------------------------------------------------------------------------
    */

    error_log((string) $exception);

    http_response_code(500);
    header('Content-Type: text/plain; charset=UTF-8');

    echo 'An internal server error occurred.';
}