<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;

class ArchivoController extends Controller
{
    public function index() { return view('cliente.archivos.index'); }
    public function create() { return view('cliente.archivos.create'); }
    public function store() {}
    public function destroy($id) {}
}
