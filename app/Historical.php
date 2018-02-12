<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Historical extends Model {
    // Para que Eloquent no asuma los atributos "created_at" y "updated_at".
    public $timestamps = false;

    /**
     * Atributos que son asignables.
     *
     * @var array
     */
    protected $fillable = [
        'userID',
        'typeID',
        'datetime',
        'description'
    ];

    /**
     * Atributos que no pueden cambiar.
     *
     * @var array
     */
     protected $guarded = [
         'id',
         'userID',
         'typeID',
         'datetime',
         'description'
     ];

    /**
     * Atributos que deben ser ocultos para arreglos.
     *
     * @var array
     */
    protected $hidden = [
        'typeID'
    ];

    // Se asocia un miembro del historial con el usuario responsable de la acción.
    public function user() {
        $this->hasOne('App\User', 'id', 'userID');
    }
}
