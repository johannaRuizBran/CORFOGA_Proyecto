<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Detail extends Model {
    // Para que Eloquent no asuma los atributos "created_at" y "updated_at".
    public $timestamps = false;

    /**
     * Atributos que son asignables.
     *
     * @var array
     */
    protected $fillable = [
        'inspectionID',
        'animalID',
        'feedingMethodID',
        'weight',
        'scrotalCircumference',
        'observations'
    ];

    /**
     * Atributos que no pueden cambiar.
     *
     * @var array
     */
     protected $guarded = [
        'inspectionID',
        'animalID',
        'feedingMethodID',
        'weight',
        'scrotalCircumference',
        'observations'
     ];

    /**
     * Atributos que deben ser ocultos para arreglos.
     *
     * @var array
     */
    protected $hidden = [
        'feedingMethodID'
    ];

    // Se asocian los detalles con los animales a los que corresponden.
    public function animal() {
        $this->hasOne('App\Animal', 'id', 'animalID');
    }
}
