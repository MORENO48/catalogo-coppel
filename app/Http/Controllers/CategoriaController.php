<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\Categoria;
use Illuminate\Http\Request;

class CategoriaController extends Controller {
    public function get($id = null) {
        try {
            $datos = Categoria::get($id);
            return response()->json($datos);
        } catch (\Throwable $err) {
            return response()->json(["estatus" => -1, "msj" => $err->getMessage(), "linea" => $err->getLine()],500);
        }
    }
}
