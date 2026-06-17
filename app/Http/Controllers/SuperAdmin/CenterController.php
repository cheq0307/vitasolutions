<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Center;
use App\Models\User;
use App\Services\ImageUploadService;
use Illuminate\Http\Request;

class CenterController extends Controller
{
    public function __construct(protected ImageUploadService $imageService) {}

    // ─── Index ───────────────────────────────────────────────────────────────

    public function index()
    {
        $centers = Center::with('owner')->orderBy('name')->paginate(15);
        return view('superadmin.centers.index', compact('centers'));
    }

    // ─── Show ────────────────────────────────────────────────────────────────

    public function show(Center $center)
    {
        $center->load(['owner', 'users' => fn($q) => $q->where('role', '!=', 'cliente')]);
        return view('superadmin.centers.show', compact('center'));
    }

    // ─── Create ──────────────────────────────────────────────────────────────

    public function create()
    {
        $owners = User::where('role', 'admin')->whereNull('center_id')->get();
        return view('superadmin.centers.create', compact('owners'));
    }

    // ─── Store ───────────────────────────────────────────────────────────────

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'       => 'required|string|max:150',
            'address'    => 'nullable|string|max:300',
            'phone'      => 'nullable|string|max:30',
            'email'      => 'nullable|email|max:150',
            'logo'       => 'nullable|url|max:500',
            'logo_file'  => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'owner_id'   => 'nullable|exists:users,id',
            'active'     => 'boolean',
        ]);

        $data['active'] = $request->boolean('active', true);

        if ($request->hasFile('logo_file')) {
            $data['logo_path'] = $this->imageService->store($request->file('logo_file'), 'centers');
            $data['logo']      = null;
        }

        $center = Center::create($data);

        // Asignar center_id al owner si se seleccionó
        if ($data['owner_id'] ?? null) {
            User::where('id', $data['owner_id'])->update(['center_id' => $center->id]);
        }

        return redirect()->route('superadmin.centers.index')
                         ->with('success', 'Centro creado correctamente.');
    }

    // ─── Edit ────────────────────────────────────────────────────────────────

    public function edit(Center $center)
    {
        // Admins disponibles para ser owner: rol admin sin centro, o el owner actual
        $owners = User::where('role', 'admin')
                      ->where(fn($q) => $q->whereNull('center_id')
                                          ->orWhere('id', $center->owner_id))
                      ->get();

        return view('superadmin.centers.edit', compact('center', 'owners'));
    }

    // ─── Update ──────────────────────────────────────────────────────────────

    public function update(Request $request, Center $center)
    {
        $data = $request->validate([
            'name'         => 'required|string|max:150',
            'address'      => 'nullable|string|max:300',
            'phone'        => 'nullable|string|max:30',
            'email'        => 'nullable|email|max:150',
            'logo'         => 'nullable|url|max:500',
            'logo_file'    => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'remove_logo'  => 'boolean',
            'owner_id'     => 'nullable|exists:users,id',
            'active'       => 'boolean',
        ]);

        $data['active'] = $request->boolean('active');

        // Lógica de logo
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

        // Manejo de owner_id: si cambia, actualizar center_id de usuarios
        $oldOwnerId = $center->owner_id;
        $newOwnerId = $data['owner_id'] ?? null;

        if ($oldOwnerId !== $newOwnerId) {
            // Quitar center_id al owner anterior (si no es staff del mismo centro)
            if ($oldOwnerId) {
                User::where('id', $oldOwnerId)->update(['center_id' => null]);
            }
            // Asignar center_id al nuevo owner
            if ($newOwnerId) {
                User::where('id', $newOwnerId)->update(['center_id' => $center->id]);
            }
        }

        $center->update($data);

        return redirect()->route('superadmin.centers.show', $center)
                         ->with('success', 'Centro actualizado correctamente.');
    }

    // ─── Toggle activo ───────────────────────────────────────────────────────

    public function toggle(Center $center)
    {
        $center->update(['active' => !$center->active]);
        return back()->with('success', 'Estado del centro actualizado.');
    }

    // ─── Toggle admin (habilitar/deshabilitar cualquier usuario) ─────────────

    public function toggleAdmin(User $user)
    {
        $user->update(['active' => !$user->active]);
        return back()->with('success', 'Estado del usuario actualizado.');
    }
}