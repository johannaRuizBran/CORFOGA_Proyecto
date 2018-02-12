<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Animal extends Model {
    // Para que Eloquent no asuma los atributos "created_at" y "updated_at".
    public $timestamps = false;

    /**
     * Atributos que son asignables.
     *
     * @var array
     */
    protected $fillable = [
        'asocebuFarmID',
        'breedID',
        'register',
        'code',
        'sex',
        'birthdate',
        'fatherRegister',
        'fatherCode',
        'motherRegister',
        'motherCode'
    ];

    /**
     * Atributos que no pueden cambiar.
     *
     * @var array
     */
     protected $guarded = [
         'id',
         'asocebuFarmID',
         'breedID',
         'register',
         'code',
         'sex',
         'birthdate',
         'fatherRegister',
         'fatherCode',
         'motherRegister',
         'motherCode'
     ];

    /**
     * Atributos que deben ser ocultos para arreglos.
     *
     * @var array
     */
    protected $hidden = [
        'breedID'
    ];

    // Se asocia el animal con los detalles en los que se menciona.
    public function details() {
        $this->hasMany('App\Detail', 'animalID', 'id');
    }
}
