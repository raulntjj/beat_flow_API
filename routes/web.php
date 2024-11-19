<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return [
        'status' => 'success',
        'response' => [
            'Laravel' => app()->version(),
            'API' => 'BeatFlow API Rest',
            'Documentation' => 'Access /api/documentation'
        ]
    ];
});

Route::fallback(function () {
    return response()->json(['status' => 'failed', 'details' => 'Route not found'], 404);
});


require __DIR__.'/auth.php';
