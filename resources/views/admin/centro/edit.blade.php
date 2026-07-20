@extends('layouts.app')

@section('title', 'Mi Centro')
@section('subtitle', 'Edita la información y logo de tu centro')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-0">
    <div class="bg-slate-900 border border-slate-800 rounded-xl p-6">

        @if($errors->any())
        <div class="bg-red-900/30 border border-red-700 text-red-400 rounded-lg px-4 py-3 mb-6 text-sm">
            @foreach($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
        @endif

        <form action="{{ route('admin.centro.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="space-y-4">
                <p class="text-slate-400 text-xs font-semibold uppercase tracking-wide">Información del centro</p>

                <div>
                    <label class="block text-slate-300 text-sm mb-1">
                        Nombre del centro <span class="text-red-400">*</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name', $center->name) }}" required
                           class="w-full bg-slate-800 text-slate-100 rounded-lg px-4 py-2 border border-slate-700 focus:outline-none focus:border-green-500 text-sm"
                           placeholder="Nombre del centro">
                    @error('name') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-slate-300 text-sm mb-1">Dirección</label>
                    <input type="text" name="address" value="{{ old('address', $center->address) }}"
                           class="w-full bg-slate-800 text-slate-100 rounded-lg px-4 py-2 border border-slate-700 focus:outline-none focus:border-green-500 text-sm"
                           placeholder="Calle, colonia, ciudad">
                    @error('address') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-slate-300 text-sm mb-1">Teléfono</label>
                        <input type="text" name="phone" value="{{ old('phone', $center->phone) }}"
                               class="w-full bg-slate-800 text-slate-100 rounded-lg px-4 py-2 border border-slate-700 focus:outline-none focus:border-green-500 text-sm"
                               placeholder="222 123 4567">
                        @error('phone') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-slate-300 text-sm mb-1">Email de contacto</label>
                        <input type="email" name="email" value="{{ old('email', $center->email) }}"
                               class="w-full bg-slate-800 text-slate-100 rounded-lg px-4 py-2 border border-slate-700 focus:outline-none focus:border-green-500 text-sm"
                               placeholder="centro@ejemplo.com">
                        @error('email') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <div class="border-t border-slate-800 mt-6 pt-6 space-y-3">
                <p class="text-slate-400 text-xs font-semibold uppercase tracking-wide">Logo del centro</p>
                <p class="text-xs text-slate-500">El logo aparece en el sidebar y en la app móvil de tus clientes.</p>

                @include('components.image-upload-field', [
                    'label'              => 'Logo del centro',
                    'fileField'          => 'logo_file',
                    'urlField'           => 'logo_url',
                    'removeField'        => 'remove_logo',
                    'currentUrl'         => $center->getResolvedImageUrl(),
                    'currentExternalUrl' => $center->logo_url,
                    'hasLocal'           => (bool) $center->logo_path,
                ])

                @error('logo_file') <p class="text-xs text-red-400 mt-2">{{ $message }}</p> @enderror
                @error('logo_url')  <p class="text-xs text-red-400 mt-2">{{ $message }}</p> @enderror
            </div>

            <div class="flex gap-3 mt-6">
                <button type="submit"
                        class="bg-green-600 hover:bg-green-500 text-white font-medium px-6 py-2 rounded-lg transition text-sm">
                    Guardar cambios
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
