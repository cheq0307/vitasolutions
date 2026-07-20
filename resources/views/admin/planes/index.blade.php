@extends('layouts.app')

@section('title', 'Planes')
@section('subtitle', 'Catálogo de planes y suscripciones')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div></div>
    <a href="{{ route('admin.planes.create') }}"
       class="inline-flex items-center gap-2 px-5 py-3 md:px-4 md:py-2 rounded-lg text-base md:text-sm font-semibold text-white transition"
       style="background:#0F6E56">
        <svg class="w-5 h-5 md:w-4 md:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Nuevo plan
    </a>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 mb-8">
    @forelse($plans as $plan)
    <div class="bg-slate-800 border border-slate-700 rounded-xl p-6 flex flex-col gap-4 min-h-[360px]">
        {{-- Header --}}
        <div class="flex items-start justify-between">
            <div class="flex-1">
                <span class="text-xs font-semibold px-2 py-1 rounded-full
                    {{ $plan->type === 'monthly' ? 'bg-teal-600/20 text-teal-300' :
                       ($plan->type === 'custom'  ? 'bg-purple-600/20 text-purple-300' :
                                                    'bg-slate-600/40 text-slate-400') }}">
                    {{ \App\Models\Plan::$types[$plan->type] }}
                </span>
                <h3 class="mt-3 text-white font-bold text-lg">{{ $plan->name }}</h3>
                @if($plan->description)
                <p class="text-slate-400 text-sm mt-1 line-clamp-2">{{ $plan->description }}</p>
                @endif
            </div>
            <span class="text-xs px-3 py-1 rounded-full {{ $plan->active ? 'bg-teal-600/20 text-teal-400' : 'bg-slate-700 text-slate-500' }}">
                {{ $plan->active ? 'Activo' : 'Inactivo' }}
            </span>
        </div>

        {{-- Precio --}}
        <div class="flex items-end gap-1">
            <span class="text-3xl font-bold text-white">${{ number_format($plan->price, 2) }}</span>
            @if($plan->type !== 'visit')
            <span class="text-slate-500 text-base mb-1">/mes</span>
            @endif
        </div>

        {{-- Productos --}}
        <div class="flex flex-wrap gap-2">
            @forelse($plan->products->take(3) as $product)
            <span class="text-xs bg-slate-700 text-slate-300 px-2 py-1 rounded-full">{{ $product->name }}</span>
            @empty
            <span class="text-xs text-slate-600 italic">Sin productos</span>
            @endforelse
            @if($plan->products->count() > 3)
            <span class="text-xs bg-slate-700 text-slate-400 px-2 py-1 rounded-full">+{{ $plan->products->count() - 3 }}</span>
            @endif
        </div>

        {{-- Stats --}}
        <div class="flex items-center gap-4 pt-3 border-t border-slate-700 text-sm text-slate-500">
            <span>{{ $plan->active_clients_count }} activo(s)</span>
            <span>{{ $plan->client_plans_count }} total</span>
        </div>

        {{-- Acciones --}}
        <div class="flex items-center gap-2 pt-2 flex-col sm:flex-row">
            <a href="{{ route('admin.planes.show', $plan) }}"
               class="w-full text-center text-sm font-medium py-2 rounded-lg bg-slate-700 text-slate-300 hover:bg-slate-600 transition">
                Ver detalle
            </a>
            <a href="{{ route('admin.planes.edit', $plan) }}"
               class="w-full text-center text-sm font-medium py-2 rounded-lg bg-teal-700/30 text-teal-300 hover:bg-teal-700/50 transition">
                Editar
            </a>
            <form method="POST" action="{{ route('admin.planes.destroy', $plan) }}"
                  onsubmit="return confirm('¿Eliminar el plan «{{ $plan->name }}»?')" class="w-full">
                @csrf @method('DELETE')
                <button class="w-full py-2 rounded-lg bg-red-900/30 text-red-400 hover:bg-red-900/50 transition text-sm font-medium">
                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    Eliminar
                </button>
            </form>
        </div>
    </div>
    @empty
    <div class="col-span-full text-center py-20 text-slate-600">
        <svg class="w-14 h-14 mx-auto mb-4 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
        </svg>
        <p class="text-lg">No hay planes registrados</p>
        <a href="{{ route('admin.planes.create') }}" class="mt-4 inline-block text-teal-400 text-base hover:underline">Crear el primero</a>
    </div>
    @endforelse
</div>

<div class="mt-8">
    {{ $plans->links() }}
</div>

{{-- Acceso rápido a suscripciones --}}
<div class="mt-8 flex flex-col sm:flex-row items-center sm:items-center justify-between gap-4 border-t border-slate-800 pt-6">
    <p class="text-slate-500 text-base">Gestionar asignaciones de planes a clientes</p>
    <a href="{{ route('admin.client-planes.index') }}"
       class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-3 md:px-4 md:py-2 rounded-lg text-base md:text-sm font-medium bg-slate-800 text-slate-300 border border-slate-700 hover:border-slate-500 transition">
        Ver suscripciones activas
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
    </a>
</div>
@endsection