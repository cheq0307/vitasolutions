@extends('layouts.app')

@section('title', $center->name)
@section('subtitle', 'Detalle del centro')

@section('content')
<div class="max-w-4xl space-y-6">

    {{-- Header --}}
    <div class="flex items-center gap-3 justify-end">
        <a href="{{ route('superadmin.centers.edit', $center) }}"
           class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold text-white transition"
           style="background:#0F6E56">
            Editar centro
        </a>
    </div>

    {{-- Info + stats --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="md:col-span-2 bg-slate-800 border border-slate-700 rounded-xl p-5">
            <div class="flex items-center gap-4">
                @if($center->logo_url)
                <img src="{{ $center->logo_url }}" class="h-14 rounded-xl object-contain bg-slate-700 p-1">
                @else
                <div class="w-14 h-14 rounded-xl flex items-center justify-center" style="background:#0F6E56">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                    </svg>
                </div>
                @endif
                <div>
                    <h2 class="text-white font-bold text-lg">{{ $center->name }}</h2>
                    <p class="text-slate-400 text-sm">{{ $center->address ?: 'Sin dirección' }}</p>
                    <div class="flex items-center gap-4 mt-1">
                        @if($center->email)<p class="text-slate-500 text-xs">{{ $center->email }}</p>@endif
                        @if($center->phone)<p class="text-slate-500 text-xs">{{ $center->phone }}</p>@endif
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-slate-800 border border-slate-700 rounded-xl p-5 grid grid-cols-2 gap-3">
            @foreach(['admins' => 'Admins', 'clients' => 'Clientes', 'products' => 'Productos', 'plans' => 'Planes'] as $key => $label)
            <div>
                <p class="text-slate-500 text-xs">{{ $label }}</p>
                <p class="text-2xl font-bold text-white">{{ $stats[$key] }}</p>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Owner actual --}}
    <div class="bg-slate-800 border border-slate-700 rounded-xl p-5">
        <h2 class="text-white font-semibold text-sm uppercase tracking-wider mb-4">Admin Owner</h2>

        @if($center->owner)
        <div class="flex items-center justify-between p-3 bg-teal-600/10 border border-teal-600/30 rounded-lg">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-full flex items-center justify-center text-xs font-bold text-white shrink-0" style="background:#0F6E56">
                    {{ strtoupper(substr($center->owner->name, 0, 2)) }}
                </div>
                <div>
                    <p class="text-white font-medium text-sm">{{ $center->owner->name }}</p>
                    <p class="text-slate-500 text-xs">{{ $center->owner->email }}</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <span class="text-xs bg-teal-600/20 text-teal-400 px-2 py-0.5 rounded-full font-medium">Owner</span>
                {{-- Superadmin puede deshabilitar al owner --}}
                <form method="POST" action="{{ route('superadmin.centers.toggle-admin', [$center, $center->owner]) }}"
                      onsubmit="return confirm('¿Deshabilitar al admin owner? El centro quedará sin owner activo.')">
                    @csrf @method('PATCH')
                    <button class="text-xs px-3 py-1 rounded-lg {{ $center->owner->active ? 'bg-red-900/30 text-red-400 hover:bg-red-900/50' : 'bg-teal-700/30 text-teal-300 hover:bg-teal-700/50' }} transition">
                        {{ $center->owner->active ? 'Deshabilitar' : 'Habilitar' }}
                    </button>
                </form>
            </div>
        </div>
        @else
        <p class="text-slate-600 text-sm text-center py-4">Este centro no tiene admin owner asignado.</p>
        @endif

        {{-- Asignar owner desde admins existentes --}}
        @if($adminsDisponibles->isNotEmpty())
        <div class="mt-4 pt-4 border-t border-slate-700">
            <p class="text-slate-400 text-sm mb-3">Cambiar owner — selecciona un asesor del centro:</p>
            <form method="POST" action="{{ route('superadmin.centers.assign-owner', $center) }}" class="flex gap-3">
                @csrf
                <select name="owner_id" class="flex-1 bg-slate-700 border border-slate-600 text-white rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-teal-500">
                    <option value="">— Selecciona asesor —</option>
                    @foreach($adminsDisponibles as $admin)
                    <option value="{{ $admin->id }}">{{ $admin->name }} — {{ $admin->email }}</option>
                    @endforeach
                </select>
                <button type="submit"
                    class="px-4 py-2 rounded-lg text-sm font-medium text-white transition"
                    style="background:#0F6E56">
                    Asignar
                </button>
            </form>
        </div>
        @endif
    </div>

    {{-- Todos los admins / staff --}}
    <div class="bg-slate-800 border border-slate-700 rounded-xl p-5">
        <h2 class="text-white font-semibold text-sm uppercase tracking-wider mb-4">Equipo completo</h2>
        @if($center->admins->isEmpty())
        <p class="text-slate-600 text-sm text-center py-6">Sin asesores registrados.</p>
        @else
        <div class="space-y-2">
            @foreach($center->admins as $admin)
            @if($admin->id !== $center->owner_id)
            <div class="flex items-center justify-between p-3 bg-slate-700/40 rounded-lg">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold text-white shrink-0 bg-slate-600">
                        {{ strtoupper(substr($admin->name, 0, 2)) }}
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <p class="text-white text-sm font-medium">{{ $admin->name }}</p>
                            <span class="text-xs text-slate-500">Staff</span>
                            @if(!$admin->active)
                            <span class="text-xs bg-red-900/30 text-red-400 px-2 py-0.5 rounded-full">Deshabilitado</span>
                            @endif
                        </div>
                        <p class="text-slate-500 text-xs">{{ $admin->email }}</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('superadmin.centers.toggle-admin', [$center, $admin]) }}">
                    @csrf @method('PATCH')
                    <button class="text-xs px-3 py-1 rounded-lg {{ $admin->active ? 'bg-red-900/30 text-red-400 hover:bg-red-900/50' : 'bg-teal-700/30 text-teal-300 hover:bg-teal-700/50' }} transition">
                        {{ $admin->active ? 'Deshabilitar' : 'Habilitar' }}
                    </button>
                </form>
            </div>
            @endif
            @endforeach
        </div>
        @endif
    </div>

    {{-- Clientes --}}
    @if($center->clients->isNotEmpty())
    <div class="bg-slate-800 border border-slate-700 rounded-xl p-5">
        <h2 class="text-white font-semibold text-sm uppercase tracking-wider mb-4">Clientes ({{ $center->clients->count() }})</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
            @foreach($center->clients as $client)
            <div class="flex items-center gap-3 p-3 bg-slate-700/40 rounded-lg">
                <div class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold text-white shrink-0 bg-slate-600">
                    {{ strtoupper(substr($client->name, 0, 2)) }}
                </div>
                <div>
                    <p class="text-slate-300 text-sm">{{ $client->name }}</p>
                    <p class="text-slate-500 text-xs">{{ $client->email }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

</div>
@endsection