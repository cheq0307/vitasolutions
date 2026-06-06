@extends('layouts.app')

@section('title', 'Perfil de salud — ' . $cliente->name)
@section('page-title', 'Perfil de salud')
@section('page-subtitle', $cliente->name)

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    {{-- Volver --}}
    <a href="{{ route('admin.clientes.show', $cliente) }}"
       class="inline-flex items-center gap-2 text-sm text-slate-400 hover:text-white transition">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Volver al expediente
    </a>

    @if(session('success'))
        <div class="bg-emerald-900/40 border border-emerald-600/50 text-emerald-300 rounded-xl px-4 py-3 text-sm flex items-center gap-2">
            <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-900/40 border border-red-600/50 text-red-300 rounded-xl px-4 py-3 text-sm">
            <ul class="list-disc list-inside space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.clientes.salud.update', $cliente) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        {{-- DATOS BÁSICOS --}}
        <div class="bg-slate-800/60 border border-slate-700/50 rounded-2xl p-6">
            <div class="flex items-center gap-3 mb-5">
                <div class="w-8 h-8 bg-teal-500/20 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <h2 class="text-white font-semibold">Datos básicos</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-slate-400 text-xs font-medium mb-1.5 uppercase tracking-wide">Tipo de sangre</label>
                    <select name="blood_type" class="w-full bg-slate-900/70 border border-slate-600/50 rounded-lg text-white text-sm px-3 py-2.5 focus:outline-none focus:border-teal-500 transition">
                        <option value="">— Seleccionar —</option>
                        @foreach(['A+','A-','B+','B-','AB+','AB-','O+','O-','desconocido'] as $tipo)
                            <option value="{{ $tipo }}" {{ old('blood_type', $perfil->blood_type) == $tipo ? 'selected' : '' }}>
                                {{ $tipo === 'desconocido' ? 'No lo sé' : $tipo }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-slate-400 text-xs font-medium mb-1.5 uppercase tracking-wide">Sexo biológico</label>
                    <select name="sex" class="w-full bg-slate-900/70 border border-slate-600/50 rounded-lg text-white text-sm px-3 py-2.5 focus:outline-none focus:border-teal-500 transition">
                        <option value="">— Seleccionar —</option>
                        <option value="masculino" {{ old('sex', $perfil->sex) == 'masculino' ? 'selected' : '' }}>Masculino</option>
                        <option value="femenino"  {{ old('sex', $perfil->sex) == 'femenino'  ? 'selected' : '' }}>Femenino</option>
                        <option value="otro"      {{ old('sex', $perfil->sex) == 'otro'      ? 'selected' : '' }}>Otro</option>
                    </select>
                </div>

                <div>
                    <label class="block text-slate-400 text-xs font-medium mb-1.5 uppercase tracking-wide">Fecha de nacimiento</label>
                    <input type="date" name="birth_date"
                        value="{{ old('birth_date', $perfil->birth_date ? $perfil->birth_date->format('Y-m-d') : '') }}"
                        class="w-full bg-slate-900/70 border border-slate-600/50 rounded-lg text-white text-sm px-3 py-2.5 focus:outline-none focus:border-teal-500 transition">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                <div>
                    <label class="block text-slate-400 text-xs font-medium mb-1.5 uppercase tracking-wide">Estatura</label>
                    <div class="relative">
                        <input type="number" name="height_cm" step="0.1" min="50" max="250"
                            value="{{ old('height_cm', $perfil->height_cm) }}"
                            placeholder="170"
                            class="w-full bg-slate-900/70 border border-slate-600/50 rounded-lg text-white text-sm px-3 py-2.5 pr-10 focus:outline-none focus:border-teal-500 transition">
                        <span class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-500 text-xs">cm</span>
                    </div>
                </div>

                <div>
                    <label class="block text-slate-400 text-xs font-medium mb-1.5 uppercase tracking-wide">Peso</label>
                    <div class="relative">
                        <input type="number" name="weight_kg" step="0.1" min="20" max="300"
                            value="{{ old('weight_kg', $perfil->weight_kg) }}"
                            placeholder="70"
                            class="w-full bg-slate-900/70 border border-slate-600/50 rounded-lg text-white text-sm px-3 py-2.5 pr-10 focus:outline-none focus:border-teal-500 transition">
                        <span class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-500 text-xs">kg</span>
                    </div>
                </div>
            </div>

            @if($perfil->height_cm && $perfil->weight_kg)
            <div class="mt-4 bg-teal-900/20 border border-teal-700/30 rounded-xl px-4 py-3 flex items-center gap-4">
                <div>
                    <p class="text-xs text-slate-400 uppercase tracking-wide">IMC actual</p>
                    <p class="text-2xl font-bold text-teal-400">{{ number_format($perfil->imc, 1) }}</p>
                </div>
                <div class="h-8 w-px bg-slate-600/40"></div>
                <div>
                    <p class="text-xs text-slate-400 uppercase tracking-wide">Clasificación</p>
                    <p class="text-sm font-semibold text-white">{{ $perfil->imc_label }}</p>
                </div>
            </div>
            @endif
        </div>

        {{-- ANTECEDENTES MÉDICOS --}}
        <div class="bg-slate-800/60 border border-slate-700/50 rounded-2xl p-6">
            <div class="flex items-center gap-3 mb-5">
                <div class="w-8 h-8 bg-rose-500/20 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <h2 class="text-white font-semibold">Antecedentes médicos</h2>
            </div>

            <div class="space-y-4">
                <div>
                    <label class="block text-slate-400 text-xs font-medium mb-1.5 uppercase tracking-wide">Alergias</label>
                    <textarea name="allergies" rows="2" placeholder="Ej: Polen, mariscos, penicilina..."
                        class="w-full bg-slate-900/70 border border-slate-600/50 rounded-lg text-white text-sm px-3 py-2.5 focus:outline-none focus:border-teal-500 transition resize-none placeholder-slate-600">{{ old('allergies', $perfil->allergies) }}</textarea>
                </div>

                <div>
                    <label class="block text-slate-400 text-xs font-medium mb-1.5 uppercase tracking-wide">Padecimientos crónicos</label>
                    <textarea name="chronic_conditions" rows="2" placeholder="Ej: Diabetes tipo 2, hipertensión..."
                        class="w-full bg-slate-900/70 border border-slate-600/50 rounded-lg text-white text-sm px-3 py-2.5 focus:outline-none focus:border-teal-500 transition resize-none placeholder-slate-600">{{ old('chronic_conditions', $perfil->chronic_conditions) }}</textarea>
                </div>

                <div>
                    <label class="block text-slate-400 text-xs font-medium mb-1.5 uppercase tracking-wide">Medicamentos actuales</label>
                    <textarea name="current_medications" rows="2" placeholder="Ej: Metformina 500mg, Levotiroxina 50mcg..."
                        class="w-full bg-slate-900/70 border border-slate-600/50 rounded-lg text-white text-sm px-3 py-2.5 focus:outline-none focus:border-teal-500 transition resize-none placeholder-slate-600">{{ old('current_medications', $perfil->current_medications) }}</textarea>
                </div>
            </div>
        </div>

        {{-- OBJETIVO Y ACTIVIDAD --}}
        <div class="bg-slate-800/60 border border-slate-700/50 rounded-2xl p-6">
            <div class="flex items-center gap-3 mb-5">
                <div class="w-8 h-8 bg-violet-500/20 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
                <h2 class="text-white font-semibold">Objetivo y actividad física</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-slate-400 text-xs font-medium mb-2 uppercase tracking-wide">Meta principal</label>
                    <div class="grid grid-cols-2 gap-2">
                        @php
                        $metas = [
                            'perder_peso' => ['label' => 'Perder peso',    'icon' => '⚖️'],
                            'ganar_masa'  => ['label' => 'Ganar músculo',  'icon' => '💪'],
                            'mantener'    => ['label' => 'Mantenerme',     'icon' => '⚡'],
                            'energia'     => ['label' => 'Más energía',    'icon' => '☀️'],
                            'sueno'       => ['label' => 'Mejor sueño',    'icon' => '🌙'],
                            'estres'      => ['label' => 'Reducir estrés', 'icon' => '🧘'],
                        ];
                        $currentGoal = old('main_goal', $perfil->main_goal);
                        @endphp
                        @foreach($metas as $val => $meta)
                        <label class="cursor-pointer">
                            <input type="radio" name="main_goal" value="{{ $val }}" class="peer sr-only"
                                {{ $currentGoal == $val ? 'checked' : '' }}>
                            <div class="border border-slate-600/50 rounded-xl px-3 py-2.5 flex items-center gap-2 transition
                                peer-checked:border-teal-500 peer-checked:bg-teal-900/30 peer-checked:text-teal-300
                                text-slate-400 bg-slate-900/50 hover:border-slate-500">
                                <span>{{ $meta['icon'] }}</span>
                                <span class="text-xs font-medium">{{ $meta['label'] }}</span>
                            </div>
                        </label>
                        @endforeach
                    </div>
                </div>

                <div>
                    <label class="block text-slate-400 text-xs font-medium mb-2 uppercase tracking-wide">Nivel de actividad</label>
                    <div class="space-y-1.5">
                        @php
                        $niveles = [
                            'sedentario' => ['label' => 'Sedentario',  'desc' => 'Sin ejercicio'],
                            'ligero'     => ['label' => 'Ligero',      'desc' => '1-3 días/sem'],
                            'moderado'   => ['label' => 'Moderado',    'desc' => '3-5 días/sem'],
                            'activo'     => ['label' => 'Activo',      'desc' => '6-7 días/sem'],
                            'muy_activo' => ['label' => 'Muy activo',  'desc' => 'Diario intenso'],
                        ];
                        $currentLevel = old('activity_level', $perfil->activity_level);
                        @endphp
                        @foreach($niveles as $val => $nivel)
                        <label class="cursor-pointer block">
                            <input type="radio" name="activity_level" value="{{ $val }}" class="peer sr-only"
                                {{ $currentLevel == $val ? 'checked' : '' }}>
                            <div class="border border-slate-600/50 rounded-lg px-3 py-2 flex items-center justify-between transition
                                peer-checked:border-teal-500 peer-checked:bg-teal-900/30
                                bg-slate-900/50 hover:border-slate-500">
                                <span class="text-sm font-medium peer-checked:text-teal-300 text-slate-300">{{ $nivel['label'] }}</span>
                                <span class="text-xs text-slate-500">{{ $nivel['desc'] }}</span>
                            </div>
                        </label>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- NOTAS --}}
        <div class="bg-slate-800/60 border border-slate-700/50 rounded-2xl p-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-8 h-8 bg-amber-500/20 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                </div>
                <h2 class="text-white font-semibold">Notas del asesor</h2>
            </div>
            <textarea name="notes" rows="3" placeholder="Observaciones clínicas, notas de seguimiento..."
                class="w-full bg-slate-900/70 border border-slate-600/50 rounded-lg text-white text-sm px-3 py-2.5 focus:outline-none focus:border-teal-500 transition resize-none placeholder-slate-600">{{ old('notes', $perfil->notes) }}</textarea>
        </div>

        {{-- GUARDAR --}}
        <div class="flex justify-end">
            <button type="submit"
                class="bg-teal-600 hover:bg-teal-500 text-white font-semibold text-sm px-8 py-3 rounded-xl transition flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Guardar cambios
            </button>
        </div>

    </form>
</div>
@endsection