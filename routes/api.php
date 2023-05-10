<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ArticuloController;

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

Route::post('/articulo/caracteristica',[ArticuloController::class, 'addCaracteristica']);
Route::delete('/articulo/caracteristica/{id}',[ArticuloController::class, 'deleteCaracteristica']);

Route::get('/articulo/{id?}',[ArticuloController::class, 'get']);
Route::post('/articulo/{id}',[ArticuloController::class, 'update']);
Route::post('/articulo',[ArticuloController::class, 'create']);
Route::delete('/articulo/{id}',[ArticuloController::class, 'delete']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

