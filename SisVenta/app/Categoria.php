<?php

namespace SisVenta;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    //Referencciando la tabla a usar por el modelo
    protected $table ='categoria';

    protected $primaryKey ='idcategoria';

    //Campo generado por laravel para la fecha y hora de actualización de registros
    public $timestamps = false;

    //Campos que se asignan al modelo
    protected $fillable = [
    	'nombre',
    	'descripcion',
    	'condicion'
    ];

    protected $guarded = [

    ];
}
