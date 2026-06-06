<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;

class CuestionarioController extends Controller
{
    public function index() { return view('cliente.cuestionarios.index'); }
    public function responder($survey) {}
    public function guardar($survey) {}
}
