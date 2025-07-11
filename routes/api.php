<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;

// Ruta de ejemplo: obtener el usuario autenticado (si hay token)
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/users', [UserController::class, 'index']); // Listado de todos los usuarios
Route::get('/users/{user}', [UserController::class, 'show']); // Información detallada de un usuario por ID
