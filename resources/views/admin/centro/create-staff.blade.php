@extends('layouts.app')

@section('title', 'Nuevo asesor')
@section('subtitle', 'Agregar un asesor a tu centro')

@section('content')
<div class="max-w-lg">
<form method="POST" action="{{ route('admin.centro.staff.store') }}">
@csrf

<div class="bg-slate-800 border border-slate-700 rounded-xl p-5 space-y-4">
    <h2 class="text-white font-semibold text-sm uppercase tracking-wider">Datos del asesor</h2>

    <div>
        <label class="block text-slate-300 text-sm font-medium mb-1">Nombre completo *</label>
        <input type="text" name="name" value="{{ old('name') }}" required
            class="w-full bg-slate-700 border border-slate-600 text-white placeholder-slate-500 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-teal-500"
            placeholder="Nombre del asesor">
    </div>

    <div>
        <label class="block text-slate-300 text-sm font-medium mb-1">Correo electrónico *</label>
        <input type="email" name="email" value="{{ old('email') }}" required
            class="w-full bg-slate-700 border border-slate-600 text-white placeholder-slate-500 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-teal-500"
            placeholder="asesor@centro.com">
    </div>

    <div>
        <label class="block text-slate-300 text-sm font-medium mb-1">Contraseña *</label>
        <input type="password" name="password" required
            class="w-full bg-slate-700 border border-slate-600 text-white placeholder-slate-500 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-teal-500"
            placeholder="Mínimo 6 caracteres">
    </div>

    <div class="bg-slate-700/50 rounded-lg p-3">
        <p class="text-slate-400 text-xs">
            <span class="text-teal-400 font-medium">Nota:</span>
            El asesor tendrá acceso a gestionar clientes, productos y planes del centro,
            pero no podrá crear otros asesores ni modificar la información del centro.
        </p>
    </div>

    <div class="flex gap-3 pt-2">
        <button type="submit"
            class="flex-1 py-2.5 rounded-lg text-sm font-semibold text-white transition"
            style="background:#0F6E56">
            Crear asesor
        </button>
        <a href="{{ route('admin.centro.show') }}"
           class="flex-1 text-center py-2.5 rounded-lg text-sm font-medium bg-slate-700 text-slate-300 hover:bg-slate-600 transition">
            Cancelar
        </a>
    </div>
</div>
</form>
</div>
@endsection