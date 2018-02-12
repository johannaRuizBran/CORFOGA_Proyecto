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
use App\User;
use App\Farm;
use App\Historical;

class UsersController extends Controller {

    /**
     * Carga los usuarios para después desplegarlos en la vista "index" correspondiente.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        // Se toman todos los usuarios.
        $users = new User();
        // Lista de filtros a aplicar.
        $queries = [];
        /* Los usuarios siempre están ordenados por el estado, pero podrían ordenarse
           con algunos argumentos extra. */
        $orderBy = 'state asc';
        if(request()->has('nombre')) {
            $name = request('nombre');
            $users = $users->where(DB::raw('CONCAT(name, " ", lastName)'), 'like', '%'.$name.'%');
            $queries['nombre'] = $name;
        }
        if(request()->has('rol')) {
            $orderBy = $orderBy.', role asc';
            $queries['rol'] = 'si';
        }
        $users = $users->orderByRaw($orderBy)->paginate(10)->appends($queries);
        return view('admin.users.index', compact('users'));
    }

    /**
     * Retorna la página con el formulario para crear usuarios.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        return view('admin.users.create');
    }

    /**
     * Realiza validaciones para el almacenaje de un nuevo usuario.
     * Si la información es correcta se almacena el usuario, sino, se informa al usuario.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        /* El "identificationRegex" puede cambiar dependiendo del tipo de entidad
        que se quiera crear. */
        $identificationRegex = '/^[1-7]-[0-9]{4}-[0-9]{4}$/';
        if($request['type'] == 'l'){
            $identificationRegex = '/^3-[1-9]{3}-[1-9]{6}$/';
        }
        // Se crea un "Validator" con las reglas definidas para cada uno de los atributos.
        $validator = Validator::make($request->all(), [
            'identification' => 'required|regex:'.$identificationRegex.'|unique:users',
            'name' => 'required|string|max:30',
            'lastName' => 'required|string|max:30',
            'password' => 'required|string|min:8|confirmed',
            'email' => 'required|string|email|max:255|unique:users',
            'phoneNumber' => 'required|regex:/^[0-9]{4}-[0-9]{4}$/',
        ]);
        // Si la validación de algunos de los datos falla, se informa al usuario.
        if($validator->fails()) {
            return Redirect::to('admin/usuarios/create')->withErrors($validator)->withInput();
        }
        else {
            $state = 'Listo';
            $message = 'El usuario fue creado exitosamente.';
            $alert_class = 'alert-success';
            try {
                // Se almacena el usuario en la base de datos.
                User::create([
                    'identification' => $request['identification'],
                    'name' => $request['name'],
                    'lastName' => $request['lastName'],
                    'password' => bcrypt($request['password']),
                    'email' => $request['email'],
                    'phoneNumber' => $request['phoneNumber'],
                    'role' => $request['role']
                ]);
                // Se guarda un registro en el historial referente a la acción realizada.
                Historical::create([
                    'userID' => Auth::id(),
                    'typeID' => 1,
                    'datetime' => Carbon::now('America/Costa_Rica'),
                    'description' => 'Se creó el usuario con cédula: '.$request['identification']
                ]);
            }
            catch(QueryException $exception) {
                $state = 'Error';
                $message = 'No se pudo crear el usuario.';
                $alert_class = 'alert-danger';
            }
            // Se genera la alerta para informar al usuario acerca del éxito/fracaso del proceso.
            Session::flash('state', $state);
            Session::flash('message', $message);
            Session::flash('alert_class', $alert_class);
            return redirect()->route('admin.usuarios.index');
        }
    }

    /**
     * Busca un usuario por el "id" y luego lo retorna en una vista para su edición.
     * Si el usuario es un productor, se buscan sus fincas, si tiene alguna entonces
     * se notifica a través de la variable "anyFarm".
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        $user = User::find($id);
        $anyFarm = false;
        if($user->role == 'p') {
            $farmsCount = Farm::where('userID', $user->id)->count();
            if($farmsCount > 0){
                $anyFarm = true;
            }
        }
        return view('admin.users.show', compact('user', 'anyFarm'));
    }

    /**
     * Permite activar o desactivar un usuario y sus fincas.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        $state = 'Listo';
        $alert_class = 'alert-success';
        $user = User::find($id);
        try{
            if($user->state == '1'){
                $user->state = '0';
                $message = 'El usuario fue desactivado exitosamente.';
                $typeID = 8;
                $description = 'Se desactivó el usuario con cédula: '.$user->identification;
            }
            else{
                $user->state = '1';
                $message = 'El usuario fue activado exitosamente.';
                $typeID = 7;
                $description = 'Se activó el usuario con cédula: '.$user->identification;
            }
            // Si el usuario es un productor será necesario activar/desactivar sus fincas.
            if($user->role == 'p'){
                foreach (Farm::where('userID', $id)->cursor() as $farm) {
                    if($farm->state != $user->state) {
                        $farm->state = $user->state;
                        $farm->save();
                    }
                }
            }
            $user->save();
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
            $message = 'El usuario no pudo ser editado.';
            $alert_class = 'alert-warning';
        }
        Session::flash('state', $state);
        Session::flash('message', $message);
        Session::flash('alert_class', $alert_class);
        return redirect()->route('admin.usuarios.index');
    }

    /**
     * Actualiza un usuario recién editado.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Las reglas iniciales para los atributos.
        $rules = [
            'name' => 'required|string|max:30',
            'lastName' => 'required|string|max:30',
            'password' => 'required|string|min:8|confirmed',
            'phoneNumber' => 'required|regex:/^[0-9]{4}-[0-9]{4}$/',
        ];
        $user = User::find($id);
        /* Si el correo cambió se agrega la regla para validarlo, esto para evitar un
        choque consigo mismo en caso de no haber sido cambiado. */
        if($user->email != $request['email']){
            array_push($rules, ['email' => 'required|string|email|max:255|unique:users']);
        }
        // Se crea un "Validator" con las reglas definidas para cada uno de los atributos.
        $validator = Validator::make($request->all(), $rules);
        // Si la validación de algunos de los datos falla, se informa al usuario.
        if($validator->fails()) {
            return Redirect::to('admin/usuarios/'.$id.'/edit')->withErrors($validator)->withInput();
        }
        else {
            $state = 'Listo';
            $message = 'El usuario fue editado exitosamente.';
            $alert_class = 'alert-success';
            try {
                // Se actualiza el usuario con los nuevos valores.
                $user->name = $request['name'];
                $user->lastName = $request['lastName'];
                $user->password = bcrypt($request['password']);
                $user->email = $request['email'];
                $user->phoneNumber = $request['phoneNumber'];
                $user->save();
                // Se guarda un registro en el historial referente a la acción realizada.
                Historical::create([
                    'userID' => Auth::id(),
                    'typeID' => 2,
                    'datetime' => Carbon::now('America/Costa_Rica'),
                    'description' => 'Se editó el usuario con cédula: '.$user->identification
                ]);
            }
            catch(QueryException $exception) {
                $state = 'Error';
                $message = 'No se pudo editar el usuario.';
                $alert_class = 'alert-danger';
            }
            // Se genera la alerta para informar al usuario acerca del éxito/fracaso del proceso.
            Session::flash('state', $state);
            Session::flash('message', $message);
            Session::flash('alert_class', $alert_class);
            return redirect()->route('admin.usuarios.index');
        }
    }
}
