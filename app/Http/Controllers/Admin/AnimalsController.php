<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\Paginator;
use Carbon\Carbon;
use Session;
use Excel;
use App\Animal;
use App\Historical;

class AnimalsController extends Controller {

    /**
     * Carga los animales para después desplegarlos en la vista "index" correspondiente.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
        // Se toman todos los animales.
        $animals = new Animal();
        // Se relacionan los animales con las razas.
        $animals = $animals
            ->join('breeds', 'animals.breedID', '=', 'breeds.id')
            ->join('farms', 'animals.asocebuFarmID', '=', 'farms.asocebuID')
            ->select('animals.*', 'breeds.name as breedName', 'farms.state');
        // Lista de filtros a aplicar.
        $queries = [];
        /* Los animales siempre están ordenados por el estado de la finca y el asocebuID,
           pero podrían ordenarse con algunos argumentos extra. */
        $orderBy = 'farms.state asc, farms.asocebuID asc';
        if(request()->has('finca')) {
            $asocebuFarmID = request('finca');
            $animals = $animals->where('asocebuFarmID', $asocebuFarmID);
            $queries['finca'] = $asocebuFarmID;
        }
        if(request()->has('sexo')) {
            $orderBy = $orderBy.', sex asc';
            $queries['sexo'] = 'si';
        }
        if(request()->has('raza')) {
            $orderBy = $orderBy.', breedName asc';
            $queries['raza'] = 'si';
        }
        $animals = $animals->orderByRaw($orderBy)->paginate(10)->appends($queries);
        $this->convertDate($animals);
        return view('admin.animals.index', compact('animals'));
    }

    /**
     * Cambia el formato de las fechas de nacimiento de los animales.
     *
     * @return \App\Animal[] $animals
     */
    public function convertDate($animals) {
        // Recorre la tabla de animales.
        foreach($animals as $animal) {
            // Reemplaza el formato de la fecha almacenada.
            $animal->birthdate = Carbon::createFromFormat('Y-m-d', $animal->birthdate)
                ->format('d/m/Y');
        }
    }

    /**
     * Retorna la página con el formulario para cargar animales cargando en el proceso
     * las fincas.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $farms = DB::table('farms')
            ->select('asocebuID', 'name')
            ->orderBy('asocebuID', 'desc')
            ->get();
        return view('admin.animals.create', compact('farms'));
    }

    /**
     * Lee el excel brindado por el usuario para guardar todos los animales en la base de datos.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $saved = 0;
        $failed = 0;
        $asocebuID = $request['farm'];
        // Se toma el directorio temporal donde se almacena el archivo en el servidor.
        $filePath = $request->file('excel')->getRealPath();
        // Se carga el archivo en formato Excel desde el directorio donde se encuentra y se lee.
        Excel::load($filePath, function($reader) use ($asocebuID, &$saved, &$failed) {
            // Se itera sobre cada fila del Excel.
            $reader->each(function($row) use ($asocebuID, &$saved, &$failed) {
                // Se busca el ID de la "raza" basado en el texto encontrado en dicho espacio del archivo.
                $breedID = DB::table('breeds')
                    ->whereRaw('LOWER(`name`) = ?', [strtolower($row['raza'])])
                    ->pluck('id')
                    ->first();
                // Si no se encuentra la raza del animal se pondrá 'desconocida'.
                if(!$breedID){
                    $breedID = 0;
                }
                try {
                    // Se almacena el animal en la base de datos.
                    Animal::create([
                        'asocebuFarmID' => $asocebuID,
                        'breedID' => $breedID,
                        'register' => $row['registro'],
                        'code' => $row['codigo'],
                        'sex' => strtolower($row['sx']),
                        'birthdate' => $row['fec.nac.'],
                        'fatherRegister' => $row['reg.padre'],
                        'fatherCode' => $row['cod.padre'],
                        'motherRegister' => $row['reg.madre'],
                        'motherCode' => $row['cod.madre']
                    ]);
                    $saved++;
                }
                catch(QueryException $exception) {
                    $failed++;
                }
            });
        });
        // Se guarda un registro en el historial referente a la acción realizada.
        Historical::create([
            'userID' => Auth::id(),
            'typeID' => 5,
            'datetime' => Carbon::now('America/Costa_Rica'),
            'description' => 'Se registraron '.$saved.' animales en '.$asocebuID.', '.$failed.' fallos'
        ]);
        $state = 'Listo';
        $message = 'Se registraron '.$saved.' animales en la finca con ID: '.$asocebuID;
        $alert_class = 'alert-success';
        if($saved == 0) {
            $state = 'Error';
            $message = 'No se pudo registrar ninguno de los animales del archivo, probablemente están repetidos';
            $alert_class = 'alert-danger';
        }
        elseif($failed > 0) {
            $state = 'Atención';
            $message = 'Se registraron '.$saved.' animales y '.$failed.' fallaron, probablemente están repetidos';
            $alert_class = 'alert-warning';
        }
        // Se genera la alerta para informar al usuario acerca del éxito/parcial/fracaso del proceso.
        Session::flash('state', $state);
        Session::flash('message', $message);
        Session::flash('alert_class', $alert_class);
        return redirect()->route('admin.animales.index');
    }
}
