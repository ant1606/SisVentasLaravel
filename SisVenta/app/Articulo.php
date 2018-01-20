<?php

namespace SisVenta;

use Illuminate\Database\Eloquent\Model;

class Articulo extends Model
{
    //Referencciando la tabla a usar por el modelo
    protected $table ='articulo';

    protected $primaryKey ='idarticulo';

    //Campo generado por laravel para la fecha y hora de actualización de registros
    public $timestamps = false;

    //Campos que se asignan al modelo
    protected $fillable = [
    	'idcategoria',
    	'codigo',
    	'nombre',
    	'stock',
    	'descripcion',
    	'imagen',
    	'estado'
    ];

    protected $guarded = [

    ];
}
