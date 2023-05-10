<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class ArticuloModel extends Model {
    protected $table = 'articulos';
    protected $primaryKey = 'id';
    public $timestamps  = true;
    protected $fillable = ['codigo','nombre','cat_id','status'];

    function categoria() {
        return $this->hasOne('App\models\CatArticuloModel', 'id', 'cat_id')->where('status',1)->select('id','nombre');
    }

    function caracteristicas() {
        return $this->hasMany('App\models\CaracteristicaModel', 'articulo_id', 'id')->where('status',1)->select('id','nombre','valor','articulo_id');
    }
}