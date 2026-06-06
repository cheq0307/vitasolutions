@extends('layouts.app')

@section('title', 'Catálogo de productos')
@section('subtitle', 'Gestión de productos nutraceuticos')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-white">Catálogo de productos</h2>
            <p class="text-sm text-slate-400 mt-1">{{ $productos->total() }} producto(s) registrado(s)</p>
        </div>
        <a href="{{ route('admin.productos.create') }}"
           class="inline-flex items-center gap-2 bg-teal-600 hover:bg-teal-500 text-white text-sm font-semibold px-4 py-2.5 rounded-xl transition-colors shadow-lg">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Nuevo producto
        </a>
    </div>

    {{-- Flash — solo uno --}}
    @if(session('success'))
        <div class="flex items-center gap-3 bg-teal-600/20 border border-teal-500/40 text-teal-300 rounded-xl px-4 py-3 text-sm font-medium">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    {{-- Tabla --}}
    <div class="bg-slate-800 rounded-2xl shadow-xl border border-slate-700 overflow-hidden">
        @if($productos->isEmpty())
            <div class="text-center py-16">
                <svg class="w-12 h-12 mx-auto mb-3 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"/>
                </svg>
                <p class="text-slate-300 font-medium">Sin productos registrados</p>
                <p class="text-slate-500 text-sm mt-1">Comienza agregando el primer producto al catálogo.</p>
            </div>
        @else
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-slate-700">
                        <th class="px-5 py-4 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Producto</th>
                        <th class="px-5 py-4 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Marca</th>
                        <th class="px-5 py-4 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Categoría</th>
                        <th class="px-5 py-4 text-center text-xs font-semibold text-slate-400 uppercase tracking-wider">Estado</th>
                        <th class="px-5 py-4 text-center text-xs font-semibold text-slate-400 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700/60">
                    @foreach($productos as $producto)
                    <tr class="hover:bg-slate-700/40 transition-colors">

                        {{-- Imagen + Nombre --}}
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-3">
                                @if($producto->image_url)
                                    <img src="{{ $producto->image_url }}"
                                         alt="{{ $producto->name }}"
                                         class="w-10 h-10 rounded-lg object-cover border border-slate-600"
                                         onerror="this.src='https://placehold.co/40x40/1e293b/475569?text=?'">
                                @else
                                    <div class="w-10 h-10 rounded-lg bg-slate-700 border border-slate-600 flex items-center justify-center shrink-0">
                                        <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                  d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"/>
                                        </svg>
                                    </div>
                                @endif
                                <span class="font-semibold text-white">{{ $producto->name }}</span>
                            </div>
                        </td>

                        <td class="px-5 py-4 text-slate-300">{{ $producto->brand }}</td>

                        <td class="px-5 py-4">
                            <span class="inline-block bg-slate-700 text-slate-300 text-xs font-medium px-3 py-1 rounded-full border border-slate-600">
                                {{ $producto->category }}
                            </span>
                        </td>

                        <td class="px-5 py-4 text-center">
                            @if($producto->active)
                                <span class="inline-block bg-teal-600/20 text-teal-400 border border-teal-500/30 text-xs font-semibold px-3 py-1 rounded-full">Activo</span>
                            @else
                                <span class="inline-block bg-slate-700 text-slate-400 border border-slate-600 text-xs font-semibold px-3 py-1 rounded-full">Inactivo</span>
                            @endif
                        </td>

                        <td class="px-5 py-4">
                            <div class="flex items-center justify-center gap-3">
                                <a href="{{ route('admin.productos.edit', $producto) }}"
                                   class="text-teal-400 hover:text-teal-300 font-semibold transition-colors text-xs">
                                    Editar
                                </a>
                                <span class="text-slate-600">|</span>
                                <form action="{{ route('admin.productos.destroy', $producto) }}" method="POST"
                                      onsubmit="return confirm('¿Eliminar este producto?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="text-red-400 hover:text-red-300 font-semibold transition-colors text-xs">
                                        Eliminar
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            {{-- Paginación --}}
            @if($productos->hasPages())
                <div class="px-5 py-4 border-t border-slate-700">
                    {{ $productos->links() }}
                </div>
            @endif
        @endif
    </div>

</div>
@endsection