<?php

declare(strict_types=1);

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

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