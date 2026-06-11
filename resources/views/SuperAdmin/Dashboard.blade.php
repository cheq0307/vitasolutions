@extends('layouts.app')

@section('title', 'Super Admin')
@section('subtitle', 'Panel de control global — VitaSolutions')

@section('content')
<div class="space-y-6">

    {{-- Stats globales --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-slate-800 border border-slate-700 rounded-xl p-5">
            <p class="text-slate-400 text-xs uppercase tracking-wider">Centros</p>
            <p class="text-3xl font-bold text-white mt-1">{{ $stats['centers'] }}</p>
        </div>
        <div class="bg-slate-800 border border-slate-700 rounded-xl p-5">
            <p class="text-slate-400 text-xs uppercase tracking-wider">Administradores</p>
            <p class="text-3xl font-bold text-white mt-1">{{ $stats['admins'] }}</p>
        </div>
        <div class="bg-slate-800 border border-slate-700 rounded-xl p-5">
            <p class="text-slate-400 text-xs uppercase tracking-wider">Clientes totales</p>
            <p class="text-3xl font-bold text-white mt-1">{{ $stats['clients'] }}</p>
        </div>
        <div class="bg-slate-800 border border-slate-700 rounded-xl p-5">
            <p class="text-slate-400 text-xs uppercase tracking-wider">Productos totales</p>
            <p class="text-3xl font-bold text-white mt-1">{{ $stats['products'] }}</p>
        </div>
    </div>

    {{-- Centros recientes --}}
    <div class="bg-slate-800 border border-slate-700 rounded-xl p-5">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-white font-semibold">Centros registrados</h2>
            <a href="{{ route('superadmin.centers.create') }}"
               class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg text-sm font-medium text-white transition"
               style="background:#0F6E56">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Nuevo centro
            </a>
        </div>

        <div class="space-y-3">
            @forelse($centers as $center)
            <div class="flex items-center justify-between p-3 bg-slate-700/40 rounded-lg">
                <div class="flex items-center gap-3">
                    @if($center->logo_url)
                    <img src="{{ $center->logo_url }}" class="w-8 h-8 rounded-lg object-cover">
                    @else
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background:#0F6E56">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                    </div>
                    @endif
                    <div>
                        <p class="text-white font-medium text-sm">{{ $center->name }}</p>
                        <p class="text-slate-500 text-xs">{{ $center->admins_count }} admin · {{ $center->clients_count }} clientes</p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-xs px-2 py-0.5 rounded-full {{ $center->active ? 'bg-teal-600/20 text-teal-400' : 'bg-slate-700 text-slate-500' }}">
                        {{ $center->active ? 'Activo' : 'Inactivo' }}
                    </span>
                    <a href="{{ route('superadmin.centers.show', $center) }}"
                       class="text-xs px-3 py-1 rounded-lg bg-slate-700 text-slate-300 hover:bg-slate-600 transition">
                        Ver
                    </a>
                </div>
            </div>
            @empty
            <p class="text-slate-600 text-sm text-center py-8">No hay centros registrados aún.</p>
            @endforelse
        </div>

        @if($centers->count() >= 5)
        <div class="mt-4 text-center">
            <a href="{{ route('superadmin.centers.index') }}" class="text-teal-400 text-sm hover:underline">
                Ver todos los centros →
            </a>
        </div>
        @endif
    </div>

</div>
@endsection