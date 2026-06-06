@extends('layouts.app')

@section('title', 'Editar suscripción')
@section('subtitle', $clientPlan->user->name . ' — ' . $clientPlan->plan->name)

@section('content')
<div class="max-w-xl">
<form method="POST" action="{{ route('admin.client-planes.update', $clientPlan) }}">
@csrf @method('PUT')

<div class="bg-slate-800 border border-slate-700 rounded-xl p-6 space-y-5">

    {{-- Cliente (no editable) --}}
    <div>
        <label class="block text-slate-300 text-sm font-medium mb-1">Cliente</label>
        <div class="flex items-center gap-3 p-3 bg-slate-700/50 rounded-lg">
            <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold text-white shrink-0" style="background:#0F6E56">
                {{ strtoupper(substr($clientPlan->user->name, 0, 2)) }}
            </div>
            <div>
                <p class="text-white text-sm font-medium">{{ $clientPlan->user->name }}</p>
                <p class="text-slate-500 text-xs">{{ $clientPlan->user->email }}</p>
            </div>
        </div>
    </div>

    <div>
        <label class="block text-slate-300 text-sm font-medium mb-1">Plan *</label>
        <select name="plan_id" required
            class="w-full bg-slate-700 border border-slate-600 text-white rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-teal-500">
            @foreach($planes as $plan)
            <option value="{{ $plan->id }}" {{ $clientPlan->plan_id == $plan->id ? 'selected' : '' }}>
                {{ $plan->name }} — ${{ number_format($plan->price, 2) }}/mes
            </option>
            @endforeach
        </select>
    </div>

    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-slate-300 text-sm font-medium mb-1">Fecha inicio *</label>
            <input type="date" name="starts_at" value="{{ old('starts_at', $clientPlan->starts_at->toDateString()) }}" required
                class="w-full bg-slate-700 border border-slate-600 text-white rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-teal-500">
        </div>
        <div>
            <label class="block text-slate-300 text-sm font-medium mb-1">Fecha término</label>
            <input type="date" name="ends_at" value="{{ old('ends_at', $clientPlan->ends_at?->toDateString()) }}"
                class="w-full bg-slate-700 border border-slate-600 text-white rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-teal-500">
        </div>
    </div>

    <div>
        <label class="block text-slate-300 text-sm font-medium mb-1">Estado</label>
        <select name="status"
            class="w-full bg-slate-700 border border-slate-600 text-white rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-teal-500">
            @foreach(\App\Models\ClientPlan::$statuses as $val => $label)
            <option value="{{ $val }}" {{ old('status', $clientPlan->status) === $val ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="block text-slate-300 text-sm font-medium mb-1">Notas</label>
        <textarea name="notes" rows="2"
            class="w-full bg-slate-700 border border-slate-600 text-white placeholder-slate-500 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-teal-500">{{ old('notes', $clientPlan->notes) }}</textarea>
    </div>

    <div class="flex gap-3 pt-2">
        <button type="submit"
            class="flex-1 py-2.5 rounded-lg text-sm font-semibold text-white transition"
            style="background:#0F6E56">
            Guardar cambios
        </button>
        <a href="{{ route('admin.client-planes.index') }}"
           class="flex-1 text-center py-2.5 rounded-lg text-sm font-medium bg-slate-700 text-slate-300 hover:bg-slate-600 transition">
            Cancelar
        </a>
    </div>
</div>
</form>
</div>
@endsection