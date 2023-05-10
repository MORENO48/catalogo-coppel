<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class CaracteristicaModel extends Model {
    protected $table = 'caracteristicas';
    protected $primaryKey = 'id';
    public $timestamps  = true;
    protected $fillable = ['nombre','status'];
}