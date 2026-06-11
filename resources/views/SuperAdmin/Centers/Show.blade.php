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

    {{-- Info --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="md:col-span-3 bg-slate-800 border border-slate-700 rounded-xl p-5 space-y-3">
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
                    <p class="text-slate-400 text-sm">{{ $center->address ?: 'Sin dirección registrada' }}</p>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-3 pt-2">
                <div>
                    <p class="text-slate-500 text-xs">Correo</p>
                    <p class="text-slate-300 text-sm">{{ $center->email ?: '—' }}</p>
                </div>
                <div>
                    <p class="text-slate-500 text-xs">Teléfono</p>
                    <p class="text-slate-300 text-sm">{{ $center->phone ?: '—' }}</p>
                </div>
            </div>
        </div>

        <div class="bg-slate-800 border border-slate-700 rounded-xl p-5 flex flex-col gap-4">
            @foreach(['admins' => 'Admins', 'clients' => 'Clientes', 'products' => 'Productos', 'plans' => 'Planes'] as $key => $label)
            <div>
                <p class="text-slate-500 text-xs">{{ $label }}</p>
                <p class="text-2xl font-bold text-white">{{ $stats[$key] }}</p>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Admins del centro --}}
    <div class="bg-slate-800 border border-slate-700 rounded-xl p-5">
        <h2 class="text-white font-semibold text-sm uppercase tracking-wider mb-4">Administradores</h2>
        @if($center->admins->isEmpty())
        <p class="text-slate-600 text-sm text-center py-6">Sin administradores asignados.</p>
        @else
        <div class="space-y-2">
            @foreach($center->admins as $admin)
            <div class="flex items-center gap-3 p-3 bg-slate-700/40 rounded-lg">
                <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold text-white shrink-0" style="background:#0F6E56">
                    {{ strtoupper(substr($admin->name, 0, 2)) }}
                </div>
                <div>
                    <p class="text-white text-sm font-medium">{{ $admin->name }}</p>
                    <p class="text-slate-500 text-xs">{{ $admin->email }}</p>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>

    {{-- Clientes del centro --}}
    <div class="bg-slate-800 border border-slate-700 rounded-xl p-5">
        <h2 class="text-white font-semibold text-sm uppercase tracking-wider mb-4">Clientes</h2>
        @if($center->clients->isEmpty())
        <p class="text-slate-600 text-sm text-center py-6">Sin clientes registrados.</p>
        @else
        <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
            @foreach($center->clients as $client)
            <div class="flex items-center gap-3 p-3 bg-slate-700/40 rounded-lg">
                <div class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold text-white shrink-0 bg-slate-600">
                    {{ strtoupper(substr($client->name, 0, 2)) }}
                </div>
                <p class="text-slate-300 text-sm">{{ $client->name }}</p>
            </div>
            @endforeach
        </div>
        @endif
    </div>

</div>
@endsection