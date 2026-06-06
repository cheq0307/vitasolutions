@extends('layouts.app')
@section('title', $cliente->name)
@section('page-title', $cliente->name)
@section('page-subtitle', 'Expediente completo del cliente')
@section('content')

{{-- Header del cliente --}}
<div class="bg-slate-900 border border-slate-800 rounded-xl p-6 mb-6">
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 bg-green-700 rounded-full flex items-center justify-center text-xl font-bold text-white">
                {{ strtoupper(substr($cliente->name, 0, 2)) }}
            </div>
            <div>
                <h2 class="text-xl font-semibold text-white">{{ $cliente->name }}</h2>
                <p class="text-slate-400 text-sm">{{ $cliente->email }}</p>
                @if($cliente->phone)
                    <p class="text-slate-500 text-xs mt-0.5">{{ $cliente->phone }}</p>
                @endif
            </div>
        </div>
        <div class="flex items-center gap-3">
            @if($cliente->active)
                <span class="text-xs bg-green-900/50 text-green-400 border border-green-800 px-3 py-1 rounded-full">Activo</span>
            @else
                <span class="text-xs bg-slate-800 text-slate-500 px-3 py-1 rounded-full">Inactivo</span>
            @endif
            <a href="{{ route('admin.clientes.salud.edit', $cliente) }}"
               class="text-sm text-teal-400 hover:text-white px-4 py-2 rounded-lg hover:bg-slate-800 transition border border-teal-700">
                Perfil de salud
            </a>
            <a href="{{ route('admin.clientes.edit', $cliente) }}"
               class="text-sm text-slate-400 hover:text-white px-4 py-2 rounded-lg hover:bg-slate-800 transition border border-slate-700">
                Editar
            </a>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

    {{-- Expediente de salud --}}
    <div class="xl:col-span-2 space-y-6">

        {{-- Perfil clínico --}}
        <div class="bg-slate-900 border border-slate-800 rounded-xl">
            <div class="px-6 py-4 border-b border-slate-800 flex items-center justify-between">
                <h3 class="text-sm font-semibold text-white">Perfil de salud</h3>
                <a href="{{ route('admin.clientes.salud.edit', $cliente) }}"
                   class="text-xs text-teal-400 hover:text-teal-300 transition">Editar →</a>
            </div>
            <div class="p-6">
                @if($cliente->healthProfile)
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        <div>
                            <p class="text-xs text-slate-500 mb-1">Tipo de sangre</p>
                            <p class="text-sm text-white font-medium">{{ $cliente->healthProfile->blood_type ?? '—' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500 mb-1">Sexo</p>
                            <p class="text-sm text-white font-medium">{{ $cliente->healthProfile->sex ? ucfirst($cliente->healthProfile->sex) : '—' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500 mb-1">Fecha de nacimiento</p>
                            <p class="text-sm text-white font-medium">{{ $cliente->healthProfile->birth_date?->format('d M Y') ?? '—' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500 mb-1">Estatura</p>
                            <p class="text-sm text-white font-medium">{{ $cliente->healthProfile->height_cm ? $cliente->healthProfile->height_cm . ' cm' : '—' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500 mb-1">Peso</p>
                            <p class="text-sm text-white font-medium">{{ $cliente->healthProfile->weight_kg ? $cliente->healthProfile->weight_kg . ' kg' : '—' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500 mb-1">IMC</p>
                            <p class="text-sm text-white font-medium">{{ $cliente->healthProfile->imc ?? '—' }}
                                @if($cliente->healthProfile->imc)
                                    <span class="text-xs text-slate-500 ml-1">{{ $cliente->healthProfile->imc_label }}</span>
                                @endif
                            </p>
                        </div>
                        <div class="col-span-2 md:col-span-3">
                            <p class="text-xs text-slate-500 mb-1">Alergias</p>
                            <p class="text-sm text-white">{{ $cliente->healthProfile->allergies ?? '—' }}</p>
                        </div>
                        <div class="col-span-2 md:col-span-3">
                            <p class="text-xs text-slate-500 mb-1">Padecimientos crónicos</p>
                            <p class="text-sm text-white">{{ $cliente->healthProfile->chronic_conditions ?? '—' }}</p>
                        </div>
                        <div class="col-span-2 md:col-span-3">
                            <p class="text-xs text-slate-500 mb-1">Medicamentos actuales</p>
                            <p class="text-sm text-white">{{ $cliente->healthProfile->current_medications ?? '—' }}</p>
                        </div>
                    </div>
                @else
                    <p class="text-slate-500 text-sm">Sin perfil de salud registrado.</p>
                @endif
            </div>
        </div>

        {{-- Últimas mediciones --}}
        <div class="bg-slate-900 border border-slate-800 rounded-xl">
            <div class="px-6 py-4 border-b border-slate-800">
                <h3 class="text-sm font-semibold text-white">Últimas mediciones</h3>
            </div>
            <div class="divide-y divide-slate-800">
                @forelse($cliente->deviceReadings->take(5) as $reading)
                <div class="px-6 py-3 flex items-center justify-between">
                    <p class="text-sm text-slate-300">{{ $reading->device_label }}</p>
                    <p class="text-sm font-medium text-white">{{ $reading->reading_display }}</p>
                    <p class="text-xs text-slate-500">{{ $reading->measured_at->diffForHumans() }}</p>
                </div>
                @empty
                <div class="px-6 py-6 text-center text-slate-600 text-sm">Sin mediciones registradas.</div>
                @endforelse
            </div>
        </div>

    </div>

    {{-- Panel derecho --}}
    <div class="space-y-6">

        {{-- Protocolos activos --}}
        <div class="bg-slate-900 border border-slate-800 rounded-xl">
            <div class="px-6 py-4 border-b border-slate-800">
                <h3 class="text-sm font-semibold text-white">Protocolos activos</h3>
            </div>
            <div class="divide-y divide-slate-800">
                @forelse($cliente->protocols->where('status', 'active') as $protocol)
                <div class="px-6 py-4">
                    <p class="text-sm font-medium text-white">{{ $protocol->product->name }}</p>
                    <p class="text-xs text-slate-500 mt-0.5">{{ $protocol->dose }} — {{ $protocol->frequency }}</p>
                    <p class="text-xs text-slate-600 mt-1">Desde {{ $protocol->started_at->format('d M Y') }}</p>
                </div>
                @empty
                <div class="px-6 py-6 text-center text-slate-600 text-sm">Sin protocolos activos.</div>
                @endforelse
            </div>
        </div>

        {{-- Archivos de salud --}}
        <div class="bg-slate-900 border border-slate-800 rounded-xl">
            <div class="px-6 py-4 border-b border-slate-800">
                <h3 class="text-sm font-semibold text-white">Archivos de salud</h3>
            </div>
            <div class="divide-y divide-slate-800">
                @forelse($cliente->healthFiles->take(5) as $file)
                <div class="px-6 py-3 flex items-center justify-between">
                    <p class="text-sm text-slate-300 truncate">{{ $file->title }}</p>
                    <a href="{{ $file->cloud_url }}" target="_blank"
                       class="text-xs text-green-400 hover:underline ml-2 flex-shrink-0">Ver</a>
                </div>
                @empty
                <div class="px-6 py-6 text-center text-slate-600 text-sm">Sin archivos subidos.</div>
                @endforelse
            </div>
        </div>

    </div>
</div>

<div class="mt-4">
    <a href="{{ route('admin.clientes.index') }}" class="text-sm text-slate-500 hover:text-white">← Volver a clientes</a>
</div>
@endsection