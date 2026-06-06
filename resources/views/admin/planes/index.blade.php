@extends('layouts.app')

@section('title', 'Planes')
@section('subtitle', 'Catálogo de planes y suscripciones')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div></div>
    <a href="{{ route('admin.planes.create') }}"
       class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold text-white transition"
       style="background:#0F6E56">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Nuevo plan
    </a>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4 mb-6">
    @forelse($plans as $plan)
    <div class="bg-slate-800 border border-slate-700 rounded-xl p-5 flex flex-col gap-3">
        {{-- Header --}}
        <div class="flex items-start justify-between">
            <div>
                <span class="text-xs font-semibold px-2 py-0.5 rounded-full
                    {{ $plan->type === 'monthly' ? 'bg-teal-600/20 text-teal-300' :
                       ($plan->type === 'custom'  ? 'bg-purple-600/20 text-purple-300' :
                                                    'bg-slate-600/40 text-slate-400') }}">
                    {{ \App\Models\Plan::$types[$plan->type] }}
                </span>
                <h3 class="mt-2 text-white font-semibold text-base">{{ $plan->name }}</h3>
                @if($plan->description)
                <p class="text-slate-400 text-xs mt-0.5 line-clamp-2">{{ $plan->description }}</p>
                @endif
            </div>
            <span class="text-xs px-2 py-0.5 rounded-full {{ $plan->active ? 'bg-teal-600/20 text-teal-400' : 'bg-slate-700 text-slate-500' }}">
                {{ $plan->active ? 'Activo' : 'Inactivo' }}
            </span>
        </div>

        {{-- Precio --}}
        <div class="flex items-end gap-1">
            <span class="text-2xl font-bold text-white">${{ number_format($plan->price, 2) }}</span>
            @if($plan->type !== 'visit')
            <span class="text-slate-500 text-sm mb-0.5">/mes</span>
            @endif
        </div>

        {{-- Productos --}}
        <div class="flex flex-wrap gap-1">
            @forelse($plan->products->take(4) as $product)
            <span class="text-xs bg-slate-700 text-slate-300 px-2 py-0.5 rounded-full">{{ $product->name }}</span>
            @empty
            <span class="text-xs text-slate-600 italic">Sin productos asignados</span>
            @endforelse
            @if($plan->products->count() > 4)
            <span class="text-xs bg-slate-700 text-slate-400 px-2 py-0.5 rounded-full">+{{ $plan->products->count() - 4 }} más</span>
            @endif
        </div>

        {{-- Stats --}}
        <div class="flex items-center gap-4 pt-1 border-t border-slate-700 text-xs text-slate-500">
            <span>{{ $plan->active_clients_count }} activo(s)</span>
            <span>{{ $plan->client_plans_count }} total</span>
        </div>

        {{-- Acciones --}}
        <div class="flex items-center gap-2 pt-1">
            <a href="{{ route('admin.planes.show', $plan) }}"
               class="flex-1 text-center text-xs font-medium py-1.5 rounded-lg bg-slate-700 text-slate-300 hover:bg-slate-600 transition">
                Ver detalle
            </a>
            <a href="{{ route('admin.planes.edit', $plan) }}"
               class="flex-1 text-center text-xs font-medium py-1.5 rounded-lg bg-teal-700/30 text-teal-300 hover:bg-teal-700/50 transition">
                Editar
            </a>
            <form method="POST" action="{{ route('admin.planes.destroy', $plan) }}"
                  onsubmit="return confirm('¿Eliminar el plan «{{ $plan->name }}»?')">
                @csrf @method('DELETE')
                <button class="px-3 py-1.5 rounded-lg bg-red-900/30 text-red-400 hover:bg-red-900/50 transition text-xs">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </button>
            </form>
        </div>
    </div>
    @empty
    <div class="col-span-3 text-center py-16 text-slate-600">
        <svg class="w-10 h-10 mx-auto mb-3 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
        </svg>
        <p>No hay planes registrados</p>
        <a href="{{ route('admin.planes.create') }}" class="mt-3 inline-block text-teal-400 text-sm hover:underline">Crear el primero</a>
    </div>
    @endforelse
</div>

{{ $plans->links() }}

{{-- Acceso rápido a suscripciones --}}
<div class="mt-6 flex items-center justify-between border-t border-slate-800 pt-6">
    <p class="text-slate-500 text-sm">Gestionar asignaciones de planes a clientes</p>
    <a href="{{ route('admin.client-planes.index') }}"
       class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium bg-slate-800 text-slate-300 border border-slate-700 hover:border-slate-500 transition">
        Ver suscripciones activas
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
    </a>
</div>
@endsection