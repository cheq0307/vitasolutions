<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CenterController extends Controller
{
    /**
     * Obtiene el centro del admin autenticado.
     */
    private function myCenter()
    {
        return auth()->user()->center;
    }

    /**
     * Vista del centro — disponible para owner y staff.
     */
    public function show()
    {
        $center = $this->myCenter();

        if (!$center) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'No tienes un centro asignado.');
        }

        $center->load(['owner', 'admins', 'clients']);
        $isOwner = auth()->user()->isOwner();

        return view('admin.centro.show', compact('center', 'isOwner'));
    }

    /**
     * Formulario de edición del centro — solo para el owner.
     */
    public function edit()
    {
        $this->authorizeOwner();
        $center = $this->myCenter();

        return view('admin.centro.edit', compact('center'));
    }

    /**
     * Actualizar información del centro — solo para el owner.
     */
    public function update(Request $request)
    {
        $this->authorizeOwner();
        $center = $this->myCenter();

        $request->validate([
            'name'     => 'required|string|max:120',
            'address'  => 'nullable|string|max:200',
            'phone'    => 'nullable|string|max:20',
            'email'    => 'nullable|email|max:120',
            'logo_url' => 'nullable|url|max:500',
        ]);

        $center->update([
            'name'     => $request->name,
            'address'  => $request->address,
            'phone'    => $request->phone,
            'email'    => $request->email,
            'logo_url' => $request->logo_url,
        ]);

        return redirect()->route('admin.centro.show')
            ->with('success', 'Información del centro actualizada.');
    }

    /**
     * Crear un nuevo admin staff en el centro — solo owner.
     */
    public function createStaff()
    {
        $this->authorizeOwner();
        return view('admin.centro.create-staff');
    }

    public function storeStaff(Request $request)
    {
        $this->authorizeOwner();

        $request->validate([
            'name'     => 'required|string|max:120',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
        ]);

        User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'role'      => 'admin',
            'center_id' => $this->myCenter()->id,
            'active'    => true,
        ]);

        return redirect()->route('admin.centro.show')
            ->with('success', "Admin \"{$request->name}\" creado correctamente.");
    }

    /**
     * Habilitar / deshabilitar un admin staff — solo owner.
     * El owner NO puede deshabilitarse a sí mismo.
     */
    public function toggleStaff(User $user)
    {
        $this->authorizeOwner();
        $center = $this->myCenter();

        // Verificar que el usuario pertenece al mismo centro
        if ($user->center_id !== $center->id) {
            abort(403);
        }

        // El owner no puede deshabilitarse a sí mismo
        if ($user->id === auth()->id()) {
            return back()->with('error', 'No puedes deshabilitarte a ti mismo.');
        }

        // El owner no puede deshabilitar a otro owner
        // (solo el superadmin puede hacerlo)
        if ($center->isOwnedBy($user)) {
            return back()->with('error', 'Solo el superadmin puede deshabilitar al admin owner.');
        }

        $user->update(['active' => !$user->active]);
        $estado = $user->active ? 'habilitado' : 'deshabilitado';

        return back()->with('success', "{$user->name} ha sido {$estado}.");
    }

    /**
     * Verifica que el usuario autenticado es owner de su centro.
     */
    private function authorizeOwner(): void
    {
        if (!auth()->user()->isOwner() && !auth()->user()->isSuperAdmin()) {
            abort(403, 'Solo el admin owner puede realizar esta acción.');
        }
    }
}