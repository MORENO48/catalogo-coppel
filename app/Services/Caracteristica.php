<?php

namespace App\Services;

use App\Models\ArticuloModel;
use App\Models\CaracteristicaModel;
use Exception;

class Caracteristica {
    static function create($parametros) {
        //validar si existe caracteristica
        $existe = CaracteristicaModel::where('nombre',$parametros['nombre'])
            ->where('articulo_id',$parametros['articulo_id'])
            ->where('status',1)
            ->count();
        
        if ($existe > 0) {
            return ['estatus' => 1, 'msj' => 'Caracteristica ya registrada'];
        }

        //validar si existe articulo
        $existe = ArticuloModel::where('id',$parametros['articulo_id'])
            ->where('status',1)
            ->count();
        
        if ($existe == 0) {
            return ['estatus' => 1, 'msj' => 'Articulo no existe'];
        }

        //crear caracteristica
        $caracteristica = new CaracteristicaModel;
        $caracteristica->nombre = $parametros['nombre'];
        $caracteristica->valor = $parametros['valor'];
        $caracteristica->articulo_id = $parametros['articulo_id'];
        $caracteristica->status = 1;
        $caracteristica->save();

        return $caracteristica;
    }

    static function delete($id) {
        $caracteristica = CaracteristicaModel::where('status',1)
            ->find($id);
        
        if (!$caracteristica) {
            return ['estatus' => 1, 'msj' => 'No se encontro caracteristica'];
        }

        $caracteristica->status = 0;
        $caracteristica->save();

        return ['estatus' => 0, 'msj' => 'Caracteristica eliminado'];
    }
}
