<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Session;

class HomeController extends Controller {

    /* Se ejecuta después de que un usuario se logea con éxito; redirecciona al
     * usuario según su tipo a la vista de "inicio" correspondiente. Si el usuario
     * se encuentra desactivado, se deslogea y se notifica. Si no hay ningún usuario
     * logeado entonces se vuelve a la vista de login. */
    public function index() {
        $user = Auth::user();
        if($user->state == '1') {
            if($user->role == 'a') {
                return redirect()->route('admin.inicio');
            }
            elseif($user->role == 'i') {
                return redirect()->route('inspector.inicio');
            }
            else {
                return redirect()->route('productores');
            }
        }
        else {
            Auth::logout();
            Session::flash('state', 'Error');
            Session::flash('message', 'Su cuenta se encuentra desactivada actualmente');
            Session::flash('alert_class', 'alert-danger');
            return redirect('/');
        }
    }
}
