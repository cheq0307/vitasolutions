@extends('layouts.app')
@section('title', 'Editar cliente')
@section('page-title', 'Editar cliente')
@section('page-subtitle')Modificar datos de {{ $cliente->name }}@endsection
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

        <form method="POST" action="{{ route('admin.clientes.update', $cliente) }}">
            @csrf
            @method('PATCH')

            <div class="mb-4">
                <label class="block text-slate-300 text-sm mb-1">Nombre completo <span class="text-red-400">*</span></label>
                <input type="text" name="name" value="{{ old('name', $cliente->name) }}"
                    class="w-full bg-slate-800 text-slate-100 rounded-lg px-4 py-2 border border-slate-700 focus:outline-none focus:border-green-500 text-sm">
            </div>

            <div class="mb-4">
                <label class="block text-slate-300 text-sm mb-1">Correo electrónico <span class="text-red-400">*</span></label>
                <input type="email" name="email" value="{{ old('email', $cliente->email) }}"
                    class="w-full bg-slate-800 text-slate-100 rounded-lg px-4 py-2 border border-slate-700 focus:outline-none focus:border-green-500 text-sm">
            </div>

            <div class="mb-4">
                <label class="block text-slate-300 text-sm mb-1">Teléfono</label>
                <input type="text" name="phone" value="{{ old('phone', $cliente->phone) }}"
                    class="w-full bg-slate-800 text-slate-100 rounded-lg px-4 py-2 border border-slate-700 focus:outline-none focus:border-green-500 text-sm">
            </div>

            <div class="mb-6">
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="hidden" name="active" value="0">
                    <input type="checkbox" name="active" value="1" {{ $cliente->active ? 'checked' : '' }}
                        class="w-4 h-4 accent-green-500">
                    <span class="text-slate-300 text-sm">Cliente activo</span>
                </label>
            </div>

            <div class="flex gap-3">
                <button type="submit"
                    class="bg-green-600 hover:bg-green-500 text-white font-medium px-6 py-2 rounded-lg transition text-sm">
                    Guardar cambios
                </button>
                <a href="{{ route('admin.clientes.show', $cliente) }}"
                    class="text-slate-400 hover:text-white px-6 py-2 rounded-lg hover:bg-slate-800 transition text-sm">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
@endsection