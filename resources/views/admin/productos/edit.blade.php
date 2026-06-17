@extends('layouts.app')

@section('title', 'Editar Producto')

@section('content')
<div class="max-w-2xl mx-auto py-8 px-4">

    {{-- Header --}}
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('admin.productos.index') }}"
           class="p-2 rounded-lg hover:bg-gray-100 text-gray-500 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div>
            <h1 class="text-xl font-bold text-gray-900">Editar Producto</h1>
            <p class="text-sm text-gray-500">{{ $producto->name }}</p>
        </div>
    </div>

    <form action="{{ route('admin.productos.update', $producto) }}" method="POST"
          enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        {{-- Nombre y marca --}}
        <div class="bg-white rounded-xl border border-gray-200 p-5 space-y-4">
            <h2 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Información básica</h2>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Nombre <span class="text-red-500">*</span>
                </label>
                <input type="text" name="name" value="{{ old('name', $producto->name) }}" required
                       class="w-full rounded-lg border-gray-300 focus:ring-emerald-500 focus:border-emerald-500">
                @error('name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Marca</label>
                    <input type="text" name="brand" value="{{ old('brand', $producto->brand) }}"
                           class="w-full rounded-lg border-gray-300 focus:ring-emerald-500 focus:border-emerald-500">
                    @error('brand') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Categoría</label>
                    <input type="text" name="category" value="{{ old('category', $producto->category) }}"
                           list="categories-list"
                           class="w-full rounded-lg border-gray-300 focus:ring-emerald-500 focus:border-emerald-500">
                    <datalist id="categories-list">
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}">
                        @endforeach
                    </datalist>
                    @error('category') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
                <textarea name="description" rows="3"
                          class="w-full rounded-lg border-gray-300 focus:ring-emerald-500 focus:border-emerald-500">{{ old('description', $producto->description) }}</textarea>
                @error('description') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Precio (MXN)</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-3 flex items-center text-gray-400 text-sm">$</span>
                        <input type="number" name="price" value="{{ old('price', $producto->price) }}"
                               step="0.01" min="0"
                               class="w-full pl-7 rounded-lg border-gray-300 focus:ring-emerald-500 focus:border-emerald-500">
                    </div>
                    @error('price') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="flex items-end pb-1">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1"
                               {{ old('is_active', $producto->is_active) ? 'checked' : '' }}
                               class="w-4 h-4 rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
                        <span class="text-sm font-medium text-gray-700">Activo</span>
                    </label>
                </div>
            </div>
        </div>

        {{-- Imagen --}}
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <h2 class="text-sm font-semibold text-gray-700 uppercase tracking-wide mb-4">Imagen del producto</h2>

            @include('components.image-upload-field', [
                'label'              => 'Imagen del producto',
                'fileField'          => 'image_file',
                'urlField'           => 'image_url',
                'removeField'        => 'remove_image',
                'currentUrl'         => $producto->getResolvedImageUrl(),
                'currentExternalUrl' => $producto->image_url,
                'hasLocal'           => (bool) $producto->image_path,
            ])

            @error('image_file') <p class="text-xs text-red-500 mt-2">{{ $message }}</p> @enderror
            @error('image_url')  <p class="text-xs text-red-500 mt-2">{{ $message }}</p> @enderror
        </div>

        {{-- Actions --}}
        <div class="flex items-center justify-between">
            {{-- Eliminar producto --}}
            <form action="{{ route('admin.productos.destroy', $producto) }}" method="POST"
                  onsubmit="return confirm('¿Eliminar este producto? Esta acción no se puede deshacer.')">
                @csrf @method('DELETE')
                <button type="submit"
                        class="px-4 py-2.5 rounded-lg border border-red-200 text-red-600 text-sm font-medium hover:bg-red-50 transition-colors">
                    Eliminar producto
                </button>
            </form>

            <div class="flex items-center gap-3">
                <a href="{{ route('admin.productos.index') }}"
                   class="px-5 py-2.5 rounded-lg border border-gray-300 text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                    Cancelar
                </a>
                <button type="submit"
                        class="px-5 py-2.5 rounded-lg bg-emerald-600 text-white text-sm font-medium hover:bg-emerald-700 transition-colors">
                    Guardar cambios
                </button>
            </div>
        </div>

    </form>
</div>
@endsection