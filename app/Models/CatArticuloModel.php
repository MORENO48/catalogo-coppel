<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class CatArticuloModel extends Model {
    protected $table = 'cat_articulos';
    protected $primaryKey = 'id';
    public $timestamps  = true;
    protected $fillable = ['nombre','status'];
}