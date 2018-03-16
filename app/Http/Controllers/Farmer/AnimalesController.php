<?php

namespace App\Http\Controllers\Farmer;

use App\Http\Controllers\Controller;

class AnimalesController extends Controller
{
    // Se retorna la vista "inicio" de los farmer.
    public function index() {
        return view('farmer.animal');
    }
}
