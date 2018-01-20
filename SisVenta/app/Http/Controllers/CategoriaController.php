<?php

namespace SisVenta\Http\Controllers;

use Illuminate\Http\Request;
use SisVenta\Http\Requests;
use SisVenta\Categoria;
use Illuminate\Support\Facades\Redirect;
use SisVenta\Http\Requests\CategoriaFormRequest;
use DB;

class CategoriaController extends Controller
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
   			//Filtro de Busquedas obtenidas desde el formulario
   			$query=trim($request->get('searchText'));
   			//Obtenemos los datos de la tabla donde le agregamos los parametros de busqueda
   			$categorias =DB::table('categoria')->where('nombre','LIKE','%'.$query.'%')
   				->where('condicion','=','1')
   				->orderBy('idcategoria','desc')
   				->paginate(7);

   			//    Vista(Carpeta/Controlador/Pagina, Parametros que se le envia a la vista)
   			return view('almacen.categoria.index',["categorias"=>$categorias,"searchText"=>$query]);
   		}
   }
   public function create()
   {
   		return view("almacen.categoria.create");
   }
   public function store(CategoriaFormRequest $request)
   {
		$categoria = new Categoria;
		$categoria->nombre = $request->get('nombre');
		$categoria->descripcion = $request->get('descripcion');
		$categoria->condicion = '1';
		$categoria->save();
		//return Redirect::to('almacen/categoria');
      return \Redirect::to('almacen/categoria');
   }
   public function show($id)
   {
   		return view('almacen.categoria.show',["categoria"=>Categoria::findOrFail($id)]);
   }
   public function edit($id)
   {
   		return view('almacen.categoria.edit',["categoria"=>Categoria::findOrFail($id)]);
   }
   public function update(CategoriaFormRequest $request, $id)
   {
   		$categoria = Categoria::findOrFail($id);
   		$categoria->nombre = $request->get('nombre');
   		$categoria->descripcion = $request->get('descripcion');
   		$categoria->update();
   		return \Redirect::to('almacen/categoria');
   }
   public function destroy($id)
   {
   		$categoria = Categoria::findOrFail($id);
   		$categoria->condicion = '0';
   		$categoria->update();
   		return \Redirect::to('almacen/categoria');
   }

}
