{{--
    Componente reutilizable para campo de imagen híbrido (archivo local o URL).

    Parámetros:
      $label        → "Imagen del producto" | "Logo del centro"
      $fileField    → nombre del input file → "image_file" | "logo_file"
      $urlField     → nombre del input URL  → "image_url"  | "logo_url"
      $removeField  → nombre del checkbox   → "remove_image" | "remove_logo"
      $currentUrl   → resultado de $model->getResolvedImageUrl() (puede ser null)
      $hasLocal     → bool: si el modelo tiene imagen local (para mostrar badge)
--}}

<div x-data="imageUploadField()" class="space-y-3">

    {{-- Preview actual --}}
    @if($currentUrl)
    <div class="flex items-center gap-3 p-3 bg-slate-800 rounded-lg border border-slate-700">
        <img src="{{ $currentUrl }}"
             alt="Imagen actual"
             class="w-16 h-16 object-cover rounded-lg border border-slate-600"
             onerror="this.src='{{ asset('images/placeholder.png') }}'">
        <div class="flex-1 min-w-0">
            <p class="text-sm font-medium text-slate-200">Imagen actual</p>
            @if($hasLocal ?? false)
                <span class="inline-flex items-center gap-1 text-xs text-green-400 bg-green-900/30 px-2 py-0.5 rounded-full">
                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    Archivo local
                </span>
            @else
                <span class="inline-flex items-center gap-1 text-xs text-blue-400 bg-blue-900/30 px-2 py-0.5 rounded-full">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                    URL externa
                </span>
            @endif
        </div>
        {{-- Checkbox eliminar --}}
        <label class="flex items-center gap-2 text-sm text-red-400 cursor-pointer shrink-0">
            <input type="checkbox"
                   name="{{ $removeField ?? 'remove_image' }}"
                   value="1"
                   x-model="removing"
                   class="rounded border-slate-600 text-red-500 focus:ring-red-400 bg-slate-800">
            Eliminar
        </label>
    </div>
    @endif

    {{-- Cuando NO está eliminando --}}
    <template x-if="!removing">
        <div class="space-y-3">

            {{-- Upload de archivo --}}
            <div>
                <label class="block text-slate-300 text-sm mb-1">
                    {{ $label ?? 'Imagen' }} <span class="text-slate-500 font-normal">(archivo)</span>
                </label>
                <input type="file"
                       name="{{ $fileField ?? 'image_file' }}"
                       accept="image/jpeg,image/png,image/webp"
                       @change="previewFile($event)"
                       class="block w-full text-sm text-slate-400 file:mr-3 file:py-2 file:px-4
                              file:rounded-lg file:border-0 file:text-sm file:font-medium
                              file:bg-green-900/40 file:text-green-400 hover:file:bg-green-900/60
                              cursor-pointer">
                <p class="text-xs text-slate-500 mt-1">JPG, PNG o WEBP · Máx. 2 MB</p>
            </div>

            {{-- Preview del archivo seleccionado --}}
            <template x-if="preview">
                <div class="flex items-center gap-3 p-3 bg-green-900/20 rounded-lg border border-green-800/50">
                    <img :src="preview" class="w-16 h-16 object-cover rounded-lg border border-green-700/50">
                    <div>
                        <p class="text-sm font-medium text-green-400">Nueva imagen seleccionada</p>
                        <button type="button" @click="clearPreview()" class="text-xs text-red-400 hover:underline mt-0.5">Quitar</button>
                    </div>
                </div>
            </template>

            {{-- Separador "o" --}}
            <div x-show="!preview" class="flex items-center gap-3 text-slate-600">
                <div class="flex-1 h-px bg-slate-700"></div>
                <span class="text-xs uppercase tracking-wide">o</span>
                <div class="flex-1 h-px bg-slate-700"></div>
            </div>

            {{-- URL externa --}}
            <div x-show="!preview">
                <label class="block text-slate-300 text-sm mb-1">
                    URL externa <span class="text-slate-500 font-normal">(opcional)</span>
                </label>
                <input type="url"
                       name="{{ $urlField ?? 'image_url' }}"
                       value="{{ old($urlField ?? 'image_url', $currentExternalUrl ?? '') }}"
                       placeholder="https://ejemplo.com/imagen.jpg"
                       class="w-full bg-slate-800 text-slate-100 rounded-lg px-4 py-2 border border-slate-700 focus:outline-none focus:border-green-500 text-sm">
                <p class="text-xs text-slate-500 mt-1">Si subes un archivo, la URL se ignora.</p>
            </div>

        </div>
    </template>

    {{-- Mensaje cuando está eliminando --}}
    <template x-if="removing">
        <p class="text-sm text-red-400 bg-red-900/20 px-3 py-2 rounded-lg border border-red-800/50">
            La imagen se eliminará al guardar. Desmarca la casilla para cancelar.
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
