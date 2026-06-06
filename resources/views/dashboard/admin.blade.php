{{-- resources/views/dashboard/admin.blade.php --}}
@extends('layouts.app')

@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Resumen general de VitaSolutions')

@section('content')

{{-- KPI Cards --}}
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-8">

    <div class="bg-slate-900 border border-slate-800 rounded-xl p-5">
        <p class="text-xs text-slate-500 uppercase tracking-wide mb-1">Clientes activos</p>
        <p class="text-3xl font-semibold text-white">{{ $stats['active_clients'] }}</p>
        <p class="text-xs text-brand-400 mt-1">de {{ $stats['total_clients'] }} registrados</p>
    </div>

    <div class="bg-slate-900 border border-slate-800 rounded-xl p-5">
        <p class="text-xs text-slate-500 uppercase tracking-wide mb-1">Protocolos activos</p>
        <p class="text-3xl font-semibold text-white">{{ $stats['active_protocols'] }}</p>
        <p class="text-xs text-slate-500 mt-1">suplementos en curso</p>
    </div>

    <div class="bg-slate-900 border border-slate-800 rounded-xl p-5">
        <p class="text-xs text-slate-500 uppercase tracking-wide mb-1">Mediciones hoy</p>
        <p class="text-3xl font-semibold text-white">{{ $stats['readings_today'] }}</p>
        <p class="text-xs text-slate-500 mt-1">registros del día</p>
    </div>

    <div class="bg-slate-900 border border-slate-800 rounded-xl p-5">
        <p class="text-xs text-slate-500 uppercase tracking-wide mb-1">Cuestionarios pendientes</p>
        <p class="text-3xl font-semibold text-amber-400">{{ $stats['pending_surveys'] }}</p>
        <p class="text-xs text-slate-500 mt-1">sin responder</p>
    </div>

</div>

<div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

    {{-- Clientes recientes --}}
    <div class="xl:col-span-2 bg-slate-900 border border-slate-800 rounded-xl">
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-800">
            <h2 class="text-sm font-semibold text-white">Clientes recientes</h2>
            <a href="{{ route('admin.clientes.index') }}" class="text-xs text-brand-400 hover:text-brand-300">
                Ver todos →
            </a>
        </div>
        <div class="divide-y divide-slate-800">
            @forelse($recentClients as $client)
            <div class="flex items-center gap-4 px-6 py-4">
                <div class="w-9 h-9 bg-brand-700 rounded-full flex items-center justify-center text-xs font-semibold text-white flex-shrink-0">
                    {{ strtoupper(substr($client->name, 0, 2)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-white truncate">{{ $client->name }}</p>
                    <p class="text-xs text-slate-500 truncate">{{ $client->email }}</p>
                </div>
                <div class="flex items-center gap-2">
                    @if($client->active)
                    <span class="text-xs bg-brand-900/60 text-brand-400 border border-brand-800 px-2 py-0.5 rounded-full">
                        Activo
                    </span>
                    @else
                    <span class="text-xs bg-slate-800 text-slate-500 px-2 py-0.5 rounded-full">
                        Inactivo
                    </span>
                    @endif
                    <a href="{{ route('admin.clientes.show', $client) }}"
                       class="text-xs text-slate-400 hover:text-white px-2 py-1 rounded hover:bg-slate-800 transition-colors">
                        Ver
                    </a>
                </div>
            </div>
            @empty
            <div class="px-6 py-8 text-center text-slate-600 text-sm">
                Aún no hay clientes registrados.
                <a href="{{ route('admin.clientes.create') }}" class="text-brand-400 hover:underline ml-1">
                    Registrar el primero
                </a>
            </div>
            @endforelse
        </div>
    </div>

    {{-- Actividad reciente --}}
    <div class="bg-slate-900 border border-slate-800 rounded-xl">
        <div class="px-6 py-4 border-b border-slate-800">
            <h2 class="text-sm font-semibold text-white">Actividad reciente</h2>
        </div>
        <div class="px-6 py-4 space-y-4">
            @forelse($recentActivity as $item)
            <div class="flex gap-3">
                <div class="w-2 h-2 bg-brand-500 rounded-full mt-1.5 flex-shrink-0"></div>
                <div>
                    <p class="text-sm text-slate-300">{{ $item['description'] }}</p>
                    <p class="text-xs text-slate-600 mt-0.5">{{ $item['time'] }}</p>
                </div>
            </div>
            @empty
            <p class="text-sm text-slate-600 text-center py-4">Sin actividad reciente</p>
            @endforelse
        </div>
    </div>

</div>

@endsection
