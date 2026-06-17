@extends('layouts.app')

@section('title', 'Mi Centro')

@section('content')
<div class="max-w-2xl mx-auto py-8 px-4">

    {{-- Header --}}
    <div class="mb-6">
        <h1 class="text-xl font-bold text-gray-900">Mi Centro</h1>
        <p class="text-sm text-gray-500">Edita la información y logo de tu centro</p>
    </div>

    @if(session('success'))
        <div class="mb-5 flex items-center gap-2 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-lg text-sm">
            <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('admin.centro.update') }}" method="POST"
          enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        {{-- Datos básicos --}}
        <div class="bg-white rounded-xl border border-gray-200 p-5 space-y-4">
            <h2 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Información del centro</h2>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Nombre del centro <span class="text-red-500">*</span>
                </label>
                <input type="text" name="name" value="{{ old('name', $center->name) }}" required
                       class="w-full rounded-lg border-gray-300 focus:ring-emerald-500 focus:border-emerald-500">
                @error('name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Dirección</label>
                <input type="text" name="address" value="{{ old('address', $center->address) }}"
                       class="w-full rounded-lg border-gray-300 focus:ring-emerald-500 focus:border-emerald-500"
                       placeholder="Calle, colonia, ciudad">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Teléfono</label>
                    <input type="text" name="phone" value="{{ old('phone', $center->phone) }}"
                           class="w-full rounded-lg border-gray-300 focus:ring-emerald-500 focus:border-emerald-500"
                           placeholder="222 123 4567">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email de contacto</label>
                    <input type="email" name="email" value="{{ old('email', $center->email) }}"
                           class="w-full rounded-lg border-gray-300 focus:ring-emerald-500 focus:border-emerald-500"
                           placeholder="centro@ejemplo.com">
                </div>
            </div>
        </div>

        {{-- Logo --}}
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <h2 class="text-sm font-semibold text-gray-700 uppercase tracking-wide mb-4">Logo del centro</h2>
            <p class="text-xs text-gray-400 mb-4">El logo aparece en el sidebar y en la app móvil de tus clientes.</p>

            @include('components.image-upload-field', [
                'label'              => 'Logo del centro',
                'fileField'          => 'logo_file',
                'urlField'           => 'logo_url',
                'removeField'        => 'remove_logo',
                'currentUrl'         => $center->getResolvedImageUrl(),
                'currentExternalUrl' => $center->logo_url,
                'hasLocal'           => (bool) $center->logo_path,
            ])

            @error('logo_file') <p class="text-xs text-red-500 mt-2">{{ $message }}</p> @enderror
            @error('logo')      <p class="text-xs text-red-500 mt-2">{{ $message }}</p> @enderror
        </div>

        {{-- Actions --}}
        <div class="flex justify-end">
            <button type="submit"
                    class="px-6 py-2.5 rounded-lg bg-emerald-600 text-white text-sm font-medium hover:bg-emerald-700 transition-colors">
                Guardar cambios
            </button>
        </div>

    </form>
</div>
@endsection