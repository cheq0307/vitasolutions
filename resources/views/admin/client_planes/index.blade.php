@extends('layouts.app')

@section('title', 'Suscripciones')
@section('subtitle', 'Planes asignados a clientes')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div></div>
    <a href="{{ route('admin.client-planes.create') }}"
       class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold text-white transition"
       style="background:#0F6E56">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Asignar plan
    </a>
</div>

<div class="bg-slate-800 border border-slate-700 rounded-xl overflow-hidden">
    <table class="w-full text-sm">
        <thead>
            <tr class="border-b border-slate-700">
                <th class="px-4 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Cliente</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Plan</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Inicio</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Vence</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Estado</th>
                <th class="px-4 py-3"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-700/50">
            @forelse($subscriptions as $sub)
            <tr class="hover:bg-slate-700/30 transition">
                <td class="px-4 py-3">
                    <div class="flex items-center gap-2">
                        <div class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold text-white shrink-0" style="background:#0F6E56">
                            {{ strtoupper(substr($sub->user->name, 0, 2)) }}
                        </div>
                        <span class="text-white font-medium">{{ $sub->user->name }}</span>
                    </div>
                </td>
                <td class="px-4 py-3">
                    <a href="{{ route('admin.planes.show', $sub->plan) }}" class="text-teal-400 hover:underline">
                        {{ $sub->plan->name }}
                    </a>
                    <p class="text-slate-500 text-xs">${{ number_format($sub->plan->price, 2) }}/mes</p>
                </td>
                <td class="px-4 py-3 text-slate-300">{{ $sub->starts_at->isoFormat('D MMM YY') }}</td>
                <td class="px-4 py-3">
                    @if($sub->ends_at)
                    @php $days = $sub->daysRemaining(); @endphp
                    <span class="{{ $days < 7 ? 'text-red-400' : 'text-slate-300' }}">
                        {{ $sub->ends_at->isoFormat('D MMM YY') }}
                    </span>
                    @if($days < 7 && $days >= 0)
                    <p class="text-xs text-red-400">Vence en {{ $days }} días</p>
                    @elseif($days < 0)
                    <p class="text-xs text-red-500">Vencido</p>
                    @endif
                    @else
                    <span class="text-slate-500">—</span>
                    @endif
                </td>
                <td class="px-4 py-3">
                    <span class="text-xs px-2 py-1 rounded-full font-medium
                        {{ $sub->status === 'active'    ? 'bg-teal-600/20 text-teal-300' :
                           ($sub->status === 'paused'   ? 'bg-yellow-600/20 text-yellow-300' :
                                                          'bg-red-600/20 text-red-400') }}">
                        {{ \App\Models\ClientPlan::$statuses[$sub->status] }}
                    </span>
                </td>
                <td class="px-4 py-3 text-right">
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
                <td colspan="6" class="text-center py-14 text-slate-600">
                    No hay suscripciones registradas.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">{{ $subscriptions->links() }}</div>
@endsection