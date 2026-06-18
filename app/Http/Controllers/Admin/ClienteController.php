<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\HealthProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ClienteController extends Controller
{
    public function index()
    {
        $clientes = User::where('role', 'client')->latest()->get();
        return view('admin.clientes.index', compact('clientes'));
    }

    public function create()
    {
        return view('admin.clientes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'phone'    => 'nullable|string|max:20',
            'password' => 'required|min:6|confirmed',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'phone'    => $request->phone,
            'password' => Hash::make($request->password),
            'role'     => 'client',
            'active'   => true,
        ]);

        HealthProfile::create(['user_id' => $user->id]);

        return redirect()->route('admin.clientes.show', $user)
            ->with('success', 'Cliente registrado correctamente.');
    }

    public function show(User $cliente)
    {
        $cliente->load('healthProfile', 'userProtocols', 'wellnessSurveys', 'deviceReadings', 'healthFiles');
        return view('admin.clientes.show', compact('cliente'));
    }

    public function edit(User $cliente)
    {
        return view('admin.clientes.edit', compact('cliente'));
    }

    public function update(Request $request, User $cliente)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $cliente->id,
            'phone' => 'nullable|string|max:20',
        ]);

        $cliente->update($request->only('name', 'email', 'phone', 'active'));

        return redirect()->route('admin.clientes.show', $cliente)
            ->with('success', 'Cliente actualizado.');
    }

    public function destroy(User $cliente)
    {
        $cliente->delete();
        return redirect()->route('admin.clientes.index')
            ->with('success', 'Cliente eliminado.');
    }

    public function editSalud(User $cliente)
    {
        $perfil = $cliente->healthProfile ?? HealthProfile::create(['user_id' => $cliente->id]);
        return view('admin.clientes.salud', compact('cliente', 'perfil'));
    }

    public function updateSalud(Request $request, User $cliente)
    {
        $request->validate([
            'blood_type'          => 'nullable|in:A+,A-,B+,B-,AB+,AB-,O+,O-,desconocido',
            'sex'                 => 'nullable|in:masculino,femenino,otro',
            'birth_date'          => 'nullable|date|before:today',
            'height_cm'           => 'nullable|numeric|min:50|max:250',
            'weight_kg'           => 'nullable|numeric|min:20|max:300',
            'allergies'           => 'nullable|string|max:500',
            'chronic_conditions'  => 'nullable|string|max:500',
            'current_medications' => 'nullable|string|max:500',
            'main_goal'           => 'nullable|in:perder_peso,ganar_masa,mantener,energia,sueno,estres,otro',
            'activity_level'      => 'nullable|in:sedentario,ligero,moderado,activo,muy_activo',
            'notes'               => 'nullable|string|max:1000',
        ]);

        $perfil = $cliente->healthProfile ?? HealthProfile::create(['user_id' => $cliente->id]);

        $perfil->update($request->only([
            'blood_type', 'sex', 'birth_date', 'height_cm', 'weight_kg',
            'allergies', 'chronic_conditions', 'current_medications',
            'main_goal', 'activity_level', 'notes',
        ]));

        return redirect()->route('admin.clientes.show', $cliente)
            ->with('success', 'Perfil de salud actualizado.');
    }

    public function salud(User $cliente)     { 
        $cliente->load('healthProfile', 'userProtocols', 'wellnessSurveys', 'deviceReadings', 'healthFiles');
        return view('admin.clientes.show', compact('cliente')); 
    }
    public function mediciones(User $cliente){ 
        $cliente->load('healthProfile', 'userProtocols', 'wellnessSurveys', 'deviceReadings', 'healthFiles');
        return view('admin.clientes.show', compact('cliente')); 
    }
    public function archivos(User $cliente)  { 
        $cliente->load('healthProfile', 'userProtocols', 'wellnessSurveys', 'deviceReadings', 'healthFiles');
        return view('admin.clientes.show', compact('cliente')); 
    }
    public function protocolos(User $cliente){ 
        $cliente->load('healthProfile', 'userProtocols', 'wellnessSurveys', 'deviceReadings', 'healthFiles');
        return view('admin.clientes.show', compact('cliente')); 
    }
    public function asignarProtocolo(Request $request, User $cliente) {}
}