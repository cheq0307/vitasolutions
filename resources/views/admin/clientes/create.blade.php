@extends('layouts.app')
@section('title', 'Nuevo cliente')
@section('page-title', 'Nuevo cliente')
@section('page-subtitle', 'Registrar un nuevo cliente en el sistema')
@section('content')

<div class="max-w-lg">
    <div class="bg-slate-900 border border-slate-800 rounded-xl p-6">

        @if($errors->any())
        <div class="bg-red-900/30 border border-red-700 text-red-400 rounded-lg px-4 py-3 mb-6 text-sm">
            @foreach($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
        @endif

        <form method="POST" action="{{ route('admin.clientes.store') }}">
            @csrf

            <div class="mb-4">
                <label class="block text-slate-300 text-sm mb-1">Nombre completo <span class="text-red-400">*</span></label>
                <input type="text" name="name" value="{{ old('name') }}"
                    class="w-full bg-slate-800 text-slate-100 rounded-lg px-4 py-2 border border-slate-700 focus:outline-none focus:border-green-500 text-sm"
                    placeholder="Ej. María García López">
            </div>

            <div class="mb-4">
                <label class="block text-slate-300 text-sm mb-1">Correo electrónico <span class="text-red-400">*</span></label>
                <input type="email" name="email" value="{{ old('email') }}"
                    class="w-full bg-slate-800 text-slate-100 rounded-lg px-4 py-2 border border-slate-700 focus:outline-none focus:border-green-500 text-sm"
                    placeholder="correo@ejemplo.com">
            </div>

            <div class="mb-4">
                <label class="block text-slate-300 text-sm mb-1">Teléfono</label>
                <input type="text" name="phone" value="{{ old('phone') }}"
                    class="w-full bg-slate-800 text-slate-100 rounded-lg px-4 py-2 border border-slate-700 focus:outline-none focus:border-green-500 text-sm"
                    placeholder="Ej. 2221234567">
            </div>

            <div class="mb-4">
                <label class="block text-slate-300 text-sm mb-1">Contraseña <span class="text-red-400">*</span></label>
                <input type="password" name="password"
                    class="w-full bg-slate-800 text-slate-100 rounded-lg px-4 py-2 border border-slate-700 focus:outline-none focus:border-green-500 text-sm"
                    placeholder="Mínimo 6 caracteres">
            </div>

            <div class="mb-6">
                <label class="block text-slate-300 text-sm mb-1">Confirmar contraseña <span class="text-red-400">*</span></label>
                <input type="password" name="password_confirmation"
                    class="w-full bg-slate-800 text-slate-100 rounded-lg px-4 py-2 border border-slate-700 focus:outline-none focus:border-green-500 text-sm">
            </div>

            <div class="flex gap-3">
                <button type="submit"
                    class="bg-green-600 hover:bg-green-500 text-white font-medium px-6 py-2 rounded-lg transition text-sm">
                    Registrar cliente
                </button>
                <a href="{{ route('admin.clientes.index') }}"
                    class="text-slate-400 hover:text-white px-6 py-2 rounded-lg hover:bg-slate-800 transition text-sm">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
