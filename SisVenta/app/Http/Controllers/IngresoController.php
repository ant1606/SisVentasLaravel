<?php

namespace SisVenta\Http\Controllers;

use Illuminate\Http\Request;

use SisVenta\Http\Requests;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use SisVenta\Http\Requests\IngresoFormRequest;
use SisVenta\Ingreso;
use SisVenta\DetalleIngreso;
use DB;

use Carbon\Carbon;
use Response;
use Illuminate\Support\Collection;

class IngresoController extends Controller
{
   public function __construct()
   {
         $this->middleware('auth');
   }
   //Todos estos metodos estan asociados con nuestras rutas resources
   public function index(Request $request)
   {
   		if($request)
   		{
   			$query=trim($request->get('searchText'));
   			$ingresos=DB::table('ingreso as i')
   			->join('persona as p','i.idproveedor','=','p.idpersona')
   			->join('detalle_ingreso as di','i.idingreso','=','di.idingreso')
   			->select('i.idingreso','i.fecha_hora','p.nombre','i.tipo_comprobante','i.serie_comprobante','i.num_comprobante','i.impuesto','i.estado', DB::raw('sum(di.cantidad*di.precio_compra) as total'))/*Obtenemos el subtotal del Ingreso.*/
   			->where('i.num_comprobante','LIKE','%'.$query.'%')
   			->orderBy('i.idingreso','desc')
   			->groupBy('i.idingreso','i.fecha_hora','p.nombre','i.tipo_comprobante','i.serie_comprobante','i.num_comprobante','i.impuesto','i.estado')
   			->paginate(7);
   			return view('compras.ingreso.index',["ingresos"=>$ingresos,"searchText"=>$query]);
   		}
   }
   public function create()
   {
   		//Obtenemos los proveedores
   		$personas=DB::table('persona')->where('tipo_persona','=','Proveedor')->get();
   		//Obtenemos los artículos
   		$articulos=DB::table('articulo as art')
   			->select(DB::raw('CONCAT(art.codigo, " ", art.nombre) AS articulo'),'art.idarticulo')
   			->where('art.estado','=','Activo')
   			->get();
   		return view("compras.ingreso.create",["personas"=>$personas,"articulos"=>$articulos]);
   }
   public function store(IngresoFormRequest $request)
   {
   		try{
   			DB::beginTransaction();
   			$ingreso = new Ingreso;
   			$ingreso->idproveedor=$request->get('idproveedor');
   			$ingreso->tipo_comprobante=$request->get('tipo_comprobante');
   			$ingreso->serie_comprobante=$request->get('serie_comprobante');
   			$ingreso->num_comprobante=$request->get('num_comprobante');

   			/*Obteniendo la hora para agregar a la tabla mediante la clase Carbon*/
   			$mytime = Carbon::now('America/Lima');
   			$ingreso->fecha_hora=$mytime->toDateTimeString();
   			$ingreso->impuesto='18';
   			$ingreso->estado='A';
   			$ingreso->save(); /*Guardamos la cabecera del Ingreso*/

   			/*Guardamos el detalle_ingreso, recordar que este objeto es tratado como un array*/
   			$idarticulo=$request->get('idarticulo');
   			$cantidad=$request->get('cantidad');
   			$precio_compra=$request->get('precio_compra');
   			$precio_venta=$request->get('precio_venta');

   			$cont=0;

   			while($cont < count($idarticulo))
   			{
   				$detalle = new DetalleIngreso();
   				$detalle->idingreso = $ingreso->idingreso;
   				$detalle->idarticulo = $idarticulo[$cont];
   				$detalle->cantidad=$cantidad[$cont];
   				$detalle->precio_compra=$precio_compra[$cont];
   				$detalle->precio_venta=$precio_venta[$cont];
   				$detalle->save();
   				$cont=$cont+1;
   			}

   			DB::commit();

   		}catch(Exception $e)
   		{
   			DB::rollback();
   		}

   		return \Redirect::to('compras\ingreso');
   }
   public function show($id)
   {
   		$ingreso = DB::table('ingreso as i')
   			->join('persona as p','i.idproveedor','=','p.idpersona')
   			->join('detalle_ingreso as di','i.idingreso','=','di.idingreso')
   			->select('i.idingreso','i.fecha_hora','p.nombre','i.tipo_comprobante','i.serie_comprobante','i.num_comprobante','i.impuesto','i.estado',
   				DB::raw('sum(di.cantidad*di.precio_compra) as total'))
   			->where('i.idingreso','=',$id)
   			->groupBy('i.idingreso','i.fecha_hora','p.nombre','i.tipo_comprobante','i.serie_comprobante','i.num_comprobante','i.impuesto','i.estado')
   			->first();

   		$detalles=DB::table('detalle_ingreso as d')
   			->join('articulo as a','d.idarticulo','=','a.idarticulo')
   			->select('a.nombre as articulo','d.cantidad','d.precio_compra','d.precio_venta')
   			->where('d.idingreso','=',$id)
   			->get();

   		return view("compras.ingreso.show",["ingreso"=>$ingreso,"detalles"=>$detalles]);
   }
   public function detroy($id)
   {
	   	$ingreso = Ingreso::findOrFail($id);
	   	$ingreso->estado='C';
	   	$ingreso->update();
	   	return \Redirect::to('compras/ingreso');
   }
}
