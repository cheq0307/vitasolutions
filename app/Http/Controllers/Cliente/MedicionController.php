<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;

class MedicionController extends Controller
{
    public function index() { return view('cliente.mediciones.index'); }
    public function create() { return view('cliente.mediciones.create'); }
    public function store() {}
    public function show($id) {}
}
