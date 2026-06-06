<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use App\Models\HealthProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PerfilSaludController extends Controller
{
    public function show()
    {
        $user   = Auth::user();
        $perfil = $user->healthProfile ?? HealthProfile::create(['user_id' => $user->id]);

        return view('cliente.perfil', compact('user', 'perfil'));
    }

    public function update(Request $request)
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

        $user   = Auth::user();
        $perfil = $user->healthProfile ?? HealthProfile::create(['user_id' => $user->id]);

        $perfil->update($request->only([
            'blood_type', 'sex', 'birth_date', 'height_cm', 'weight_kg',
            'allergies', 'chronic_conditions', 'current_medications',
            'main_goal', 'activity_level', 'notes',
        ]));

        return redirect()->route('cliente.perfil')->with('success', 'Perfil actualizado correctamente.');
    }
}