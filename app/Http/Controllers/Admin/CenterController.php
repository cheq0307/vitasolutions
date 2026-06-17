<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Center;
use App\Models\User;
use App\Services\ImageUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class CenterController extends Controller
{
    public function __construct(protected ImageUploadService $imageService) {}

    // ─── Ver / editar el propio centro (owner) ────────────────────────────────

    public function show()
    {
        $center = $this->getOwnCenter();
        return view('admin.centro.edit', compact('center'));
    }

    public function update(Request $request)
    {
        $center = $this->getOwnCenter();

        $data = $request->validate([
            'name'        => 'required|string|max:150',
            'address'     => 'nullable|string|max:300',
            'phone'       => 'nullable|string|max:30',
            'email'       => 'nullable|email|max:150',
            'logo'        => 'nullable|url|max:500',
            'logo_file'   => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'remove_logo' => 'boolean',
        ]);

        // Lógica de logo (misma que en SuperAdmin)
        if ($request->hasFile('logo_file')) {
            $data['logo_path'] = $this->imageService->replace(
                $request->file('logo_file'),
                $center->logo_path,
                'centers'
            );
            $data['logo'] = null;

        } elseif ($request->boolean('remove_logo')) {
            $this->imageService->delete($center->logo_path);
            $data['logo_path'] = null;
            $data['logo']      = null;

        } elseif (!empty($data['logo'])) {
            $this->imageService->delete($center->logo_path);
            $data['logo_path'] = null;

        } else {
            unset($data['logo']);
        }

        $center->update($data);

        return redirect()->route('admin.centro.show')
                         ->with('success', 'Centro actualizado correctamente.');
    }

    // ─── Gestión de staff ─────────────────────────────────────────────────────

    public function createStaff()
    {
        return view('admin.centro.create-staff');
    }

    public function storeStaff(Request $request)
    {
        $center = $this->getOwnCenter();

        $data = $request->validate([
            'name'     => 'required|string|max:150',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        User::create([
            'name'      => $data['name'],
            'email'     => $data['email'],
            'password'  => Hash::make($data['password']),
            'role'      => 'admin',
            'center_id' => $center->id,
            'active'    => true,
        ]);

        return redirect()->route('admin.centro.show')
                         ->with('success', 'Asesor creado correctamente.');
    }

    public function toggleStaff(User $user)
    {
        $center = $this->getOwnCenter();

        // Solo puede modificar usuarios de su propio centro
        abort_if($user->center_id !== $center->id, 403);

        // No puede deshabilitarse a sí mismo
        abort_if($user->id === Auth::id(), 403, 'No puedes deshabilitarte a ti mismo.');

        // No puede deshabilitar a otro owner (solo superadmin puede)
        abort_if($center->owner_id === $user->id && $user->id !== Auth::id(), 403);

        $user->update(['active' => !$user->active]);

        return back()->with('success', 'Estado del asesor actualizado.');
    }

    // ─── Helper privado ───────────────────────────────────────────────────────

    private function getOwnCenter(): Center
    {
        $user = Auth::user();

        // Verificar que el usuario autenticado es owner de un centro
        $center = Center::where('owner_id', $user->id)->first();
        abort_if(!$center, 403, 'No tienes un centro asignado como owner.');

        return $center;
    }
}