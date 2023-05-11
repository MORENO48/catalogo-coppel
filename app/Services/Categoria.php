<?php

namespace App\Services;

use App\Models\CatArticuloModel;
use Exception;

class Categoria {
    static function get($id = null, $filtros = []) {
        //busqueda por id
        if ($id) {
            $datos = CatArticuloModel::select('id','nombre')
                ->where('id',$id)
                ->where('status',1)
                ->first();

            return $datos;
        }

        //Obtener todas las categorias
        $datos = CatArticuloModel::select('id','nombre')
            ->where('status',1)
            ->get();
        return $datos;
    }
}
