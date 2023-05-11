<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\Articulo;
use App\Services\Caracteristica;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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
            $validator = Validator::make($request->all(), [
                'codigo' => 'required|numeric|min:1',
                'nombre' => 'required|max:20',
                'cat_id' => 'required|numeric|min:1',
                'caracteristicas' => ''
            ]);

            if ($validator->fails()) {
                $errors = $validator->errors();
                return response()->json(["estatus" => -1, "msj" => $errors],400);
            }

            $datos = Articulo::create($request);
            return response()->json($datos);
        } catch (\Throwable $err) {
            return response()->json(["estatus" => -1, "msj" => $err->getMessage(), "linea" => $err->getLine()],500);
        }
    }
    
    public function update(Request $request, $id) {
        try {
            $validator = Validator::make($request->all(), [
                'nombre' => 'required|max:20',
                'cat_id' => 'required|numeric|min:1'
            ]);

            if ($validator->fails()) {
                $errors = $validator->errors();
                return response()->json(["estatus" => -1, "msj" => $errors],400);
            }

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
            $validator = Validator::make($request->all(), [
                'nombre' => 'required|max:20',
                'valor' => 'required|max:30',
                'articulo_id' => 'required|numeric|min:1'
            ]);

            if ($validator->fails()) {
                $errors = $validator->errors();
                return response()->json(["estatus" => -1, "msj" => $errors],400);
            }

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