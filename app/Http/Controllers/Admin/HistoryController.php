<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Historical;

class HistoryController extends Controller {

    /**
     * Carga el historial para después desplegarlo en la vista "index" correspondiente.
     * Además, calcula el tiempo que ha pasado desde cada acción del historial hasta la actualidad.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        // Se toma tod el historial.
        $historicals = new Historical();
        // Se relaciona con los usuarios y los tipos.
        $historicals = $historicals
            ->join('users', 'historicals.userID', '=', 'users.id')
            ->join('types', 'historicals.typeID', '=', 'types.id')
            ->orderBy('datetime', 'desc')
            ->select(DB::raw('CONCAT(users.name, " ", users.lastName) as fullName'),
                'types.id as typeID', 'types.name as typeName', 'historicals.description', 'historicals.datetime');
        // Lista de filtros a aplicar.
        $queries = [];
        if(request()->has('responsable')){
            $user = request('responsable');
            $historicals = $historicals->where(DB::raw('CONCAT(users.name, " ", users.lastName)'), 'like', '%'.$user.'%');
            $queries['responsable'] = $user;
        }
        if(request()->has('accion') && request('accion') != 0){
            $action = request('accion');
            $historicals = $historicals->where('types.id', $action);
            $queries['accion'] = $action;
        }
        $historicals = $historicals->paginate(10)->appends($queries);
        $this->convertDate($historicals);
        return view('admin.history', compact('historicals'));
    }

    /**
     * Cambia el formato de las fechas de las acciones.
     *
     * @return \App\Animal[] $animals
     */
    public function convertDate($historicals) {
        // Recorre la tabla del historial.
        foreach($historicals as $historical) {
            // Reemplaza la fecha almacenada de la acción por una con formato más amigable.
            $historical->datetime = Carbon::createFromFormat('Y-m-d H:i:s', $historical->datetime)
                ->format('d/m/Y ─ h:i a');
            /*
            // Obtiene la fecha actual.
            $actualDate = Carbon::now('America/Costa_Rica');
            // Reemplaza la fecha almacenada de la acción por un cálculo de cuantos días, horas y minutos han pasado.
            $historical->datetime = $actualDate
                ->diff(Carbon::createFromFormat('Y-m-d H:i:s', $historical->datetime))
                ->format('Hace %d días %H horas y %I minutos.');
            */
        }
    }
}
