<?php

namespace SisVenta;

use Illuminate\Database\Eloquent\Model;

class DetalleVenta extends Model
{
    //Referencciando la tabla a usar por el modelo
    protected $table ='detalle_venta';

    protected $primaryKey ='iddetalle_venta';

    //Campo generado por laravel para la fecha y hora de actualización de registros
    public $timestamps = false;

    //Campos que se asignan al modelo
    protected $fillable = [
    	'idventa',
    	'idarticulo',
    	'idcantidad',
    	'precio_venta',
    	'descuento'
    ];

    protected $guarded = [

    ];
}
