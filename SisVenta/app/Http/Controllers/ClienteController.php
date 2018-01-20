<?php

namespace SisVenta\Http\Controllers;

use Illuminate\Http\Request;

use SisVenta\Http\Requests;
use SisVenta\Persona;
use Illuminate\Support\Facades\Redirect;
use SisVenta\Http\Requests\PersonaFormRequest;
use DB;

//Se relaciona con el Modelo Persona
class ClienteController extends Controller
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
   			$personas =DB::table('persona')
   				->where('nombre','LIKE','%'.$query.'%')
   				->where('tipo_persona','=','Cliente')
   				->orwhere('num_documento','LIKE','%'.$query.'%')
   				->where('tipo_persona','=','Cliente')
   				->orderBy('idpersona','desc')
   				->paginate(7);

   			//    Vista(Carpeta/Controlador/Pagina, Parametros que se le envia a la vista)
   			return view('ventas.cliente.index',["personas"=>$personas,"searchText"=>$query]);
   		}
   }
   public function create()
   {
   		return view("ventas.cliente.create");
   }
   public function store(PersonaFormRequest $request)
   {
		$persona = new Persona;
		$persona->tipo_persona = 'Cliente';
		$persona->nombre = $request->get('nombre');
		$persona->tipo_documento = $request->get('tipo_documento');
		$persona->num_documento = $request->get('num_documento');
		$persona->direccion = $request->get('direccion');
		$persona->telefono = $request->get('telefono');
		$persona->email = $request->get('email');
		$persona->save();		

      	return \Redirect::to('ventas/cliente');
   }
   public function show($id)
   {
   		return view('ventas.cliente.show',["persona"=>Persona::findOrFail($id)]);
   }
   public function edit($id)
   {
   		return view('ventas.cliente.edit',["persona"=>Persona::findOrFail($id)]);
   }
   public function update(PersonaFormRequest $request, $id)
   {
   		$persona = Persona::findOrFail($id);
		$persona->nombre = $request->get('nombre');
		$persona->tipo_documento = $request->get('tipo_documento');
		$persona->num_documento = $request->get('num_documento');
		$persona->direccion = $request->get('direccion');
		$persona->telefono = $request->get('telefono');
		$persona->email = $request->get('email');
   		$persona->update();
   		return \Redirect::to('ventas/cliente');
   }
   public function destroy($id)
   {
   		$persona = Persona::findOrFail($id);
   		$persona->tipo_persona = 'Inactivo';
   		$persona->update();
   		return \Redirect::to('ventas/cliente');
   }

}
