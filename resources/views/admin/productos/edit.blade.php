@extends('layouts.app')

@section('title', 'Editar producto')
@section('subtitle', $producto->name)

@section('content')
<div class="max-w-2xl space-y-6">

    {{-- Header --}}
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.productos.index') }}"
           class="text-slate-400 hover:text-teal-400 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <h2 class="text-white text-2xl font-bold tracking-tight">Editar producto</h2>
    </div>

    {{-- Card --}}
    <div class="bg-slate-800 rounded-2xl shadow-xl border border-slate-700 p-8">
        <form action="{{ route('admin.productos.update', $producto) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            {{-- Nombre --}}
            <div>
                <label class="block text-sm font-semibold text-slate-200 mb-2">
                    Nombre del producto <span class="text-teal-400">*</span>
                </label>
                <input type="text" name="name" value="{{ old('name', $producto->name) }}"
                       class="w-full bg-slate-700 border border-slate-600 rounded-xl px-4 py-3 text-sm text-white
                              focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent transition
                              @error('name') border-red-400 @enderror">
                @error('name')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Marca --}}
            <div>
                <label class="block text-sm font-semibold text-slate-200 mb-2">
                    Marca <span class="text-teal-400">*</span>
                </label>
                <input type="text" name="brand" value="{{ old('brand', $producto->brand) }}"
                       class="w-full bg-slate-700 border border-slate-600 rounded-xl px-4 py-3 text-sm text-white
                              focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent transition
                              @error('brand') border-red-400 @enderror">
                @error('brand')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Categoría --}}
            @php
                // Detectar si la categoría actual del producto no está en la lista base
                $currentCat = old('category', $producto->category);
                $isCustom = $currentCat && !in_array($currentCat, $categories);
            @endphp
            <div>
                <label class="block text-sm font-semibold text-slate-200 mb-2">
                    Categoría <span class="text-teal-400">*</span>
                </label>

                <select name="category" id="cat-select" onchange="handleCategory(this)"
                        class="w-full bg-slate-700 border border-slate-600 rounded-xl px-4 py-3 text-sm text-white
                               focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent transition">
                    <option value="" class="bg-slate-800">— Seleccionar —</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat }}" class="bg-slate-800"
                                {{ $currentCat === $cat ? 'selected' : '' }}>
                            {{ $cat }}
                        </option>
                    @endforeach
                    <option value="__otro__" class="bg-slate-800 text-teal-400"
                            {{ $isCustom || old('category') === '__otro__' ? 'selected' : '' }}>
                        + Nueva categoría...
                    </option>
                </select>

                {{-- Campo categoría nueva --}}
                <div id="custom-cat-wrap" class="{{ $isCustom || old('category') === '__otro__' ? '' : 'hidden' }} mt-3">
                    <div class="flex items-center gap-2">
                        <span class="text-teal-400 text-lg font-bold pl-1">+</span>
                        <input type="text" name="custom_category" id="custom-cat"
                               value="{{ $isCustom ? $currentCat : old('custom_category') }}"
                               placeholder="Escribe el nombre de la nueva categoría"
                               class="flex-1 bg-slate-700 border border-teal-500/60 rounded-xl px-4 py-3 text-sm text-white placeholder-slate-400
                                      focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent transition">
                    </div>
                    <p class="text-slate-400 text-xs mt-2 pl-7">
                        Esta categoría quedará disponible para futuros productos.
                    </p>
                </div>
            </div>

            {{-- Descripción --}}
            <div>
                <label class="block text-sm font-semibold text-slate-200 mb-2">Descripción</label>
                <textarea name="description" rows="3"
                          class="w-full bg-slate-700 border border-slate-600 rounded-xl px-4 py-3 text-sm text-white
                                 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent transition resize-none">{{ old('description', $producto->description) }}</textarea>
            </div>

            {{-- URL imagen --}}
            <div>
                <label class="block text-sm font-semibold text-slate-200 mb-2">URL de imagen</label>
                <input type="url" name="image_url"
                       value="{{ old('image_url', $producto->image_url) }}"
                       id="image_url_input"
                       class="w-full bg-slate-700 border border-slate-600 rounded-xl px-4 py-3 text-sm text-white placeholder-slate-400
                              focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent transition"
                       placeholder="https://ejemplo.com/imagen.jpg">
                <div id="img_preview" class="{{ $producto->image_url ? '' : 'hidden' }} mt-3">
                    <img id="img_preview_src"
                         src="{{ $producto->image_url ?? '' }}"
                         alt="Preview"
                         class="h-28 rounded-xl border border-slate-600 object-contain bg-slate-700 p-2">
                </div>
            </div>

            {{-- Toggle activo --}}
            <div class="flex items-center gap-3">
                <button type="button" id="toggle-btn" onclick="toggleActive()"
                        class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors duration-200
                               {{ $producto->active ? 'bg-teal-600' : 'bg-slate-600' }}
                               focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2 focus:ring-offset-slate-800">
                    <span id="toggle-dot"
                          class="inline-block h-4 w-4 rounded-full bg-white shadow transition-transform duration-200
                                 {{ $producto->active ? 'translate-x-6' : 'translate-x-1' }}"></span>
                </button>
                <input type="hidden" name="active" id="active-input" value="{{ $producto->active ? '1' : '0' }}">
                <span id="active-label"
                      class="text-sm font-semibold {{ $producto->active ? 'text-teal-400' : 'text-slate-400' }}">
                    {{ $producto->active ? 'Producto activo' : 'Producto inactivo' }}
                </span>
            </div>

            <div class="border-t border-slate-700 pt-2"></div>

            <div class="flex items-center gap-4">
                <button type="submit"
                        class="bg-teal-600 hover:bg-teal-500 text-white text-sm font-bold px-6 py-3 rounded-xl transition-colors shadow-lg">
                    Guardar cambios
                </button>
                <a href="{{ route('admin.productos.index') }}"
                   class="text-slate-300 hover:text-white text-sm font-medium transition-colors">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>

