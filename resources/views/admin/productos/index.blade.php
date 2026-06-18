@extends('layouts.app')

@section('title', 'Catálogo de productos')
@section('subtitle', 'Gestión de productos nutraceuticos')

@section('content')
<div class="space-y-6">

    {{-- Header con título y botón --}}
    <div class="flex items-start justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-white">Catálogo de productos</h1>
            <p class="text-sm text-slate-400 mt-1">{{ $productos->total() }} producto(s) registrado(s)</p>
        </div>
        <a href="{{ route('admin.productos.create') }}"
           class="inline-flex items-center gap-2 bg-teal-600 hover:bg-teal-500 text-white font-semibold px-4 py-2.5 rounded-xl transition-colors shadow-lg whitespace-nowrap">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Nuevo producto
        </a>
    </div>

    {{-- Barra de búsqueda y filtros --}}
    <div class="flex flex-col sm:flex-row gap-3">
        <div class="flex-1 relative">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <input type="text" placeholder="Buscar por nombre o SKU..."
                   class="w-full pl-10 pr-4 py-2.5 bg-slate-800 text-slate-100 border border-slate-700 rounded-lg focus:outline-none focus:border-teal-500 text-sm">
        </div>
        
        <select class="px-4 py-2.5 bg-slate-800 text-slate-300 border border-slate-700 rounded-lg focus:outline-none focus:border-teal-500 text-sm">
            <option value="">Todas las categorías</option>
            @foreach($categories as $cat)
                <option value="{{ $cat }}">{{ $cat }}</option>
            @endforeach
        </select>

        <select class="px-4 py-2.5 bg-slate-800 text-slate-300 border border-slate-700 rounded-lg focus:outline-none focus:border-teal-500 text-sm">
            <option value="">Todos los estados</option>
            <option value="active">Activos</option>
            <option value="inactive">Inactivos</option>
            <option value="low">Bajo stock</option>
            <option value="out">Agotado</option>
        </select>
    </div>

    {{-- Contenedor de tarjetas horizontales --}}
    <div class="space-y-3">
        @if($productos->isEmpty())
            <div class="text-center py-16 bg-slate-800 rounded-2xl border border-slate-700">
                <svg class="w-12 h-12 mx-auto mb-3 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"/>
                </svg>
                <p class="text-slate-300 font-medium">Sin productos registrados</p>
                <p class="text-slate-500 text-sm mt-1">Comienza agregando el primer producto al catálogo.</p>
            </div>
        @else
            @foreach($productos as $producto)
            <div class="bg-slate-800 border border-slate-700 rounded-xl hover:border-slate-600 hover:bg-slate-800/80 transition-all p-6">
                <div class="flex gap-6 items-start">
                    
                    {{-- Imagen pequeña (120x120) --}}
                    <div class="flex-shrink-0">
                        @if($producto->image_url || $producto->image_path)
                            <img src="{{ $producto->getResolvedImageUrl() }}"
                                 alt="{{ $producto->name }}"
                                 class="w-24 h-24 rounded-lg object-cover border border-slate-600">
                        @else
                            <div class="w-24 h-24 rounded-lg bg-slate-700 flex items-center justify-center border border-slate-600">
                                <svg class="w-12 h-12 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                          d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"/>
                                </svg>
                            </div>
                        @endif
                    </div>

                    {{-- Información principal (izquierda) --}}
                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between gap-4 mb-3">
                            <div class="flex-1">
                                <h3 class="text-lg font-bold text-white mb-1 truncate">{{ $producto->name }}</h3>
                                @if($producto->sku)
                                    <p class="text-xs text-slate-500 mb-2">SKU: <span class="text-slate-400 font-mono">{{ $producto->sku }}</span></p>
                                @endif
                                <div class="flex gap-2 items-center text-sm text-slate-400 mb-3">
                                    @if($producto->brand)
                                        <span>{{ $producto->brand }}</span>
                                        <span class="text-slate-600">•</span>
                                    @endif
                                    <span class="inline-block bg-slate-700 text-slate-300 px-2.5 py-0.5 rounded-full text-xs border border-slate-600">
                                        {{ $producto->category }}
                                    </span>
                                </div>
                            </div>

                            {{-- Estado (arriba derecha) --}}
                            <div class="flex flex-col items-end gap-1">
                                @if($producto->active)
                                    <span class="inline-block bg-teal-600/20 text-teal-400 border border-teal-500/30 text-xs font-semibold px-3 py-1 rounded-full">● Activo</span>
                                @else
                                    <span class="inline-block bg-slate-700 text-slate-400 border border-slate-600 text-xs font-semibold px-3 py-1 rounded-full">● Inactivo</span>
                                @endif
                                
                                {{-- Estado de stock --}}
                                @if($producto->stock_status === 'agotado')
                                    <span class="text-xs font-semibold text-red-400">⚠ Agotado</span>
                                @elseif($producto->stock_status === 'bajo')
                                    <span class="text-xs font-semibold text-yellow-400">⚠ Bajo stock</span>
                                @endif
                            </div>
                        </div>

                        {{-- Grid de información financiera --}}
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-4 pb-4 border-b border-slate-700">
                            <div>
                                <p class="text-xs text-slate-500 mb-1">Stock</p>
                                <p class="text-lg font-bold text-white">{{ $producto->stock ?? 0 }} <span class="text-sm text-slate-400">unid.</span></p>
                            </div>
                            <div>
                                <p class="text-xs text-slate-500 mb-1">Precio</p>
                                <p class="text-lg font-bold text-white">${{ number_format($producto->price, 2) }} <span class="text-sm text-slate-400">MXN</span></p>
                            </div>
                            @if($producto->cost)
                            <div>
                                <p class="text-xs text-slate-500 mb-1">Costo</p>
                                <p class="text-lg font-bold text-slate-300">${{ number_format($producto->cost, 2) }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-slate-500 mb-1">Ganancia</p>
                                <p class="text-lg font-bold {{ $producto->gain > 0 ? 'text-green-400' : 'text-slate-400' }}">
                                    ${{ number_format($producto->gain, 2) }}
                                </p>
                            </div>
                            @else
                            <div class="text-xs text-slate-500">
                                <p>Última actualización</p>
                                <p class="text-slate-400">{{ $producto->updated_at->format('d/m/Y') }}</p>
                            </div>
                            @endif
                        </div>

                        {{-- Acciones (botones agrupados) --}}
                        <div class="flex gap-2">
                            <a href="{{ route('admin.productos.edit', $producto) }}"
                               class="inline-flex items-center gap-2 px-4 py-2 bg-teal-600/20 hover:bg-teal-600/30 text-teal-400 rounded-lg transition-colors font-medium text-sm border border-teal-500/30">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                Editar
                            </a>
                            <button type="button" onclick="confirmDelete(this)"
                                    class="inline-flex items-center gap-2 px-4 py-2 bg-red-600/20 hover:bg-red-600/30 text-red-400 rounded-lg transition-colors font-medium text-sm border border-red-500/30">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                                Eliminar
                            </button>

                            {{-- Formulario oculto para eliminar --}}
                            <form action="{{ route('admin.productos.destroy', $producto) }}" method="POST" style="display: none;">
                                @csrf
                                @method('DELETE')
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach

            {{-- Paginación --}}
            @if($productos->hasPages())
                <div class="mt-6">
                    {{ $productos->links() }}
                </div>
            @endif
        @endif
    </div>

</div>

<script>
function confirmDelete(btn) {
    if (confirm('¿Estás seguro? Esta acción no se puede deshacer.')) {
        btn.closest('div').querySelector('form').submit();
    }
}
</script>
@endsection
