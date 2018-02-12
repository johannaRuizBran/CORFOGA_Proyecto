<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class AdminController extends Controller
{
    // Se retorna la vista "inicio" de los administradores.
    public function index() {
        return view('admin.index');
    }
}