<script>
    function handleCategory(sel) {
        const wrap  = document.getElementById('custom-cat-wrap');
        const input = document.getElementById('custom-cat');
        if (sel.value === '__otro__') {
            wrap.classList.remove('hidden');
            input.focus();
        } else {
            wrap.classList.add('hidden');
            input.value = '';
        }
    }

    const imgInput = document.getElementById('image_url_input');
    const preview  = document.getElementById('img_preview');
    const previewS = document.getElementById('img_preview_src');
    imgInput.addEventListener('input', function () {
        const url = this.value.trim();
        if (url) {
            previewS.src = url;
            previewS.onload  = () => preview.classList.remove('hidden');
            previewS.onerror = () => preview.classList.add('hidden');
        } else {
            preview.classList.add('hidden');
        }
    });

    let isActive = {{ $producto->active ? 'true' : 'false' }};
    function toggleActive() {
        isActive = !isActive;
        const btn   = document.getElementById('toggle-btn');
        const dot   = document.getElementById('toggle-dot');
        const label = document.getElementById('active-label');
        const inp   = document.getElementById('active-input');
        if (isActive) {
            btn.classList.replace('bg-slate-600', 'bg-teal-600');
            dot.classList.replace('translate-x-1', 'translate-x-6');
            label.textContent = 'Producto activo';
            label.className = 'text-sm font-semibold text-teal-400';
            inp.value = '1';
        } else {
            btn.classList.replace('bg-teal-600', 'bg-slate-600');
            dot.classList.replace('translate-x-6', 'translate-x-1');
            label.textContent = 'Producto inactivo';
            label.className = 'text-sm font-semibold text-slate-400';
            inp.value = '0';
        }
    }
</script>
@endsection