@extends('layouts.app')

@section('title', 'Nuevo Producto')

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
            <h1 class="text-xl font-bold text-gray-900">Nuevo Producto</h1>
            <p class="text-sm text-gray-500">Agrega un suplemento al catálogo del centro</p>
        </div>
    </div>

    <form action="{{ route('admin.productos.store') }}" method="POST"
          enctype="multipart/form-data" class="space-y-6">
        @csrf

        {{-- Nombre y marca --}}
        <div class="bg-white rounded-xl border border-gray-200 p-5 space-y-4">
            <h2 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Información básica</h2>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Nombre <span class="text-red-500">*</span>
                </label>
                <input type="text" name="name" value="{{ old('name') }}" required
                       class="w-full rounded-lg border-gray-300 focus:ring-emerald-500 focus:border-emerald-500"
                       placeholder="Ej. Whey Protein Gold Standard">
                @error('name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Marca</label>
                    <input type="text" name="brand" value="{{ old('brand') }}"
                           class="w-full rounded-lg border-gray-300 focus:ring-emerald-500 focus:border-emerald-500"
                           placeholder="Ej. Optimum Nutrition">
                    @error('brand') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Categoría</label>
                    <input type="text" name="category" value="{{ old('category') }}"
                           list="categories-list"
                           class="w-full rounded-lg border-gray-300 focus:ring-emerald-500 focus:border-emerald-500"
                           placeholder="Ej. Proteínas">
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
                          class="w-full rounded-lg border-gray-300 focus:ring-emerald-500 focus:border-emerald-500"
                          placeholder="Beneficios, modo de uso, ingredientes clave...">{{ old('description') }}</textarea>
                @error('description') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Precio (MXN)</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-3 flex items-center text-gray-400 text-sm">$</span>
                        <input type="number" name="price" value="{{ old('price') }}"
                               step="0.01" min="0"
                               class="w-full pl-7 rounded-lg border-gray-300 focus:ring-emerald-500 focus:border-emerald-500"
                               placeholder="0.00">
                    </div>
                    @error('price') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="flex items-end pb-1">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1"
                               {{ old('is_active', '1') == '1' ? 'checked' : '' }}
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
                'label'       => 'Imagen del producto',
                'fileField'   => 'image_file',
                'urlField'    => 'image_url',
                'removeField' => 'remove_image',
                'currentUrl'  => null,
                'hasLocal'    => false,
            ])

            @error('image_file') <p class="text-xs text-red-500 mt-2">{{ $message }}</p> @enderror
            @error('image_url')  <p class="text-xs text-red-500 mt-2">{{ $message }}</p> @enderror
        </div>

        {{-- Actions --}}
        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('admin.productos.index') }}"
               class="px-5 py-2.5 rounded-lg border border-gray-300 text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                Cancelar
            </a>
            <button type="submit"
                    class="px-5 py-2.5 rounded-lg bg-emerald-600 text-white text-sm font-medium hover:bg-emerald-700 transition-colors">
                Guardar producto
            </button>
        </div>

    </form>
</div>
@endsection