<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\Articulo;
use App\Services\Caracteristica;
use Illuminate\Http\Request;

class ArticuloController extends Controller {
    public function get(Request $filtros, $id = null) {
        try {
            $datos = Articulo::get($id,$filtros);
            return response()->json($datos);
        } catch (\Throwable $err) {
            return response()->json(["estatus" => -1, "msj" => $err->getMessage(), "linea" => $err->getLine()],500);
        }
    }

    public function create(Request $request) {
        try {
            $datos = Articulo::create($request);
            return response()->json($datos);
        } catch (\Throwable $err) {
            return response()->json(["estatus" => -1, "msj" => $err->getMessage(), "linea" => $err->getLine()],500);
        }
    }
    
    public function update(Request $request, $id) {
        try {
            $datos = Articulo::update($id, $request);
            return response()->json($datos);
        } catch (\Throwable $err) {
            return response()->json(["estatus" => -1, "msj" => $err->getMessage(), "linea" => $err->getLine()],500);
        }
    }

    public function delete($id) {
        try {
            $datos = Articulo::delete($id);
            return response()->json($datos);
        } catch (\Throwable $err) {
            return response()->json(["estatus" => -1, "msj" => $err->getMessage(), "linea" => $err->getLine()],500);
        }
    }

    public function addCaracteristica(Request $request) {
        try {
            $datos = Caracteristica::create($request);
            return response()->json($datos);
        } catch (\Throwable $err) {
            return response()->json(["estatus" => -1, "msj" => $err->getMessage(), "linea" => $err->getLine()],500);
        }
    }

    public function deleteCaracteristica($id) {
        try {
            $datos = Caracteristica::delete($id);
            return response()->json($datos);
        } catch (\Throwable $err) {
            return response()->json(["estatus" => -1, "msj" => $err->getMessage(), "linea" => $err->getLine()],500);
        }
    }
}