{{--
    Componente reutilizable para campo de imagen híbrido (archivo local o URL).

    Parámetros:
      $label        → "Imagen del producto" | "Logo del centro"
      $fileField    → nombre del input file → "image_file" | "logo_file"
      $urlField     → nombre del input URL  → "image_url"  | "logo"
      $removeField  → nombre del checkbox   → "remove_image" | "remove_logo"
      $currentUrl   → resultado de $model->getResolvedImageUrl() (puede ser null)
      $hasLocal     → bool: si el modelo tiene imagen local (para mostrar badge)
--}}

<div x-data="imageUploadField()" class="space-y-3">

    {{-- Preview actual --}}
    @if($currentUrl)
    <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg border border-gray-200">
        <img src="{{ $currentUrl }}"
             alt="Imagen actual"
             class="w-16 h-16 object-cover rounded-lg border border-gray-300"
             onerror="this.src='{{ asset('images/placeholder.png') }}'">
        <div class="flex-1 min-w-0">
            <p class="text-sm font-medium text-gray-700">Imagen actual</p>
            @if($hasLocal ?? false)
                <span class="inline-flex items-center gap-1 text-xs text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full">
                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    Archivo local
                </span>
            @else
                <span class="inline-flex items-center gap-1 text-xs text-blue-700 bg-blue-50 px-2 py-0.5 rounded-full">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                    URL externa
                </span>
            @endif
        </div>
        {{-- Checkbox eliminar --}}
        <label class="flex items-center gap-2 text-sm text-red-600 cursor-pointer shrink-0">
            <input type="checkbox"
                   name="{{ $removeField ?? 'remove_image' }}"
                   value="1"
                   x-model="removing"
                   class="rounded border-red-300 text-red-500 focus:ring-red-400">
            Eliminar
        </label>
    </div>
    @endif

    {{-- Cuando NO está eliminando --}}
    <template x-if="!removing">
        <div class="space-y-3">

            {{-- Upload de archivo --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    {{ $label ?? 'Imagen' }} <span class="text-gray-400 font-normal">(archivo)</span>
                </label>
                <input type="file"
                       name="{{ $fileField ?? 'image_file' }}"
                       accept="image/jpeg,image/png,image/webp"
                       @change="previewFile($event)"
                       class="block w-full text-sm text-gray-600 file:mr-3 file:py-2 file:px-4
                              file:rounded-lg file:border-0 file:text-sm file:font-medium
                              file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100
                              cursor-pointer">
                <p class="text-xs text-gray-400 mt-1">JPG, PNG o WEBP · Máx. 2 MB</p>
            </div>

            {{-- Preview del archivo seleccionado --}}
            <template x-if="preview">
                <div class="flex items-center gap-3 p-3 bg-emerald-50 rounded-lg border border-emerald-200">
                    <img :src="preview" class="w-16 h-16 object-cover rounded-lg border border-emerald-300">
                    <div>
                        <p class="text-sm font-medium text-emerald-700">Nueva imagen seleccionada</p>
                        <button type="button" @click="clearPreview()" class="text-xs text-red-500 hover:underline mt-0.5">Quitar</button>
                    </div>
                </div>
            </template>

            {{-- Separador "o" --}}
            <div x-show="!preview" class="flex items-center gap-3 text-gray-400">
                <div class="flex-1 h-px bg-gray-200"></div>
                <span class="text-xs uppercase tracking-wide">o</span>
                <div class="flex-1 h-px bg-gray-200"></div>
            </div>

            {{-- URL externa --}}
            <div x-show="!preview">
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    URL externa <span class="text-gray-400 font-normal">(opcional)</span>
                </label>
                <input type="url"
                       name="{{ $urlField ?? 'image_url' }}"
                       value="{{ old($urlField ?? 'image_url', $currentExternalUrl ?? '') }}"
                       placeholder="https://ejemplo.com/imagen.jpg"
                       class="w-full rounded-lg border-gray-300 text-sm focus:ring-emerald-500 focus:border-emerald-500">
                <p class="text-xs text-gray-400 mt-1">Si subes un archivo, la URL se ignora.</p>
            </div>

        </div>
    </template>

    {{-- Mensaje cuando está eliminando --}}
    <template x-if="removing">
        <p class="text-sm text-red-600 bg-red-50 px-3 py-2 rounded-lg border border-red-200">
            ⚠️ La imagen se eliminará al guardar. Desmarca la casilla para cancelar.
        </p>
    </template>

</div>

@once
@push('scripts')
<script>
function imageUploadField() {
    return {
        preview: null,
        removing: false,
        fileInput: null,

        previewFile(event) {
            const file = event.target.files[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = (e) => { this.preview = e.target.result; };
            reader.readAsDataURL(file);
            this.fileInput = event.target;
        },

        clearPreview() {
            this.preview = null;
            if (this.fileInput) this.fileInput.value = '';
        }
    }
}
</script>
@endpush
@endonce