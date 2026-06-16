@extends('layouts.app')

@section('title', 'Mi Centro')
@section('subtitle', $center->name)

@section('content')
<div class="max-w-4xl space-y-6">

    {{-- Header --}}
    <div class="flex items-center justify-end gap-3">
        @if($isOwner)
        <a href="{{ route('admin.centro.staff.create') }}"
           class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium bg-slate-800 border border-slate-700 text-slate-300 hover:border-teal-500 hover:text-teal-300 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
            </svg>
            Nuevo asesor
        </a>
        <a href="{{ route('admin.centro.edit') }}"
           class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold text-white transition"
           style="background:#0F6E56">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </svg>
            Editar centro
        </a>
        @endif
    </div>

    {{-- Info del centro --}}
    <div class="bg-slate-800 border border-slate-700 rounded-xl p-6">
        <div class="flex items-start gap-5">
            {{-- Logo --}}
            @if($center->logo_url)
            <img src="{{ $center->logo_url }}" class="w-16 h-16 rounded-xl object-cover shrink-0">
            @else
            <div class="w-16 h-16 rounded-xl flex items-center justify-center shrink-0" style="background:#0F6E56">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                </svg>
            </div>
            @endif

            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-3">
                    <h2 class="text-white font-bold text-xl">{{ $center->name }}</h2>
                    <span class="text-xs px-2 py-0.5 rounded-full {{ $center->active ? 'bg-teal-600/20 text-teal-400' : 'bg-slate-700 text-slate-500' }}">
                        {{ $center->active ? 'Activo' : 'Inactivo' }}
                    </span>
                </div>
                @if($center->address)
                <p class="text-slate-400 text-sm mt-1">{{ $center->address }}</p>
                @endif
                <div class="flex items-center gap-6 mt-3">
                    @if($center->phone)
                    <div class="flex items-center gap-2 text-slate-400 text-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                        {{ $center->phone }}
                    </div>
                    @endif
                    @if($center->email)
                    <div class="flex items-center gap-2 text-slate-400 text-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        {{ $center->email }}
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Stats --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-6 pt-6 border-t border-slate-700">
            <div class="text-center">
                <p class="text-3xl font-bold text-white">{{ $center->admins->count() }}</p>
                <p class="text-slate-500 text-xs mt-1">Asesores</p>
            </div>
            <div class="text-center">
                <p class="text-3xl font-bold text-white">{{ $center->clients->count() }}</p>
                <p class="text-slate-500 text-xs mt-1">Clientes</p>
            </div>
            <div class="text-center">
                <p class="text-3xl font-bold text-white">{{ $center->products()->count() }}</p>
                <p class="text-slate-500 text-xs mt-1">Productos</p>
            </div>
            <div class="text-center">
                <p class="text-3xl font-bold text-white">{{ $center->plans()->count() }}</p>
                <p class="text-slate-500 text-xs mt-1">Planes</p>
            </div>
        </div>
    </div>

    {{-- Equipo / Asesores --}}
    <div class="bg-slate-800 border border-slate-700 rounded-xl p-5">
        <h2 class="text-white font-semibold text-sm uppercase tracking-wider mb-4">Equipo del centro</h2>

        @if($center->admins->isEmpty())
        <p class="text-slate-600 text-sm text-center py-8">No hay asesores registrados.</p>
        @else
        <div class="space-y-2">
            @foreach($center->admins as $admin)
            <div class="flex items-center justify-between p-3 bg-slate-700/40 rounded-lg">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-full flex items-center justify-center text-xs font-bold text-white shrink-0"
                         style="background:{{ $center->owner_id === $admin->id ? '#0F6E56' : '#334155' }}">
                        {{ strtoupper(substr($admin->name, 0, 2)) }}
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <p class="text-white text-sm font-medium">{{ $admin->name }}</p>
                            @if($center->owner_id === $admin->id)
                            <span class="text-xs bg-teal-600/20 text-teal-400 px-2 py-0.5 rounded-full">Owner</span>
                            @endif
                            @if(!$admin->active)
                            <span class="text-xs bg-red-900/30 text-red-400 px-2 py-0.5 rounded-full">Deshabilitado</span>
                            @endif
                        </div>
                        <p class="text-slate-500 text-xs">{{ $admin->email }}</p>
                    </div>
                </div>

                {{-- Acciones solo para owner y solo sobre staff (no sobre sí mismo ni sobre otro owner) --}}
                @if($isOwner && $center->owner_id !== $admin->id && auth()->id() !== $admin->id)
                <form method="POST" action="{{ route('admin.centro.staff.toggle', $admin) }}">
                    @csrf @method('PATCH')
                    <button class="text-xs px-3 py-1.5 rounded-lg transition
                        {{ $admin->active
                            ? 'bg-red-900/30 text-red-400 hover:bg-red-900/50'
                            : 'bg-teal-700/30 text-teal-300 hover:bg-teal-700/50' }}">
                        {{ $admin->active ? 'Deshabilitar' : 'Habilitar' }}
                    </button>
                </form>
                @endif
            </div>
            @endforeach
        </div>
        @endif
    </div>

    {{-- Nota informativa para staff --}}
    @if(!$isOwner)
    <div class="bg-slate-800/50 border border-slate-700 rounded-xl p-4 flex items-start gap-3">
        <svg class="w-5 h-5 text-slate-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <p class="text-slate-500 text-sm">Para modificar la información del centro o gestionar asesores, contacta al admin owner del centro.</p>
    </div>
    @endif

</div>
@endsection