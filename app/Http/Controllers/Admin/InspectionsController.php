<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\Paginator;
use Carbon\Carbon;
use Excel;
use Session;
use App\Inspection;
use App\Detail;
use App\User;
use App\Farm;
use App\Historical;

class InspectionsController extends Controller {

    /**
     * Carga las inspecciones para después desplegarlos en la vista "index" correspondiente.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $inspections = new Inspection();
        // Se relacionan las fincas con los inspectores.
        $inspections = $inspections
            ->join('users', 'inspections.userID', '=', 'users.id')
            ->select('inspections.*', 'users.identification', 'users.id as userID',
                DB::raw('CONCAT(users.name, " ", users.lastName) as userFullName'));
        // Lista de filtros a aplicar.
        $queries = [];
        /* Las inspecciones siempre están ordenadas por fecha, pero podrían ordenarse
           con algunos argumentos extra. */
        $orderBy = 'inspections.datetime desc';
        if(request()->has('finca')) {
            $asocebuFarmID = request('finca');
            $animals = $inspections->where('asocebuFarmID', $asocebuFarmID);
            $queries['finca'] = $asocebuFarmID;
        }
        if(request()->has('inspectores')) {
            $orderBy = 'CONCAT(users.name, " ", users.lastName) desc,'.$orderBy;
            $queries['inspectores'] = 'si';
        }
        $inspections = $inspections->orderByRaw($orderBy)->paginate(10)->appends($queries);
        $this->convertDate($inspections);
        return view('admin.inspections.index', compact('inspections'));
    }

    /**
     * Cambia el formato de las fechas de las inspecciones.
     *
     * @return \App\Inspection[] $inspections
     */
    public function convertDate($inspections) {
        // Recorre la tabla del historial.
        foreach($inspections as $inspection) {
            // Reemplaza la fecha almacenada de la acción por una con formato más amigable.
            $inspection->datetime = Carbon::createFromFormat('Y-m-d H:i:s', $inspection->datetime)
                ->format('d/m/Y ─ h:i a');
        }
    }

    /**
     * Retorna la página con el formulario para crear inspecciones cargando en el proceso
     * los inspectores y las fincas.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $inspectors = DB::table('users')
            ->select('id', 'identification', DB::raw('CONCAT(name, " ", lastName) as fullName'))
            ->where('role', 'i')
            ->get();
        $farms = Farm::all();
        $visitNumbers = [1, 2, 3, 4]; // Esta lista debería ser calculada (ver requerimentos).
        return view('admin.inspections.create', compact('inspectors', 'farms', 'visitNumbers'));
    }

    /**
     * Inserta una inspección creada desde la aplicación web, los detalles de la misma
     * vienen en un Excel.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $saved = 0;
        $failed = 0;
        $asocebuFarmID = $request['farm'];
        $inspector = $request['inspector'];
        $visitNumber = $request['visitNumber'];
        $inspection = Inspection::create([
            'asocebuFarmID' => $asocebuFarmID,
            'userID' => $inspector,
            'datetime' => $request['datetime'],
            'visitNumber' => $visitNumber
        ]);
        $inspectionID = $inspection->id;
        // Se toma el directorio temporal donde se almacena el archivo en el servidor.
        $filePath = $request->file('excel')->getRealPath();
        // Se carga el archivo en formato Excel desde el directorio donde se encuentra y se lee.
        Excel::load($filePath, function($reader) use ($inspectionID, &$saved, &$failed) {
            // Se itera sobre cada fila del Excel.
            $reader->each(function($row) use ($inspectionID, &$saved, &$failed) {
                // Se busca el ID del "método de alimentación" basado en el texto encontrado en dicho espacio del archivo.
                $feedingMethodID = DB::table('feeding_methods')
                    ->whereRaw('LOWER(`name`) = ?', [strtolower($row['met.ali.'])])
                    ->pluck('id')
                    ->first();
                // Si no se encuentra el método de alimentación del animal se pondrá 'desconocido'.
                if(!$feedingMethodID){
                    $feedingMethodID = 0;
                }
                try {
                    // Se almacena el detalle en la base de datos.
                    Detail::create([
                        'inspectionID' => $inspectionID,
                        'animalID' => $row['registro'],
                        'feedingMethodID' => $feedingMethodID,
                        'weight' => $row['peso'],
                        'scrotalCircumference' => $row['cir.esc.'],
                        'observations' => $row['observaciones']
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
            'userID' => $inspector,
            'typeID' => 6,
            'datetime' => Carbon::now('America/Costa_Rica'),
            'description' => 'Inspección número '.$visitNumber.' terminada en la finca: '.$asocebuFarmID.' con un total de '.$saved.' animales examinados, '.$failed.' fallos'
        ]);
        $state = 'Listo';
        $message = 'Se registró la inspección sin problemas';
        $alert_class = 'alert-success';
        if($saved == 0) {
            $state = 'Error';
            $message = 'No pudieron guardarse detalles de la inspección';
            $alert_class = 'alert-danger';
        }
        elseif($failed > 0) {
            $state = 'Atención';
            $message = 'La inspección se guardó, sin embargo se encontraron algunos problemas con los detalles de la misma';
            $alert_class = 'alert-warning';
        }
        // Se genera la alerta para informar al usuario acerca del éxito/parcial/fracaso del proceso.
        Session::flash('state', $state);
        Session::flash('message', $message);
        Session::flash('alert_class', $alert_class);
        return redirect()->route('admin.inspecciones.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
