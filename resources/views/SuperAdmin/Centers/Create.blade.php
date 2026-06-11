@extends('layouts.app')

@section('title', 'Nuevo centro')
@section('subtitle', 'Registrar un nuevo centro VitaSolutions')

@section('content')
<div class="max-w-2xl">
<form method="POST" action="{{ route('superadmin.centers.store') }}">
@csrf

<div class="space-y-5">

    {{-- Info del centro --}}
    <div class="bg-slate-800 border border-slate-700 rounded-xl p-5 space-y-4">
        <h2 class="text-white font-semibold text-sm uppercase tracking-wider">Información del centro</h2>

        <div>
            <label class="block text-slate-300 text-sm font-medium mb-1">Nombre del centro *</label>
            <input type="text" name="name" value="{{ old('name') }}" required
                class="w-full bg-slate-700 border border-slate-600 text-white placeholder-slate-500 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-teal-500"
                placeholder="Ej. VitaSolutions Puebla Centro">
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-slate-300 text-sm font-medium mb-1">Teléfono</label>
                <input type="text" name="phone" value="{{ old('phone') }}"
                    class="w-full bg-slate-700 border border-slate-600 text-white placeholder-slate-500 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-teal-500"
                    placeholder="222 123 4567">
            </div>
            <div>
                <label class="block text-slate-300 text-sm font-medium mb-1">Correo del centro</label>
                <input type="email" name="email" value="{{ old('email') }}"
                    class="w-full bg-slate-700 border border-slate-600 text-white placeholder-slate-500 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-teal-500"
                    placeholder="centro@vitasolutions.com">
            </div>
        </div>

        <div>
            <label class="block text-slate-300 text-sm font-medium mb-1">Dirección</label>
            <input type="text" name="address" value="{{ old('address') }}"
                class="w-full bg-slate-700 border border-slate-600 text-white placeholder-slate-500 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-teal-500"
                placeholder="Calle, número, colonia, ciudad">
        </div>

        <div>
            <label class="block text-slate-300 text-sm font-medium mb-1">URL del logo</label>
            <input type="url" name="logo_url" value="{{ old('logo_url') }}" id="logo-url"
                class="w-full bg-slate-700 border border-slate-600 text-white placeholder-slate-500 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-teal-500"
                placeholder="https://... (opcional, si no se usa el logo de VitaSolutions)">
            <div id="logo-preview" class="hidden mt-2">
                <img id="logo-img" src="" class="h-12 rounded-lg object-contain bg-slate-700 p-1">
            </div>
        </div>

        <div class="flex items-center justify-between pt-1">
            <span class="text-slate-300 text-sm font-medium">Centro activo</span>
            <label class="relative inline-flex items-center cursor-pointer">
                <input type="hidden" name="active" value="0">
                <input type="checkbox" name="active" value="1" class="sr-only peer" checked>
                <div class="w-10 h-5 bg-slate-600 rounded-full peer peer-checked:bg-teal-600 transition after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:rounded-full after:h-4 after:w-4 after:transition peer-checked:after:translate-x-5"></div>
            </label>
        </div>
    </div>

    {{-- Admin inicial (opcional) --}}
    <div class="bg-slate-800 border border-slate-700 rounded-xl p-5 space-y-4">
        <h2 class="text-white font-semibold text-sm uppercase tracking-wider">Administrador inicial <span class="text-slate-500 font-normal normal-case">(opcional)</span></h2>
        <p class="text-slate-500 text-xs">Si lo dejas vacío, puedes asignar un admin después desde el detalle del centro.</p>

        <div>
            <label class="block text-slate-300 text-sm font-medium mb-1">Nombre</label>
            <input type="text" name="admin_name" value="{{ old('admin_name') }}"
                class="w-full bg-slate-700 border border-slate-600 text-white placeholder-slate-500 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-teal-500"
                placeholder="Nombre del administrador">
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-slate-300 text-sm font-medium mb-1">Correo</label>
                <input type="email" name="admin_email" value="{{ old('admin_email') }}"
                    class="w-full bg-slate-700 border border-slate-600 text-white placeholder-slate-500 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-teal-500"
                    placeholder="admin@centro.com">
            </div>
            <div>
                <label class="block text-slate-300 text-sm font-medium mb-1">Contraseña</label>
                <input type="password" name="admin_password"
                    class="w-full bg-slate-700 border border-slate-600 text-white placeholder-slate-500 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-teal-500"
                    placeholder="Mínimo 6 caracteres">
            </div>
        </div>
    </div>

    {{-- Acciones --}}
    <div class="flex gap-3">
        <button type="submit"
            class="flex-1 py-2.5 rounded-lg text-sm font-semibold text-white transition"
            style="background:#0F6E56">
            Crear centro
        </button>
        <a href="{{ route('superadmin.centers.index') }}"
           class="flex-1 text-center py-2.5 rounded-lg text-sm font-medium bg-slate-700 text-slate-300 hover:bg-slate-600 transition">
            Cancelar
        </a>
    </div>

</div>
</form>
</div>

@push('scripts')
<script>
document.getElementById('logo-url').addEventListener('input', function() {
    const preview = document.getElementById('logo-preview');
    const img = document.getElementById('logo-img');
    if (this.value) {
        img.src = this.value;
        preview.classList.remove('hidden');
    } else {
        preview.classList.add('hidden');
    }
});
</script>
@endpush
@endsection