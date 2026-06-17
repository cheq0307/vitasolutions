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
            {{-- Grid de tarjetas --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5 p-6">
                @foreach($productos as $producto)
                <div class="bg-slate-700/50 border border-slate-600 rounded-xl hover:border-slate-500 hover:bg-slate-700/70 transition-all overflow-hidden flex flex-col">
                    
                    {{-- Imagen --}}
                    <div class="relative h-40 bg-slate-800 overflow-hidden">
                        @if($producto->image_url || $producto->image_path)
                            <img src="{{ $producto->getResolvedImageUrl() }}"
                                 alt="{{ $producto->name }}"
                                 class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                        @else
                            <div class="w-full h-full bg-slate-800 flex items-center justify-center">
                                <svg class="w-16 h-16 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                          d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"/>
                                </svg>
                            </div>
                        @endif
                        
                        {{-- Badge estado --}}
                        <div class="absolute top-3 right-3">
                            @if($producto->active)
                                <span class="inline-block bg-teal-600/90 text-teal-100 border border-teal-400 text-xs font-semibold px-2.5 py-1 rounded-full">Activo</span>
                            @else
                                <span class="inline-block bg-slate-700 text-slate-400 border border-slate-600 text-xs font-semibold px-2.5 py-1 rounded-full">Inactivo</span>
                            @endif
                        </div>
                    </div>

                    {{-- Contenido --}}
                    <div class="p-4 flex-1 flex flex-col">
                        <h3 class="font-semibold text-white text-sm mb-1">{{ $producto->name }}</h3>
                        
                        {{-- Marca --}}
                        @if($producto->brand)
                            <p class="text-xs text-slate-400 mb-3">{{ $producto->brand }}</p>
                        @endif

                        {{-- Categoría --}}
                        <span class="inline-block bg-slate-600/40 text-slate-300 text-xs font-medium px-2.5 py-1 rounded-full mb-3 w-fit border border-slate-500/30">
                            {{ $producto->category }}
                        </span>

                        {{-- Precio y Stock --}}
                        <div class="flex items-center gap-3 mb-4 text-sm">
                            <div class="flex-1">
                                <p class="text-slate-400 text-xs mb-0.5">Precio</p>
                                <p class="text-white font-semibold">${{ number_format($producto->price, 2) }}</p>
                            </div>
                            <div class="flex-1">
                                <p class="text-slate-400 text-xs mb-0.5">Stock</p>
                                <p class="text-white font-semibold">{{ $producto->stock ?? 0 }} unid.</p>
                            </div>
                        </div>

                        {{-- Acciones --}}
                        <div class="flex items-center gap-2 pt-3 border-t border-slate-600">
                            <a href="{{ route('admin.productos.edit', $producto) }}"
                               class="flex-1 text-center text-teal-400 hover:text-teal-300 font-semibold text-xs py-2 px-3 rounded-lg hover:bg-slate-600/40 transition-colors">
                                Editar
                            </a>
                            <form action="{{ route('admin.productos.destroy', $producto) }}" method="POST" class="flex-1"
                                  onsubmit="return confirm('¿Eliminar este producto?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="w-full text-red-400 hover:text-red-300 font-semibold text-xs py-2 px-3 rounded-lg hover:bg-slate-600/40 transition-colors">
                                    Eliminar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Paginación --}}
            @if($productos->hasPages())
                <div class="px-6 py-4 border-t border-slate-700 bg-slate-700/30">
                    {{ $productos->links() }}
                </div>
            @endif
        @endif
    </div>

</div>
@endsection