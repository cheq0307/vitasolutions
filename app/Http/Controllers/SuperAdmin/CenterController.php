<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Center;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CenterController extends Controller
{
    public function index()
    {
        $centers = Center::withCount(['admins', 'clients', 'products', 'plans'])
            ->latest()
            ->paginate(15);

        return view('superadmin.centers.index', compact('centers'));
    }

    public function create()
    {
        return view('superadmin.centers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'      => 'required|string|max:120',
            'address'   => 'nullable|string|max:200',
            'phone'     => 'nullable|string|max:20',
            'email'     => 'nullable|email|max:120',
            'logo_url'  => 'nullable|url|max:500',
            'active'    => 'boolean',
            // Admin inicial del centro (opcional)
            'admin_name'     => 'nullable|string|max:120',
            'admin_email'    => 'nullable|email|unique:users,email',
            'admin_password' => 'nullable|string|min:6',
        ]);

        $center = Center::create([
            'name'     => $request->name,
            'address'  => $request->address,
            'phone'    => $request->phone,
            'email'    => $request->email,
            'logo_url' => $request->logo_url,
            'active'   => $request->boolean('active', true),
        ]);

        // Crear admin inicial si se proporcionó
        if ($request->filled('admin_email')) {
            User::create([
                'name'      => $request->admin_name,
                'email'     => $request->admin_email,
                'password'  => Hash::make($request->admin_password),
                'role'      => 'admin',
                'center_id' => $center->id,
                'active'    => true,
            ]);
        }

        return redirect()->route('superadmin.centers.index')
            ->with('success', "Centro \"{$center->name}\" creado correctamente.");
    }

    public function show(Center $center)
    {
        $center->load(['admins', 'clients']);
        $stats = [
            'admins'   => $center->admins()->count(),
            'clients'  => $center->clients()->count(),
            'products' => $center->products()->count(),
            'plans'    => $center->plans()->count(),
        ];

        return view('superadmin.centers.show', compact('center', 'stats'));
    }

    public function edit(Center $center)
    {
        return view('superadmin.centers.edit', compact('center'));
    }

    public function update(Request $request, Center $center)
    {
        $request->validate([
            'name'     => 'required|string|max:120',
            'address'  => 'nullable|string|max:200',
            'phone'    => 'nullable|string|max:20',
            'email'    => 'nullable|email|max:120',
            'logo_url' => 'nullable|url|max:500',
            'active'   => 'boolean',
        ]);

        $center->update([
            'name'     => $request->name,
            'address'  => $request->address,
            'phone'    => $request->phone,
            'email'    => $request->email,
            'logo_url' => $request->logo_url,
            'active'   => $request->boolean('active', true),
        ]);

        return redirect()->route('superadmin.centers.index')
            ->with('success', "Centro \"{$center->name}\" actualizado.");
    }

    public function destroy(Center $center)
    {
        $clientCount = $center->clients()->count();
        if ($clientCount > 0) {
            return back()->with('error', "No se puede eliminar: el centro tiene {$clientCount} cliente(s) activo(s).");
        }

        $center->delete();
        return redirect()->route('superadmin.centers.index')
            ->with('success', 'Centro eliminado.');
    }

    // Asignar admin existente a un centro
    public function assignAdmin(Request $request, Center $center)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        User::findOrFail($request->user_id)->update([
            'center_id' => $center->id,
            'role'      => 'admin',
        ]);

        return back()->with('success', 'Administrador asignado correctamente.');
    }
}