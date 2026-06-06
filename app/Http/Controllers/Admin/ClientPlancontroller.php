<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClientPlan;
use App\Models\Plan;
use App\Models\User;
use Illuminate\Http\Request;

class ClientPlanController extends Controller
{
    // Lista de suscripciones activas (todas)
    public function index()
    {
        $subscriptions = ClientPlan::with(['user', 'plan'])
            ->latest()
            ->paginate(20);

        return view('admin.client_planes.index', compact('subscriptions'));
    }

    // Asignar plan a cliente
    public function create(Request $request)
    {
        $clientes = User::where('role', 'client')->orderBy('name')->get();
        $planes   = Plan::where('active', true)->orderBy('name')->get();
        $selected_client = $request->query('cliente');

        return view('admin.client_planes.create', compact('clientes', 'planes', 'selected_client'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id'    => 'required|exists:users,id',
            'plan_id'    => 'required|exists:plans,id',
            'starts_at'  => 'required|date',
            'ends_at'    => 'nullable|date|after:starts_at',
            'status'     => 'required|in:active,paused,cancelled',
            'notes'      => 'nullable|string',
        ]);

        $subscription = ClientPlan::create($request->only(
            'user_id', 'plan_id', 'starts_at', 'ends_at', 'status', 'notes'
        ));

        $cliente = $subscription->user;

        return redirect()->route('admin.client-planes.index')
            ->with('success', "Plan asignado a {$cliente->name} correctamente.");
    }

    public function edit(ClientPlan $clientPlan)
    {
        $clientes = User::where('role', 'client')->orderBy('name')->get();
        $planes   = Plan::where('active', true)->orderBy('name')->get();

        return view('admin.client_planes.edit', compact('clientPlan', 'clientes', 'planes'));
    }

    public function update(Request $request, ClientPlan $clientPlan)
    {
        $request->validate([
            'plan_id'   => 'required|exists:plans,id',
            'starts_at' => 'required|date',
            'ends_at'   => 'nullable|date|after:starts_at',
            'status'    => 'required|in:active,paused,cancelled',
            'notes'     => 'nullable|string',
        ]);

        $clientPlan->update($request->only(
            'plan_id', 'starts_at', 'ends_at', 'status', 'notes'
        ));

        return redirect()->route('admin.client-planes.index')
            ->with('success', 'Suscripción actualizada.');
    }

    public function destroy(ClientPlan $clientPlan)
    {
        $nombre = $clientPlan->user->name;
        $clientPlan->delete();

        return redirect()->route('admin.client-planes.index')
            ->with('success', "Suscripción de {$nombre} eliminada.");
    }
}