@extends('layouts.app')
@section('title', 'Clientes')
@section('page-title', 'Clientes')
@section('page-subtitle', 'Listado de todos los clientes registrados')
@section('content')

<div class="flex justify-end mb-4">
    <a href="{{ route('admin.clientes.create') }}"
       class="bg-green-600 hover:bg-green-500 text-white text-sm font-medium px-4 py-2 rounded-lg transition">
        + Nuevo cliente
    </a>
</div>

<div class="bg-slate-900 border border-slate-800 rounded-xl overflow-hidden">
    <table class="w-full text-sm">
        <thead>
            <tr class="border-b border-slate-800">
                <th class="text-left px-6 py-3 text-slate-400 font-medium">Cliente</th>
                <th class="text-left px-6 py-3 text-slate-400 font-medium">Teléfono</th>
                <th class="text-left px-6 py-3 text-slate-400 font-medium">Estado</th>
                <th class="text-left px-6 py-3 text-slate-400 font-medium">Registro</th>
                <th class="px-6 py-3"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-800">
            @forelse($clientes as $cliente)
            <tr class="hover:bg-slate-800/50 transition">
                <td class="px-6 py-4">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-green-700 rounded-full flex items-center justify-center text-xs font-semibold text-white">
                            {{ strtoupper(substr($cliente->name, 0, 2)) }}
                        </div>
                        <div>
                            <p class="font-medium text-white">{{ $cliente->name }}</p>
                            <p class="text-xs text-slate-500">{{ $cliente->email }}</p>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4 text-slate-400">{{ $cliente->phone ?? '—' }}</td>
                <td class="px-6 py-4">
                    @if($cliente->active)
                        <span class="text-xs bg-green-900/50 text-green-400 border border-green-800 px-2 py-0.5 rounded-full">Activo</span>
                    @else
                        <span class="text-xs bg-slate-800 text-slate-500 px-2 py-0.5 rounded-full">Inactivo</span>
                    @endif
                </td>
                <td class="px-6 py-4 text-slate-500 text-xs">{{ $cliente->created_at->format('d M Y') }}</td>
                <td class="px-6 py-4 text-right">
                    <a href="{{ route('admin.clientes.show', $cliente) }}"
                       class="text-xs text-slate-400 hover:text-white px-3 py-1.5 rounded hover:bg-slate-700 transition">
                        Ver expediente →
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="px-6 py-8 text-center text-slate-600">
                    Aún no hay clientes registrados.
                    <a href="{{ route('admin.clientes.create') }}" class="text-green-400 hover:underline ml-1">Registrar el primero</a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
