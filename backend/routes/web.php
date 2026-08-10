<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

Route::get('/', function () {
    return response()->json([
        'success' => true,
        'message' => 'Beauty Salon ERP API Server is running',
    ]);
});

Route::get('/run-seed', function () {
    // Simple security token check
    if (request('secret') !== 'salon-erp-seed-2026') {
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    try {
        // Run full DatabaseSeeder — CompanySeeder depends on Country/Emirate/City seeders
        Artisan::call('db:seed', [
            '--force' => true, // Required in production
        ]);

        return response()->json([
            'success' => true,
            'message' => 'DatabaseSeeder executed successfully!',
            'output'  => Artisan::output(),
        ]);
    } catch (\Throwable $e) {
        return response()->json([
            'success' => false,
            'message' => $e->getMessage(),
            'trace'   => $e->getTraceAsString(),
        ], 500);
    }
});