<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HandlerController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('handle', [HandlerController::class, 'handle']);

require __DIR__.'/api.php';
