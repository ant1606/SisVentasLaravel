<?php

namespace SisVenta;

use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    //Referencciando la tabla a usar por el modelo
    protected $table ='persona';

    protected $primaryKey ='idpersona';

    //Campo generado por laravel para la fecha y hora de actualización de registros
    public $timestamps = false;

    //Campos que se asignan al modelo
    protected $fillable = [
    	'tipo_persona',
    	'nombre',
    	'tipo_documento',
    	'num_documento',
    	'direccion',
    	'telefono',
    	'email'
    ];

    protected $guarded = [

    ];
}
