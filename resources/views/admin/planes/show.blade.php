@extends('layouts.app')

@section('title', $plan->name)
@section('subtitle', \App\Models\Plan::$types[$plan->type] . ' — ' . ($plan->active ? 'Activo' : 'Inactivo'))

@section('content')
<div class="max-w-4xl space-y-6">

    {{-- Header actions --}}
    <div class="flex items-center gap-3 justify-end">
        <a href="{{ route('admin.client-planes.create', ['plan_id' => $plan->id]) }}"
           class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium bg-slate-800 border border-slate-700 text-slate-300 hover:border-teal-500 hover:text-teal-300 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
            </svg>
            Asignar a cliente
        </a>
        <a href="{{ route('admin.planes.edit', $plan) }}"
           class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold text-white transition"
           style="background:#0F6E56">
            Editar plan
        </a>
    </div>

    {{-- Info + precio --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="md:col-span-2 bg-slate-800 border border-slate-700 rounded-xl p-5">
            <p class="text-slate-400 text-sm mb-3">{{ $plan->description ?: 'Sin descripción.' }}</p>
            <div class="flex items-end gap-2 mt-4">
                <span class="text-3xl font-bold text-white">${{ number_format($plan->price, 2) }}</span>
                @if($plan->type !== 'visit')
                <span class="text-slate-500 mb-1">/mes</span>
                @endif
            </div>
        </div>
        <div class="bg-slate-800 border border-slate-700 rounded-xl p-5 flex flex-col justify-between">
            <div>
                <p class="text-slate-500 text-xs uppercase tracking-wider">Clientes activos</p>
                <p class="text-3xl font-bold text-white mt-1">{{ $plan->activeClients->count() }}</p>
            </div>
            <div class="mt-4">
                <p class="text-slate-500 text-xs uppercase tracking-wider">Total histórico</p>
                <p class="text-xl font-semibold text-slate-300 mt-1">{{ $plan->clientPlans->count() }}</p>
            </div>
        </div>
    </div>

    {{-- Productos --}}
    <div class="bg-slate-800 border border-slate-700 rounded-xl p-5">
        <h2 class="text-white font-semibold text-sm uppercase tracking-wider mb-4">Productos del plan</h2>
        @if($plan->products->isEmpty())
        <p class="text-slate-600 text-sm text-center py-8">Este plan no tiene productos asignados.</p>
        @else
        <div class="space-y-3">
            @foreach($plan->products as $product)
            <div class="flex items-start gap-4 p-3 bg-slate-700/40 rounded-lg">
                @if($product->image_url)
                <img src="{{ $product->image_url }}" class="w-10 h-10 rounded-lg object-cover shrink-0" alt="{{ $product->name }}">
                @else
                <div class="w-10 h-10 rounded-lg bg-slate-600 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                </div>
                @endif
                <div class="flex-1 min-w-0">
                    <p class="text-white font-medium text-sm">{{ $product->name }}</p>
                    <p class="text-slate-400 text-xs mt-0.5">
                        {{ $product->pivot->dose ?: '—' }} ·
                        {{ $product->pivot->quantity }}x/día ·
                        {{ \App\Models\Plan::$schedules[$product->pivot->schedule] }}
                        @if($product->pivot->time) a las {{ $product->pivot->time }} @endif
                    </p>
                    <div class="flex items-center gap-2 mt-1">
                        <span class="text-xs bg-slate-600 text-slate-300 px-2 py-0.5 rounded-full">
                            {{ \App\Models\Plan::$places[$product->pivot->consumption_place] }}
                        </span>
                        @if($product->pivot->notes)
                        <span class="text-xs text-slate-500 italic">{{ $product->pivot->notes }}</span>
                        @endif
                    </div>
                </div>
                <span class="text-slate-400 text-sm font-medium shrink-0">${{ number_format($product->price, 2) }}</span>
            </div>
            @endforeach
        </div>
        @endif
    </div>

    {{-- Clientes activos --}}
    @if($plan->activeClients->count())
    <div class="bg-slate-800 border border-slate-700 rounded-xl p-5">
        <h2 class="text-white font-semibold text-sm uppercase tracking-wider mb-4">Clientes en este plan</h2>
        <div class="space-y-2">
            @foreach($plan->activeClients as $cp)
            <div class="flex items-center justify-between p-3 bg-slate-700/40 rounded-lg">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold text-white" style="background:#0F6E56">
                        {{ strtoupper(substr($cp->user->name, 0, 2)) }}
                    </div>
                    <div>
                        <p class="text-white text-sm font-medium">{{ $cp->user->name }}</p>
                        <p class="text-slate-500 text-xs">Desde {{ $cp->starts_at->isoFormat('D MMM YYYY') }}</p>
                    </div>
                </div>
                <div class="text-right">
                    @if($cp->ends_at)
                    @php $days = $cp->daysRemaining(); @endphp
                    <span class="text-xs {{ $days < 7 ? 'text-red-400' : 'text-slate-400' }}">
                        {{ $days > 0 ? "Vence en {$days} días" : 'Vencido' }}
                    </span>
                    @else
                    <span class="text-xs text-slate-500">Sin fecha de término</span>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

</div>
@endsection