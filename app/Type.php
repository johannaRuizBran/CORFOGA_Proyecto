<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Type extends Model {
    // Para que Eloquent no asuma los atributos "created_at" y "updated_at".
    public $timestamps = false;

    /**
     * Atributos que no pueden cambiar.
     *
     * @var array
     */
     protected $guarded = [
        'id',
        'name'
     ];
}
