@extends('layouts.app')

@section('title', 'Suscripciones')
@section('subtitle', 'Planes asignados a clientes')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div></div>
    <a href="{{ route('admin.client-planes.create') }}"
       class="inline-flex items-center gap-2 px-5 py-3 md:px-4 md:py-2 rounded-lg text-base md:text-sm font-semibold text-white transition"
       style="background:#0F6E56">
        <svg class="w-5 h-5 md:w-4 md:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Asignar plan
    </a>
</div>

{{-- DESKTOP: Tabla --}}
<div class="hidden md:block bg-slate-800 border border-slate-700 rounded-xl overflow-hidden">
    <table class="w-full text-sm">
        <thead>
            <tr class="border-b border-slate-700">
                <th class="px-6 py-4 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Cliente</th>
                <th class="px-6 py-4 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Plan</th>
                <th class="px-6 py-4 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Inicio</th>
                <th class="px-6 py-4 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Vence</th>
                <th class="px-6 py-4 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Estado</th>
                <th class="px-6 py-4"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-700/50">
            @forelse($subscriptions as $sub)
            <tr class="hover:bg-slate-700/30 transition">
                <td class="px-6 py-4">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold text-white shrink-0" style="background:#0F6E56">
                            {{ strtoupper(substr($sub->user->name, 0, 2)) }}
                        </div>
                        <span class="text-white font-medium">{{ $sub->user->name }}</span>
                    </div>
                </td>
                <td class="px-6 py-4">
                    <a href="{{ route('admin.planes.show', $sub->plan) }}" class="text-teal-400 hover:underline text-sm">
                        {{ $sub->plan->name }}
                    </a>
                    <p class="text-slate-500 text-xs">${{ number_format($sub->plan->price, 2) }}/mes</p>
                </td>
                <td class="px-6 py-4 text-slate-300 text-sm">{{ $sub->starts_at->isoFormat('D MMM YY') }}</td>
                <td class="px-6 py-4">
                    @if($sub->ends_at)
                    @php $days = $sub->daysRemaining(); @endphp
                    <span class="{{ $days < 7 ? 'text-red-400' : 'text-slate-300' }} text-sm">
                        {{ $sub->ends_at->isoFormat('D MMM YY') }}
                    </span>
                    @if($days < 7 && $days >= 0)
                    <p class="text-xs text-red-400">Vence en {{ $days }} días</p>
                    @elseif($days < 0)
                    <p class="text-xs text-red-500">Vencido</p>
                    @endif
                    @else
                    <span class="text-slate-500 text-sm">—</span>
                    @endif
                </td>
                <td class="px-6 py-4">
                    <span class="text-xs px-2 py-1 rounded-full font-medium
                        {{ $sub->status === 'active'    ? 'bg-teal-600/20 text-teal-300' :
                           ($sub->status === 'paused'   ? 'bg-yellow-600/20 text-yellow-300' :
                                                          'bg-red-600/20 text-red-400') }}">
                        {{ \App\Models\ClientPlan::$statuses[$sub->status] }}
                    </span>
                </td>
                <td class="px-6 py-4 text-right">
                    <div class="flex items-center justify-end gap-2">
                        <a href="{{ route('admin.client-planes.edit', $sub) }}"
                           class="text-xs px-3 py-1 rounded-lg bg-slate-700 text-slate-300 hover:bg-slate-600 transition">
                            Editar
                        </a>
                        <form method="POST" action="{{ route('admin.client-planes.destroy', $sub) }}"
                              onsubmit="return confirm('¿Quitar este plan del cliente?')">
                            @csrf @method('DELETE')
                            <button class="text-xs px-3 py-1 rounded-lg bg-red-900/30 text-red-400 hover:bg-red-900/50 transition">
                                Quitar
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center py-16 text-slate-600 text-base">
                    No hay suscripciones registradas.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- MOBILE: Cards --}}
<div class="md:hidden space-y-4">
    @forelse($subscriptions as $sub)
    <div class="bg-slate-800 border border-slate-700 rounded-xl p-5 space-y-4">
        {{-- Header --}}
        <div class="flex items-start gap-3">
            <div class="w-12 h-12 rounded-full flex items-center justify-center text-sm font-bold text-white shrink-0" style="background:#0F6E56">
                {{ strtoupper(substr($sub->user->name, 0, 2)) }}
            </div>
            <div class="flex-1 min-w-0">
                <p class="font-semibold text-white text-lg">{{ $sub->user->name }}</p>
                <a href="{{ route('admin.planes.show', $sub->plan) }}" class="text-teal-400 hover:underline text-base">
                    {{ $sub->plan->name }}
                </a>
                <p class="text-slate-500 text-sm">${{ number_format($sub->plan->price, 2) }}/mes</p>
            </div>
        </div>

        {{-- Info --}}
        <div class="space-y-3 border-t border-slate-700 pt-4">
            <div class="flex justify-between items-start">
                <span class="text-slate-400 text-sm">Inicio:</span>
                <span class="text-white font-medium">{{ $sub->starts_at->isoFormat('D MMM YY') }}</span>
            </div>
            <div class="flex justify-between items-start">
                <span class="text-slate-400 text-sm">Vence:</span>
                @if($sub->ends_at)
                    @php $days = $sub->daysRemaining(); @endphp
                    <div class="text-right">
                        <span class="{{ $days < 7 ? 'text-red-400' : 'text-white' }} font-medium">
                            {{ $sub->ends_at->isoFormat('D MMM YY') }}
                        </span>
                        @if($days < 7 && $days >= 0)
                        <p class="text-xs text-red-400">Vence en {{ $days }} días</p>
                        @elseif($days < 0)
                        <p class="text-xs text-red-500">Vencido</p>
                        @endif
                    </div>
                @else
                    <span class="text-slate-500">—</span>
                @endif
            </div>
            <div class="flex justify-between items-center">
                <span class="text-slate-400 text-sm">Estado:</span>
                <span class="text-xs px-3 py-1 rounded-full font-medium
                    {{ $sub->status === 'active'    ? 'bg-teal-600/20 text-teal-300' :
                       ($sub->status === 'paused'   ? 'bg-yellow-600/20 text-yellow-300' :
                                                      'bg-red-600/20 text-red-400') }}">
                    {{ \App\Models\ClientPlan::$statuses[$sub->status] }}
                </span>
            </div>
        </div>

        {{-- Acciones --}}
        <div class="border-t border-slate-700 pt-4 flex gap-3">
            <a href="{{ route('admin.client-planes.edit', $sub) }}"
               class="flex-1 text-center text-sm font-medium py-3 rounded-lg bg-slate-700 text-slate-300 hover:bg-slate-600 transition">
                Editar
            </a>
            <form method="POST" action="{{ route('admin.client-planes.destroy', $sub) }}"
                  onsubmit="return confirm('¿Quitar este plan del cliente?')" class="flex-1">
                @csrf @method('DELETE')
                <button class="w-full text-sm font-medium py-3 rounded-lg bg-red-900/30 text-red-400 hover:bg-red-900/50 transition">
                    Quitar
                </button>
            </form>
        </div>
    </div>
    @empty
    <div class="text-center py-12 text-slate-600">
        <svg class="w-12 h-12 mx-auto mb-3 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
        </svg>
        <p class="text-base">No hay suscripciones registradas.</p>
        <a href="{{ route('admin.client-planes.create') }}" class="text-teal-400 hover:underline text-sm mt-2 inline-block">Asignar la primera</a>
    </div>
    @endforelse
</div>

<div class="mt-8">
    {{ $subscriptions->links() }}
</div>
@endsection