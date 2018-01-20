<?php

namespace SisVenta;

use Illuminate\Database\Eloquent\Model;

class Ingreso extends Model
{
    //Referencciando la tabla a usar por el modelo
    protected $table ='ingreso';

    protected $primaryKey ='idingreso';

    //Campo generado por laravel para la fecha y hora de actualización de registros
    public $timestamps = false;

    //Campos que se asignan al modelo
    protected $fillable = [
    	'idproveedor',
    	'tipo_comprobante',
    	'serie_comprobante',
    	'num_comprobante',
    	'fecha_hora',
    	'impuesto',
    	'estado'
    ];

    protected $guarded = [

    ];
}
