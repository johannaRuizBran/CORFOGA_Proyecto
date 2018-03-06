<?php

namespace App\Http\Controllers\Inspector;

use App\Http\Controllers\Controller;

class InspController extends Controller
{
    // Se retorna la vista "inicio" de los administradores.
    public function index() {
        return view('inspector.inicio');
    }

}
