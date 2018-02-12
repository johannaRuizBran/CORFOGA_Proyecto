<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Carbon\Carbon;
use App\Farm;
use App\Animal;
use App\FeedingMethod;
use App\Inspection;
use App\Detail;
use App\Historical;

class ApiController extends Controller {

    /**
     * Retorna las fincas de una región específica en un JSON para la aplicación móvil.
     * Si la región especificada es '0', se retornan las fincas correspondientes a todas las regiones.
     * @param region el identificador de la región solicitada o 0 si no se especificó.
     */
    public function getFarmsByRegion($regionID) {
        if($regionID == '0') {
            return response()->json(Farm::all(), 200, [], JSON_UNESCAPED_UNICODE);
        }
        else {
            return response()->json(Farm::where('regionID', $regionID)->get(), 200, [], JSON_UNESCAPED_UNICODE);
        }
    }

    // Retorna los animales de una finca específica en un JSON para la aplicación móvil.
    public function getAnimalsByFarm($asocebuFarmID) {
        return response()->json(Animal::where('asocebuFarmID', $asocebuFarmID)->get(), 200, [], JSON_UNESCAPED_UNICODE);
    }

    // Retorna los métodos de alimentación en un JSON para la aplicación móvil.
    public function getFeedingMethods() {
        return response()->json(FeedingMethod::all(), 200, [], JSON_UNESCAPED_UNICODE);
    }

    // Recibe el request para la creación de una nueva inspección.
    public function createInspection(Request $request) {
        $jsonInspection = json_decode($request->getContent(), true);
        $asocebuFarmID = $jsonInspection['asocebuFarmID'];
        $userID = $jsonInspection['inspector'];
        $visitNumber = $jsonInspection['visitNumber'];
        $inspection = Inspection::create([
            'asocebuFarmID' => $asocebuFarmID,
            'userID' => $userID,
            'datetime' => $jsonInspection['datetime'],
            'visitNumber' => $visitNumber
        ]);
        $inspectionID = $inspection->id;
        $animalCount = 0;
        foreach($jsonInspection['details'] as $jsonDetail) {
            Detail::create([
                'inspectionID' => $inspectionID,
                'animalID' => $jsonDetail['animalID'],
                'feedingMethodID' => $jsonDetail['feedingMethodID'],
                'weight' => $jsonDetail['weight'],
                'scrotalCircumference' => $jsonDetail['scrotalCircumference'],
                'observations' => $jsonDetail['observations']
            ]);
            $animalCount++;
        }
        // Se guarda un registro en el historial referente a la acción realizada.
        Historical::create([
            'userID' => $userID,
            'typeID' => 6,
            'datetime' => Carbon::now('America/Costa_Rica'),
            'description' => 'Inspección número '.$visitNumber.' terminada en la finca: '.$asocebuFarmID.' con un total de '.$animalCount.' animales examinados'
        ]);
        return response()->json(['message' => 'Se ha insertado el reporte y sus detalles correspondientes'], 200);
    }
}
