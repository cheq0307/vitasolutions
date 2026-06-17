@extends('layouts.app')

@section('title', 'Editar Producto')
@section('subtitle', 'Actualiza la información del producto')

@section('content')
<div class="max-w-2xl">
    <div class="bg-slate-900 border border-slate-800 rounded-xl p-6">

        @if($errors->any())
        <div class="bg-red-900/30 border border-red-700 text-red-400 rounded-lg px-4 py-3 mb-6 text-sm">
            @foreach($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
        @endif

        <form action="{{ route('admin.productos.update', $producto) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="space-y-4">
                <p class="text-slate-400 text-xs font-semibold uppercase tracking-wide">Información básica</p>

                <div>
                    <label class="block text-slate-300 text-sm mb-1">
                        Nombre <span class="text-red-400">*</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name', $producto->name) }}" required
                           class="w-full bg-slate-800 text-slate-100 rounded-lg px-4 py-2 border border-slate-700 focus:outline-none focus:border-green-500 text-sm"
                           placeholder="Ej. Whey Protein Gold Standard">
                    @error('name') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-slate-300 text-sm mb-1">Marca</label>
                        <input type="text" name="brand" value="{{ old('brand', $producto->brand) }}"
                               class="w-full bg-slate-800 text-slate-100 rounded-lg px-4 py-2 border border-slate-700 focus:outline-none focus:border-green-500 text-sm"
                               placeholder="Ej. Optimum Nutrition">
                        @error('brand') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-slate-300 text-sm mb-1">Categoría</label>
                        <input type="text" name="category" value="{{ old('category', $producto->category) }}"
                               list="categories-list"
                               class="w-full bg-slate-800 text-slate-100 rounded-lg px-4 py-2 border border-slate-700 focus:outline-none focus:border-green-500 text-sm"
                               placeholder="Ej. Proteínas">
                        <datalist id="categories-list">
                            @foreach($categories as $cat)
                                <option value="{{ $cat }}">
                            @endforeach
                        </datalist>
                        @error('category') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-slate-300 text-sm mb-1">Descripción</label>
                    <textarea name="description" rows="3"
                              class="w-full bg-slate-800 text-slate-100 rounded-lg px-4 py-2 border border-slate-700 focus:outline-none focus:border-green-500 text-sm"
                              placeholder="Beneficios, modo de uso, ingredientes clave...">{{ old('description', $producto->description) }}</textarea>
                    @error('description') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-slate-300 text-sm mb-1">Precio (MXN)</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-3 flex items-center text-slate-500 text-sm">$</span>
                            <input type="number" name="price" value="{{ old('price', $producto->price) }}"
                                   step="0.01" min="0"
                                   class="w-full pl-7 bg-slate-800 text-slate-100 rounded-lg px-4 py-2 border border-slate-700 focus:outline-none focus:border-green-500 text-sm"
                                   placeholder="0.00">
                        </div>
                        @error('price') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex items-end pb-1">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" name="is_active" value="1"
                                   {{ old('is_active', $producto->active) ? 'checked' : '' }}
                                   class="w-4 h-4 accent-green-500">
                            <span class="text-slate-300 text-sm">Activo</span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="border-t border-slate-800 mt-6 pt-6 space-y-3">
                <p class="text-slate-400 text-xs font-semibold uppercase tracking-wide">Imagen del producto</p>

                @include('components.image-upload-field', [
                    'label'              => 'Imagen del producto',
                    'fileField'          => 'image_file',
                    'urlField'           => 'image_url',
                    'removeField'        => 'remove_image',
                    'currentUrl'         => $producto->getResolvedImageUrl(),
                    'currentExternalUrl' => $producto->image_url,
                    'hasLocal'           => (bool) $producto->image_path,
                ])

                @error('image_file') <p class="text-xs text-red-400 mt-2">{{ $message }}</p> @enderror
                @error('image_url')  <p class="text-xs text-red-400 mt-2">{{ $message }}</p> @enderror
            </div>

            <div class="flex gap-3 mt-6">
                <button type="submit"
                        class="bg-green-600 hover:bg-green-500 text-white font-medium px-6 py-2 rounded-lg transition text-sm">
                    Guardar cambios
                </button>
                <a href="{{ route('admin.productos.index') }}"
                   class="text-slate-400 hover:text-white px-6 py-2 rounded-lg hover:bg-slate-800 transition text-sm">
                    Cancelar
                </a>
                <button type="button" onclick="confirmDelete()"
                        class="ml-auto text-red-400 hover:text-red-300 px-6 py-2 rounded-lg hover:bg-slate-800 transition text-sm">
                    Eliminar
                </button>
            </div>
        </form>

        {{-- Formulario oculto para eliminar --}}
        <form id="delete-form" action="{{ route('admin.productos.destroy', $producto) }}" method="POST" style="display: none;">
            @csrf
            @method('DELETE')
        </form>
    </div>
</div>

<script>
function confirmDelete() {
    if (confirm('¿Estás seguro de que deseas eliminar este producto? Esta acción no se puede deshacer.')) {
        document.getElementById('delete-form').submit();
    }
}
</script>
@endsection