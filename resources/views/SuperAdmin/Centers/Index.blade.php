@extends('layouts.app')

@section('title', 'Centros')
@section('subtitle', 'Gestión de centros VitaSolutions')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div></div>
    <a href="{{ route('superadmin.centers.create') }}"
       class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold text-white transition"
       style="background:#0F6E56">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Nuevo centro
    </a>
</div>

<div class="bg-slate-800 border border-slate-700 rounded-xl overflow-hidden">
    <table class="w-full text-sm">
        <thead>
            <tr class="border-b border-slate-700">
                <th class="px-4 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Centro</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Contacto</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Admins</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Clientes</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Estado</th>
                <th class="px-4 py-3"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-700/50">
            @forelse($centers as $center)
            <tr class="hover:bg-slate-700/30 transition">
                <td class="px-4 py-3">
                    <div class="flex items-center gap-3">
                        @if($center->logo_url)
                        <img src="{{ $center->logo_url }}" class="w-8 h-8 rounded-lg object-cover shrink-0">
                        @else
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0" style="background:#0F6E56">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                            </svg>
                        </div>
                        @endif
                        <div>
                            <p class="text-white font-medium">{{ $center->name }}</p>
                            @if($center->address)
                            <p class="text-slate-500 text-xs">{{ $center->address }}</p>
                            @endif
                        </div>
                    </div>
                </td>
                <td class="px-4 py-3">
                    <p class="text-slate-300 text-xs">{{ $center->email ?: '—' }}</p>
                    <p class="text-slate-500 text-xs">{{ $center->phone ?: '' }}</p>
                </td>
                <td class="px-4 py-3 text-slate-300">{{ $center->admins_count }}</td>
                <td class="px-4 py-3 text-slate-300">{{ $center->clients_count }}</td>
                <td class="px-4 py-3">
                    <span class="text-xs px-2 py-1 rounded-full {{ $center->active ? 'bg-teal-600/20 text-teal-400' : 'bg-slate-700 text-slate-500' }}">
                        {{ $center->active ? 'Activo' : 'Inactivo' }}
                    </span>
                </td>
                <td class="px-4 py-3 text-right">
                    <div class="flex items-center justify-end gap-2">
                        <a href="{{ route('superadmin.centers.show', $center) }}"
                           class="text-xs px-3 py-1 rounded-lg bg-slate-700 text-slate-300 hover:bg-slate-600 transition">
                            Ver
                        </a>
                        <a href="{{ route('superadmin.centers.edit', $center) }}"
                           class="text-xs px-3 py-1 rounded-lg bg-teal-700/30 text-teal-300 hover:bg-teal-700/50 transition">
                            Editar
                        </a>
                        <form method="POST" action="{{ route('superadmin.centers.destroy', $center) }}"
                              onsubmit="return confirm('¿Eliminar el centro «{{ $center->name }}»?')">
                            @csrf @method('DELETE')
                            <button class="text-xs px-3 py-1 rounded-lg bg-red-900/30 text-red-400 hover:bg-red-900/50 transition">
                                Eliminar
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center py-14 text-slate-600">
                    No hay centros registrados.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">{{ $centers->links() }}</div>
@endsection