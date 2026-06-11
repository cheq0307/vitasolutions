@extends('layouts.app')

@section('title', 'Editar centro')
@section('subtitle', $center->name)

@section('content')
<div class="max-w-2xl">
<form method="POST" action="{{ route('superadmin.centers.update', $center) }}">
@csrf @method('PUT')

<div class="space-y-5">

    <div class="bg-slate-800 border border-slate-700 rounded-xl p-5 space-y-4">
        <h2 class="text-white font-semibold text-sm uppercase tracking-wider">Información del centro</h2>

        <div>
            <label class="block text-slate-300 text-sm font-medium mb-1">Nombre *</label>
            <input type="text" name="name" value="{{ old('name', $center->name) }}" required
                class="w-full bg-slate-700 border border-slate-600 text-white rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-teal-500">
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-slate-300 text-sm font-medium mb-1">Teléfono</label>
                <input type="text" name="phone" value="{{ old('phone', $center->phone) }}"
                    class="w-full bg-slate-700 border border-slate-600 text-white placeholder-slate-500 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-teal-500">
            </div>
            <div>
                <label class="block text-slate-300 text-sm font-medium mb-1">Correo</label>
                <input type="email" name="email" value="{{ old('email', $center->email) }}"
                    class="w-full bg-slate-700 border border-slate-600 text-white placeholder-slate-500 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-teal-500">
            </div>
        </div>

        <div>
            <label class="block text-slate-300 text-sm font-medium mb-1">Dirección</label>
            <input type="text" name="address" value="{{ old('address', $center->address) }}"
                class="w-full bg-slate-700 border border-slate-600 text-white placeholder-slate-500 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-teal-500">
        </div>

        <div>
            <label class="block text-slate-300 text-sm font-medium mb-1">URL del logo</label>
            <input type="url" name="logo_url" value="{{ old('logo_url', $center->logo_url) }}" id="logo-url"
                class="w-full bg-slate-700 border border-slate-600 text-white placeholder-slate-500 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-teal-500">
            <div id="logo-preview" class="{{ $center->logo_url ? '' : 'hidden' }} mt-2">
                <img id="logo-img" src="{{ $center->logo_url }}" class="h-12 rounded-lg object-contain bg-slate-700 p-1">
            </div>
        </div>

        <div class="flex items-center justify-between pt-1">
            <span class="text-slate-300 text-sm font-medium">Centro activo</span>
            <label class="relative inline-flex items-center cursor-pointer">
                <input type="hidden" name="active" value="0">
                <input type="checkbox" name="active" value="1" class="sr-only peer" {{ $center->active ? 'checked' : '' }}>
                <div class="w-10 h-5 bg-slate-600 rounded-full peer peer-checked:bg-teal-600 transition after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:rounded-full after:h-4 after:w-4 after:transition peer-checked:after:translate-x-5"></div>
            </label>
        </div>
    </div>

    <div class="flex gap-3">
        <button type="submit"
            class="flex-1 py-2.5 rounded-lg text-sm font-semibold text-white transition"
            style="background:#0F6E56">
            Guardar cambios
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