{{-- resources/views/dashboard/cliente.blade.php --}}
@extends('layouts.app')

@section('title', 'Mi Dashboard')
@section('page-title', 'Hola, {{ auth()->user()->name }}')
@section('page-subtitle', 'Tu resumen de salud en VitaSolutions')

@section('content')

{{-- Alerta cuestionario pendiente --}}
@if($pendingSurvey)
<div class="bg-amber-900/30 border border-amber-700/50 rounded-xl px-5 py-4 mb-6 flex items-center justify-between">
    <div class="flex items-center gap-3">
        <svg class="w-5 h-5 text-amber-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <div>
            <p class="text-sm font-medium text-amber-300">Tienes un cuestionario pendiente</p>
            <p class="text-xs text-amber-500">Protocolo: {{ $pendingSurvey->protocol->product->name }}</p>
        </div>
    </div>
    <a href="{{ route('cliente.cuestionarios.responder', $pendingSurvey) }}"
       class="text-xs bg-amber-600 hover:bg-amber-500 text-white px-4 py-2 rounded-lg transition-colors font-medium">
        Responder
    </a>
</div>
@endif

{{-- Últimas mediciones --}}
<div class="grid grid-cols-2 xl:grid-cols-4 gap-4 mb-8">
    @php
        $lastWeight = $latestReadings->firstWhere('device_type', 'scale');
        $lastBP     = $latestReadings->firstWhere('device_type', 'blood_pressure');
        $lastO2     = $latestReadings->firstWhere('device_type', 'oximeter');
        $lastGluc   = $latestReadings->firstWhere('device_type', 'glucometer');
    @endphp

    <div class="bg-slate-900 border border-slate-800 rounded-xl p-5">
        <p class="text-xs text-slate-500 mb-1">Peso</p>
        <p class="text-2xl font-semibold text-white">
            {{ $lastWeight ? $lastWeight->value_1 . ' kg' : '—' }}
        </p>
        <p class="text-xs text-slate-600 mt-1">
            {{ $lastWeight ? $lastWeight->measured_at->diffForHumans() : 'Sin registro' }}
        </p>
    </div>

    <div class="bg-slate-900 border border-slate-800 rounded-xl p-5">
        <p class="text-xs text-slate-500 mb-1">Presión arterial</p>
        <p class="text-2xl font-semibold text-white">
            {{ $lastBP ? $lastBP->value_1 . '/' . $lastBP->value_2 : '—' }}
        </p>
        <p class="text-xs text-slate-600 mt-1">
            {{ $lastBP ? $lastBP->measured_at->diffForHumans() : 'Sin registro' }}
        </p>
    </div>

    <div class="bg-slate-900 border border-slate-800 rounded-xl p-5">
        <p class="text-xs text-slate-500 mb-1">SpO2</p>
        <p class="text-2xl font-semibold text-white">
            {{ $lastO2 ? $lastO2->value_1 . '%' : '—' }}
        </p>
        <p class="text-xs text-slate-600 mt-1">
            {{ $lastO2 ? $lastO2->measured_at->diffForHumans() : 'Sin registro' }}
        </p>
    </div>

    <div class="bg-slate-900 border border-slate-800 rounded-xl p-5">
        <p class="text-xs text-slate-500 mb-1">Glucosa</p>
        <p class="text-2xl font-semibold text-white">
            {{ $lastGluc ? $lastGluc->value_1 . ' mg/dL' : '—' }}
        </p>
        <p class="text-xs text-slate-600 mt-1">
            {{ $lastGluc ? $lastGluc->measured_at->diffForHumans() : 'Sin registro' }}
        </p>
    </div>
</div>

<div class="grid grid-cols-1 xl:grid-cols-2 gap-6">

    {{-- Protocolos activos --}}
    <div class="bg-slate-900 border border-slate-800 rounded-xl">
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-800">
            <h2 class="text-sm font-semibold text-white">Mis suplementos activos</h2>
            <a href="{{ route('cliente.protocolos.index') }}" class="text-xs text-brand-400 hover:text-brand-300">
                Ver todos →
            </a>
        </div>
        <div class="divide-y divide-slate-800">
            @forelse($activeProtocols as $protocol)
            <div class="px-6 py-4">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <p class="text-sm font-medium text-white">{{ $protocol->product->name }}</p>
                        <p class="text-xs text-slate-500 mt-0.5">{{ $protocol->dose }} — {{ $protocol->frequency }}</p>
                        <p class="text-xs text-slate-600 mt-1">Desde {{ $protocol->started_at->format('d M Y') }}</p>
                    </div>
                    <span class="text-xs bg-brand-900/60 text-brand-400 border border-brand-800 px-2 py-0.5 rounded-full flex-shrink-0">
                        Activo
                    </span>
                </div>
            </div>
            @empty
            <div class="px-6 py-6 text-center text-slate-600 text-sm">Sin protocolos activos</div>
            @endforelse
        </div>
    </div>

    {{-- Accesos rápidos --}}
    <div class="bg-slate-900 border border-slate-800 rounded-xl p-6">
        <h2 class="text-sm font-semibold text-white mb-4">Acciones rápidas</h2>
        <div class="grid grid-cols-2 gap-3">
            <a href="{{ route('cliente.mediciones.create') }}"
               class="flex flex-col items-center gap-2 bg-slate-800 hover:bg-slate-700 border border-slate-700 rounded-xl p-4 transition-colors text-center">
                <svg class="w-6 h-6 text-brand-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                <span class="text-xs text-slate-300 font-medium">Registrar medición</span>
            </a>
            <a href="{{ route('cliente.cuestionarios.index') }}"
               class="flex flex-col items-center gap-2 bg-slate-800 hover:bg-slate-700 border border-slate-700 rounded-xl p-4 transition-colors text-center">
                <svg class="w-6 h-6 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                </svg>
                <span class="text-xs text-slate-300 font-medium">Ver cuestionarios</span>
            </a>
            <a href="{{ route('cliente.archivos.create') }}"
               class="flex flex-col items-center gap-2 bg-slate-800 hover:bg-slate-700 border border-slate-700 rounded-xl p-4 transition-colors text-center">
                <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                </svg>
                <span class="text-xs text-slate-300 font-medium">Subir análisis</span>
            </a>
            <a href="{{ route('cliente.perfil') }}"
               class="flex flex-col items-center gap-2 bg-slate-800 hover:bg-slate-700 border border-slate-700 rounded-xl p-4 transition-colors text-center">
                <svg class="w-6 h-6 text-pink-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                <span class="text-xs text-slate-300 font-medium">Mi perfil</span>
            </a>
        </div>
    </div>

</div>
@endsection
