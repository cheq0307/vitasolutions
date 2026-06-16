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
        $centers = Center::with('owner')
            ->withCount(['admins', 'clients', 'products', 'plans'])
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
            'name'           => 'required|string|max:120',
            'address'        => 'nullable|string|max:200',
            'phone'          => 'nullable|string|max:20',
            'email'          => 'nullable|email|max:120',
            'logo_url'       => 'nullable|url|max:500',
            'active'         => 'boolean',
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

        // Crear admin owner inicial si se proporcionó
        if ($request->filled('admin_email')) {
            $owner = User::create([
                'name'      => $request->admin_name,
                'email'     => $request->admin_email,
                'password'  => Hash::make($request->admin_password),
                'role'      => 'admin',
                'center_id' => $center->id,
                'active'    => true,
            ]);

            // Asignar como owner del centro
            $center->update(['owner_id' => $owner->id]);
        }

        return redirect()->route('superadmin.centers.index')
            ->with('success', "Centro \"{$center->name}\" creado correctamente.");
    }

    public function show(Center $center)
    {
        $center->load(['owner', 'admins', 'clients']);
        $stats = [
            'admins'   => $center->admins()->count(),
            'clients'  => $center->clients()->count(),
            'products' => $center->products()->count(),
            'plans'    => $center->plans()->count(),
        ];

        // Admins disponibles para asignar como owner (admins del centro sin ser owner)
        $adminsDisponibles = $center->admins()->where('id', '!=', $center->owner_id)->get();

        return view('superadmin.centers.show', compact('center', 'stats', 'adminsDisponibles'));
    }

    public function edit(Center $center)
    {
        $center->load('admins');
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
            'owner_id' => 'nullable|exists:users,id',
        ]);

        $center->update([
            'name'     => $request->name,
            'address'  => $request->address,
            'phone'    => $request->phone,
            'email'    => $request->email,
            'logo_url' => $request->logo_url,
            'active'   => $request->boolean('active', true),
            'owner_id' => $request->owner_id,
        ]);

        return redirect()->route('superadmin.centers.index')
            ->with('success', "Centro \"{$center->name}\" actualizado.");
    }

    public function destroy(Center $center)
    {
        if ($center->clients()->count() > 0) {
            return back()->with('error', "No se puede eliminar: el centro tiene {$center->clients()->count()} cliente(s) activo(s).");
        }

        $center->delete();
        return redirect()->route('superadmin.centers.index')
            ->with('success', 'Centro eliminado.');
    }

    /**
     * Asignar un admin existente del centro como owner.
     * Solo el superadmin puede hacer esto.
     */
    public function assignOwner(Request $request, Center $center)
    {
        $request->validate([
            'owner_id' => 'required|exists:users,id',
        ]);

        $user = User::findOrFail($request->owner_id);

        // Verificar que el usuario pertenece al centro
        if ($user->center_id !== $center->id) {
            return back()->with('error', 'El usuario no pertenece a este centro.');
        }

        $center->update(['owner_id' => $user->id]);

        return back()->with('success', "{$user->name} ahora es el admin owner de {$center->name}.");
    }

    /**
     * Deshabilitar un admin del centro.
     * El superadmin puede deshabilitar a cualquiera.
     */
    public function toggleAdmin(Request $request, Center $center, User $user)
    {
        // No se puede deshabilitar al superadmin
        if ($user->isSuperAdmin()) {
            return back()->with('error', 'No se puede deshabilitar al superadmin.');
        }

        $user->update(['active' => !$user->active]);
        $estado = $user->active ? 'habilitado' : 'deshabilitado';

        return back()->with('success', "{$user->name} ha sido {$estado}.");
    }
}