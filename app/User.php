<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable {
    use Notifiable;
    /**
     * Atributos que son asignables.
     *
     * @var array
     */
    protected $fillable = [
        'identification',
        'name',
        'lastName',
        'password',
        'email',
        'phoneNumber',
        'role',
        'state'
    ];

    /**
     * Atributos que no pueden cambiar.
     *
     * @var array
     */
     protected $guarded = [
         'id',
         'identification',
         'role',
         'created_at',
         'updated_at'
     ];

    /**
     * Atributos que deben ser ocultos para arreglos.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'created_at',
        'updated_at'
    ];

    // Se asocia el usuario con sus fincas.
    public function farms() {
        $this->hasMany('App\Farm', 'userID', 'id');
    }

    // Se asocial el usuario con sus historiales.
    public function historicals() {
        $this->hasMany('App\Historical', 'userID',' id');
    } 
}
