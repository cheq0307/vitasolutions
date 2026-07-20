@extends('layouts.app')
@section('title', 'Clientes')
@section('page-title', 'Clientes')
@section('page-subtitle', 'Listado de todos los clientes registrados')
@section('content')

<div class="flex justify-end mb-6">
    <a href="{{ route('admin.clientes.create') }}"
       class="bg-green-600 hover:bg-green-500 text-white text-base md:text-sm font-medium px-5 py-3 md:px-4 md:py-2 rounded-lg transition">
        + Nuevo cliente
    </a>
</div>

{{-- DESKTOP: Tabla --}}
<div class="hidden md:block bg-slate-900 border border-slate-800 rounded-xl overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full">
        <thead>
            <tr class="border-b border-slate-800">
                <th class="text-left px-6 py-4 text-slate-400 font-medium text-sm">Cliente</th>
                <th class="text-left px-6 py-4 text-slate-400 font-medium text-sm">Teléfono</th>
                <th class="text-left px-6 py-4 text-slate-400 font-medium text-sm">Estado</th>
                <th class="text-left px-6 py-4 text-slate-400 font-medium text-sm">Registro</th>
                <th class="px-6 py-4"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-800">
            @forelse($clientes as $cliente)
            <tr class="hover:bg-slate-800/50 transition">
                <td class="px-6 py-4">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 bg-green-700 rounded-full flex items-center justify-center text-xs font-semibold text-white">
                            {{ strtoupper(substr($cliente->name, 0, 2)) }}
                        </div>
                        <div>
                            <p class="font-medium text-white text-sm">{{ $cliente->name }}</p>
                            <p class="text-xs text-slate-500">{{ $cliente->email }}</p>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4 text-slate-400 text-sm">{{ $cliente->phone ?? '—' }}</td>
                <td class="px-6 py-4">
                    @if($cliente->active)
                        <span class="text-xs bg-green-900/50 text-green-400 border border-green-800 px-2 py-1 rounded-full">Activo</span>
                    @else
                        <span class="text-xs bg-slate-800 text-slate-500 px-2 py-1 rounded-full">Inactivo</span>
                    @endif
                </td>
                <td class="px-6 py-4 text-slate-500 text-xs">{{ $cliente->created_at->format('d M Y') }}</td>
                <td class="px-6 py-4 text-right">
                    <a href="{{ route('admin.clientes.show', $cliente) }}"
                       class="text-sm text-slate-400 hover:text-white px-3 py-2 rounded hover:bg-slate-700 transition">
                        Ver expediente →
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="px-6 py-12 text-center text-slate-600">
                    Aún no hay clientes registrados.
                    <a href="{{ route('admin.clientes.create') }}" class="text-green-400 hover:underline ml-1">Registrar el primero</a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    </div>
</div>

{{-- MOBILE: Cards --}}
<div class="md:hidden space-y-4">
    @forelse($clientes as $cliente)
    <div class="bg-slate-900 border border-slate-800 rounded-xl p-5 space-y-4">
        {{-- Header --}}
        <div class="flex items-start gap-4">
            <div class="w-12 h-12 bg-green-700 rounded-full flex items-center justify-center text-sm font-semibold text-white flex-shrink-0">
                {{ strtoupper(substr($cliente->name, 0, 2)) }}
            </div>
            <div class="flex-1 min-w-0">
                <p class="font-semibold text-white text-lg">{{ $cliente->name }}</p>
                <p class="text-sm text-slate-400 break-all">{{ $cliente->email }}</p>
            </div>
        </div>

        {{-- Info --}}
        <div class="space-y-3 border-t border-slate-800 pt-4">
            <div class="flex justify-between items-center">
                <span class="text-slate-400 text-sm">Teléfono:</span>
                <span class="text-white font-medium">{{ $cliente->phone ?? '—' }}</span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-slate-400 text-sm">Estado:</span>
                @if($cliente->active)
                    <span class="text-xs bg-green-900/50 text-green-400 border border-green-800 px-2 py-1 rounded-full">Activo</span>
                @else
                    <span class="text-xs bg-slate-800 text-slate-500 px-2 py-1 rounded-full">Inactivo</span>
                @endif
            </div>
            <div class="flex justify-between items-center">
                <span class="text-slate-400 text-sm">Registro:</span>
                <span class="text-white text-sm">{{ $cliente->created_at->format('d M Y') }}</span>
            </div>
        </div>

        {{-- Acciones --}}
        <div class="border-t border-slate-800 pt-4">
            <a href="{{ route('admin.clientes.show', $cliente) }}"
               class="block w-full text-center bg-green-700/30 text-green-300 hover:bg-green-700/50 font-medium py-3 rounded-lg transition text-base">
                Ver expediente
            </a>
        </div>
    </div>
    @empty
    <div class="text-center py-12 text-slate-600">
        <svg class="w-12 h-12 mx-auto mb-3 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
        </svg>
        <p class="text-base">Aún no hay clientes registrados.</p>
        <a href="{{ route('admin.clientes.create') }}" class="text-green-400 hover:underline text-sm mt-2 inline-block">Registrar el primero</a>
    </div>
    @endforelse
</div>

<div class="mt-8">
    {{ $clientes->links() }}
</div>
@endsection
