<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Inspection extends Model {
    // Para que Eloquent no asuma los atributos "created_at" y "updated_at".
    public $timestamps = false;

    /**
     * Atributos que son asignables.
     *
     * @var array
     */
    protected $fillable = [
        'asocebuFarmID',
        'userID',
        'datetime',
        'visitNumber'
    ];

    /**
     * Atributos que no pueden cambiar.
     *
     * @var array
     */
     protected $guarded = [
        'id',
        'asocebuFarmID',
        'userID',
        'datetime',
        'visitNumber'
     ];

     // Se asocia la inspección con sus respectivos detalles.
     public function details() {
         $this->hasMany('App\Detail', 'inspectionID', 'id');
     }

     // Se asocia la inspección con su respectiva finca.
     public function farm() {
         $this->hasOne('App\Farm', 'asocebuID', 'asocebuFarmID');
     }

     // Se asocia la inspección con su respectivo inspector.
     public function user() {
         $this->hasOne('App\User', 'id', 'userID');
     }
}
