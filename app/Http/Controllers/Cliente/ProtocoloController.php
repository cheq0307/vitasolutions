<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;

class ProtocoloController extends Controller
{
    public function index() { return view('cliente.protocolos.index'); }
    public function show($id) {}
}
