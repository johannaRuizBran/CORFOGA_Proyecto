<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Farm extends Model {
    // Eloquent asume que el "primaryKey" de la tabla se llama "id", con esto se puede cambiar.
    protected $primaryKey = 'asocebuID';

    /**
     * Atributos que son asignables.
     *
     * @var array
     */
    protected $fillable = [
        'asocebuID',
        'userID',
        'regionID',
        'name'
    ];

    /**
     * Atributos que no pueden cambiar.
     *
     * @var array
     */
     protected $guarded = [
        'asocebuID',
        'regionID'
     ];

    /**
     * Atributos que deben ser ocultos para arreglos.
     *
     * @var array
     */
    protected $hidden = [
        'regionID'
    ];

    // Se asocia la finca con su respectivo usuario.
    public function user() {
        $this->hasOne('App\User', 'id', 'userID');
    }
}
