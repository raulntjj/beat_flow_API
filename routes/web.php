<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return [
        'Laravel' => app()->version(),
        'API' => 'API REST Social Media'
    ];
});

require __DIR__.'/auth.php';
