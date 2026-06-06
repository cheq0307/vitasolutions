<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class ClienteController extends Controller
{
    public function index() { return view('admin.clientes.index'); }
    public function create() { return view('admin.clientes.create'); }
    public function store() {}
    public function show($id) {}
    public function edit($id) {}
    public function update($id) {}
    public function destroy($id) {}
    public function salud($id) {}
    public function mediciones($id) {}
    public function archivos($id) {}
    public function protocolos($id) {}
    public function asignarProtocolo($id) {}
}
