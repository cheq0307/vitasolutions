@extends('layouts.app')

@section('title', 'Editar Centro')

@section('content')
<div class="max-w-2xl mx-auto py-8 px-4">

    {{-- Header --}}
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('superadmin.centers.show', $center) }}"
           class="p-2 rounded-lg hover:bg-gray-100 text-gray-500 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div>
            <h1 class="text-xl font-bold text-gray-900">Editar Centro</h1>
            <p class="text-sm text-gray-500">{{ $center->name }}</p>
        </div>
    </div>

    <form action="{{ route('superadmin.centers.update', $center) }}" method="POST"
          enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        {{-- Datos del centro --}}
        <div class="bg-white rounded-xl border border-gray-200 p-5 space-y-4">
            <h2 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Datos del centro</h2>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Nombre <span class="text-red-500">*</span>
                </label>
                <input type="text" name="name" value="{{ old('name', $center->name) }}" required
                       class="w-full rounded-lg border-gray-300 focus:ring-emerald-500 focus:border-emerald-500">
                @error('name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Dirección</label>
                <input type="text" name="address" value="{{ old('address', $center->address) }}"
                       class="w-full rounded-lg border-gray-300 focus:ring-emerald-500 focus:border-emerald-500">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Teléfono</label>
                    <input type="text" name="phone" value="{{ old('phone', $center->phone) }}"
                           class="w-full rounded-lg border-gray-300 focus:ring-emerald-500 focus:border-emerald-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email del centro</label>
                    <input type="email" name="email" value="{{ old('email', $center->email) }}"
                           class="w-full rounded-lg border-gray-300 focus:ring-emerald-500 focus:border-emerald-500">
                </div>
            </div>

            <div class="flex items-center gap-2">
                <input type="hidden" name="active" value="0">
                <input type="checkbox" name="active" value="1" id="active"
                       {{ old('active', $center->active) ? 'checked' : '' }}
                       class="w-4 h-4 rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
                <label for="active" class="text-sm font-medium text-gray-700 cursor-pointer">Centro activo</label>
            </div>
        </div>

        {{-- Owner --}}
        <div class="bg-white rounded-xl border border-gray-200 p-5 space-y-3">
            <h2 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Admin Owner</h2>
            <p class="text-xs text-gray-400">El owner puede gestionar su centro y crear admins staff.</p>

            <select name="owner_id"
                    class="w-full rounded-lg border-gray-300 focus:ring-emerald-500 focus:border-emerald-500 text-sm">
                <option value="">— Sin owner asignado —</option>
                @foreach($owners as $owner)
                    <option value="{{ $owner->id }}"
                        {{ old('owner_id', $center->owner_id) == $owner->id ? 'selected' : '' }}>
                        {{ $owner->name }} ({{ $owner->email }})
                    </option>
                @endforeach
            </select>
            @error('owner_id') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- Logo --}}
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <h2 class="text-sm font-semibold text-gray-700 uppercase tracking-wide mb-4">Logo del centro</h2>

            @include('components.image-upload-field', [
                'label'              => 'Logo del centro',
                'fileField'          => 'logo_file',
                'urlField'           => 'logo',
                'removeField'        => 'remove_logo',
                'currentUrl'         => $center->getResolvedImageUrl(),
                'currentExternalUrl' => $center->logo,
                'hasLocal'           => (bool) $center->logo_path,
            ])

            @error('logo_file') <p class="text-xs text-red-500 mt-2">{{ $message }}</p> @enderror
            @error('logo')      <p class="text-xs text-red-500 mt-2">{{ $message }}</p> @enderror
        </div>

        {{-- Actions --}}
        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('superadmin.centers.show', $center) }}"
               class="px-5 py-2.5 rounded-lg border border-gray-300 text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                Cancelar
            </a>
            <button type="submit"
                    class="px-5 py-2.5 rounded-lg bg-emerald-600 text-white text-sm font-medium hover:bg-emerald-700 transition-colors">
                Guardar cambios
            </button>
        </div>

    </form>
</div>
@endsection