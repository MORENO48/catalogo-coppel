<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ArticuloController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\CategoriaController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::get('/categoria/{id?}',[CategoriaController::class, 'get']);

Route::post('/usuario',[LoginController::class, 'create']);
Route::post('/login',[LoginController::class, 'login']);
Route::get('/articulo/{id?}',[ArticuloController::class, 'get']);

Route::post('/articulo/caracteristica',[ArticuloController::class, 'addCaracteristica']);
Route::delete('/articulo/caracteristica/{id}',[ArticuloController::class, 'deleteCaracteristica']);

Route::delete('/articulo/{id}',[ArticuloController::class, 'delete']);
Route::put('/articulo/{id}',[ArticuloController::class, 'update']);
Route::post('/articulo',[ArticuloController::class, 'create']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/categorias',[CategoriaController::class, 'get']);
});