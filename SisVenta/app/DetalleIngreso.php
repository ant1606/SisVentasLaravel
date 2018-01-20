<?php

namespace SisVenta;

use Illuminate\Database\Eloquent\Model;

class DetalleIngreso extends Model
{
    //Referencciando la tabla a usar por el modelo
    protected $table ='detalle_ingreso';

    protected $primaryKey ='iddetalle_ingreso';

    //Campo generado por laravel para la fecha y hora de actualización de registros
    public $timestamps = false;

    //Campos que se asignan al modelo
    protected $fillable = [
    	'idingreso',
    	'idarticulo',
    	'cantidad',
    	'precio_compra',
    	'precio_venta'
    ];

    protected $guarded = [

    ];
}
