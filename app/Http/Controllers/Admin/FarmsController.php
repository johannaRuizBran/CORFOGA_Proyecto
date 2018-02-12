<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\QueryException;
use Carbon\Carbon;
use Validator;
use Session;
use Redirect;
use App\Farm;
use App\User;
use App\Region;
use App\Historical;

class FarmsController extends Controller {

    /**
     * Carga las fincas según el "asocebuID" de manera descendente para después
     * desplegarlos en la vista "index" correspondiente.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Se toman todas las fincas.
        $farms = new Farm();
        // Se relacionan las fincas con los productores y las regiones.
        $farms = $farms
            ->join('users', 'farms.userID', '=', 'users.id')
            ->join('regions', 'farms.regionID', '=', 'regions.id')
            ->select('farms.asocebuID', 'users.identification', 'users.id as userID',
                DB::raw('CONCAT(users.name, " ", users.lastName) as userFullName'),
                'users.state as userState', 'regions.name as regionName',
                'farms.name as farmName', 'farms.state as farmState');
        // Lista de filtros a aplicar.
        $queries = [];
        /* Las fincas siempre están ordenadas por el estado de la finca y el asocebuID,
           pero podrían ordenarse con algunos argumentos extra. */
        $orderBy = 'farms.state asc';
        if(request()->has('region')) {
            $orderBy = $orderBy.',regionName asc';
            $queries['region'] = 'si';
        }
        if(request()->has('nombre')) {
            $orderBy = $orderBy.',farmName asc';
            $queries['nombre'] = 'si';
        }
        if(request()->has('dueno')) {
            $owner = request('dueno');
            $farms = $farms->where(DB::raw('CONCAT(users.name, " ", users.lastName)'), 'like', '%'.$owner.'%');
            $queries['dueno'] = $owner;
        }
        $farms = $farms->orderByRaw($orderBy)->paginate(10)->appends($queries);
        return view('admin.farms.index', compact('farms'));
    }

    /**
     * Retorna la página con el formulario para crear fincas cargando en el proceso
     * los productores y las regiones.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $producers = DB::table('users')
            ->select('id', 'identification', DB::raw('CONCAT(name, " ", lastName) as fullName'))
            ->where('role', 'p')
            ->get();
        $regions = Region::all();
        return view('admin.farms.create', compact('producers', 'regions'));
    }

    /**
     * Realiza validaciones para el almacenaje de una nueva finca.
     * Si la información es correcta se almacena la finca, sino, se informa al usuario.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Se crea un "Validator" con las reglas definidas para cada uno de los atributos.
        $validator = Validator::make($request->all(), [
            'asocebuID' => 'required|regex:/^[0-9]{4}$/|unique:farms',
            'name' => 'required|string|max:100'
        ]);
        // Si la validación de algunos de los datos falla, se informa al usuario.
        if($validator->fails()) {
            return Redirect::to('admin/usuarios/create')->withErrors($validator)->withInput();
        }
        else {
            $state = 'Listo';
            $message = 'La finca fue creada exitosamente.';
            $alert_class = 'alert-success';
            try {
                // Se almacena la finca en la base de datos.
                Farm::create([
                    'asocebuID' => $request['asocebuID'],
                    'userID' => $request['owner'],
                    'regionID' => $request['region'],
                    'name' => $request['name']
                ]);
                // Se guarda un registro en el historial referente a la acción realizada.
                Historical::create([
                    'userID' => Auth::id(),
                    'typeID' => 3,
                    'datetime' => Carbon::now('America/Costa_Rica'),
                    'description' => 'Se creó la finca con ID: '.$request['asocebuID']
                ]);
            }
            catch(QueryException $exception) {
                $state = 'Error';
                $message = 'No se pudo crear la finca.';
                $alert_class = 'alert-danger';
            }
            // Se genera la alerta para informar al usuario acerca del éxito/fracaso del proceso.
            Session::flash('state', $state);
            Session::flash('message', $message);
            Session::flash('alert_class', $alert_class);
            return redirect()->route('admin.fincas.index');
        }
    }

    /**
     * Busca una finca por el "id" y luego lo retorna en una vista para su edición.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $farm = Farm::find($id);
        $producers = DB::table('users')
            ->select('id', 'identification', DB::raw('CONCAT(name, " ", lastName) as fullName'))
            ->where('role', 'p')
            ->get();
        $region = Region::find($farm->regionID);
        return view('admin.farms.show', compact('farm', 'producers', 'region'));
    }

    /**
     * Permite activar o desactivar una finca.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $state = 'Listo';
        $alert_class = 'alert-success';
        $farm = Farm::find($id);
        try{
            if($farm->state == '1'){
                $farm->state = '0';
                $message = 'La finca fue desactivada exitosamente.';
                $typeID = 10;
                $description = 'Se desactivó la finca con ID: '.$id;
            }
            else{
                $farm->state = '1';
                $message = 'La finca fue activada exitosamente.';
                $typeID = 9;
                $description = 'Se activó la finca con ID: '.$id;
            }
            $farm->save();
            // Se guarda un registro en el historial referente a la acción realizada.
            Historical::create([
                'userID' => Auth::id(),
                'typeID' => $typeID,
                'datetime' => Carbon::now('America/Costa_Rica'),
                'description' => $description
            ]);
        }
        catch(QueryException $exception){
            $state = 'Error';
            $message = 'La finca no pudo ser actualizada.';
            $alert_class = 'alert-warning';
        }
        Session::flash('state', $state);
        Session::flash('message', $message);
        Session::flash('alert_class', $alert_class);
        return redirect()->route('admin.fincas.index');
    }

    /**
     * Actualiza una finca recién editada.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $farm = Farm::find($id);
        // Se crea un "Validator" con las reglas definidas para cada uno de los atributos.
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100'
        ]);
        // Si la validación de algunos de los datos falla, se informa al usuario.
        if($validator->fails()) {
            return Redirect::to('admin/fincas/'.$id.'/edit')->withErrors($validator)->withInput();
        }
        else {
            $state = 'Listo';
            $message = 'La finca fue editada exitosamente.';
            $alert_class = 'alert-success';
            try {
                // Se actualiza la finca con los nuevos valores.
                $farm->name = $request['name'];
                $farm->userID = $request['owner'];
                $farm->save();
                // Se guarda un registro en el historial referente a la acción realizada.
                Historical::create([
                    'userID' => Auth::id(),
                    'typeID' => 4,
                    'datetime' => Carbon::now('America/Costa_Rica'),
                    'description' => 'Se editó la finca con ID: '.$farm->asocebuID
                ]);
            }
            catch(QueryException $exception) {
                $state = 'Error';
                $message = 'No se pudo editar la finca.';
                $alert_class = 'alert-danger';
            }
            // Se genera la alerta para informar al usuario acerca del éxito/fracaso del proceso.
            Session::flash('state', $state);
            Session::flash('message', $message);
            Session::flash('alert_class', $alert_class);
            return redirect()->route('admin.fincas.index');
        }
    }
}
