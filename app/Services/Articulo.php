<?php

namespace App\Services;

use App\Models\ArticuloModel;
use App\Models\CaracteristicaModel;
use Exception;

class Articulo {
    static function get($id = null, $filtros = []) {
        //busqueda por id
        if ($id) {
            $datos = ArticuloModel::with(['categoria','caracteristicas'])->select('id','codigo','nombre','cat_id','status','created_at')
                ->where('id',$id)
                ->where('status',1)
                ->first();  

            return $datos;
        }

        //busqueda por filtros
        $datos = ArticuloModel::with(['categoria','caracteristicas'])->select('id','codigo','nombre','cat_id','status','created_at')
            ->where('status',1);
        
        if ($filtros['codigo']) {
            $datos = $datos->where('codigo',$filtros['codigo']);
        }

        if ($filtros['nombre']) {
            $datos = $datos->where('nombre','like','%'.$filtros['nombre'].'%');
        }

        if ($filtros['categoria_id']) {
            $datos = $datos->where('cat_id',$filtros['categoria_id']);
        }

        $datos = $datos->get();
        return $datos;
    }

    static function create($parametros) {
        //validar si existe codigo
        $existe = ArticuloModel::where('codigo',$parametros['codigo'])
            ->count();
        
        if ($existe > 0) {
            return ['estatus' => 1, 'msj' => 'Codigo ya registrado'];
        }

        //crear articulo
        $articulo = new ArticuloModel;
        $articulo->codigo = $parametros['codigo'];
        $articulo->nombre = $parametros['nombre'];
        $articulo->cat_id = $parametros['cat_id'];
        $articulo->status = 1;
        $articulo->save();

        //agregar caracteristicas
        if ($parametros['caracteristicas'] && sizeof($parametros['caracteristicas']) > 0) {
            foreach ($parametros['caracteristicas'] as $key => $caract) {
                $caract_new = new CaracteristicaModel;
                $caract_new->nombre = $caract['nombre'];
                $caract_new->valor = $caract['valor'];
                $caract_new->status = 1;
                $caract_new->articulo_id = $articulo->id;
                $caract_new->save();
            }
        }

        $articulo->caracteristicas;
        $articulo->categoria;

        return $articulo;
    }

    static function update($id, $parametros) {
        $articulo = ArticuloModel::where('status',1)
            ->find($id);
        
        if (!$articulo) {
            return ['estatus' => 1, 'msj' => 'No se encontro articulo'];
        }

        $articulo->nombre = $parametros->nombre;
        $articulo->cat_id = $parametros->cat_id;
        $articulo->save();

        $articulo->caracteristicas;
        $articulo->categoria;

        return $articulo;
    }

    static function delete($id) {
        $articulo = ArticuloModel::where('status',1)
            ->find($id);
        
        if (!$articulo) {
            return ['estatus' => 1, 'msj' => 'No se encontro articulo'];
        }

        $articulo->status = 0;
        $articulo->save();

        return ['estatus' => 0, 'msj' => 'Articulo eliminado'];
    }
}
