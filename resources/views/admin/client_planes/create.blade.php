@extends('layouts.app')

@section('title', 'Asignar plan a cliente')
@section('subtitle', 'Nueva suscripción')

@section('content')
<div class="max-w-xl">
<form method="POST" action="{{ route('admin.client-planes.store') }}">
@csrf

<div class="bg-slate-800 border border-slate-700 rounded-xl p-6 space-y-5">

    <div>
        <label class="block text-slate-300 text-sm font-medium mb-1">Cliente *</label>
        <select name="user_id" required
            class="w-full bg-slate-700 border border-slate-600 text-white rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-teal-500">
            <option value="">— Selecciona cliente —</option>
            @foreach($clientes as $cliente)
            <option value="{{ $cliente->id }}"
                {{ (old('user_id') == $cliente->id || $selected_client == $cliente->id) ? 'selected' : '' }}>
                {{ $cliente->name }} — {{ $cliente->email }}
            </option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="block text-slate-300 text-sm font-medium mb-1">Plan *</label>
        <select name="plan_id" id="plan-select" required
            class="w-full bg-slate-700 border border-slate-600 text-white rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-teal-500">
            <option value="">— Selecciona plan —</option>
            @foreach($planes as $plan)
            <option value="{{ $plan->id }}"
                data-price="{{ $plan->price }}"
                data-type="{{ $plan->type }}"
                {{ old('plan_id') == $plan->id ? 'selected' : '' }}>
                {{ $plan->name }} — ${{ number_format($plan->price, 2) }}/mes
            </option>
            @endforeach
        </select>
        <div id="plan-info" class="hidden mt-2 p-3 bg-slate-700/50 rounded-lg text-xs text-slate-400"></div>
    </div>

    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-slate-300 text-sm font-medium mb-1">Fecha inicio *</label>
            <input type="date" name="starts_at" value="{{ old('starts_at', today()->toDateString()) }}" required
                class="w-full bg-slate-700 border border-slate-600 text-white rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-teal-500">
        </div>
        <div>
            <label class="block text-slate-300 text-sm font-medium mb-1">Fecha término</label>
            <input type="date" name="ends_at" value="{{ old('ends_at') }}"
                class="w-full bg-slate-700 border border-slate-600 text-white rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-teal-500">
            <p class="text-xs text-slate-500 mt-1">Opcional. Dejar vacío = sin fecha límite.</p>
        </div>
    </div>

    <div>
        <label class="block text-slate-300 text-sm font-medium mb-1">Estado</label>
        <select name="status"
            class="w-full bg-slate-700 border border-slate-600 text-white rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-teal-500">
            @foreach(\App\Models\ClientPlan::$statuses as $val => $label)
            <option value="{{ $val }}" {{ old('status', 'active') === $val ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="block text-slate-300 text-sm font-medium mb-1">Notas</label>
        <textarea name="notes" rows="2"
            class="w-full bg-slate-700 border border-slate-600 text-white placeholder-slate-500 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-teal-500"
            placeholder="Observaciones internas...">{{ old('notes') }}</textarea>
    </div>

    <div class="flex gap-3 pt-2">
        <button type="submit"
            class="flex-1 py-2.5 rounded-lg text-sm font-semibold text-white transition"
            style="background:#0F6E56">
            Asignar plan
        </button>
        <a href="{{ route('admin.client-planes.index') }}"
           class="flex-1 text-center py-2.5 rounded-lg text-sm font-medium bg-slate-700 text-slate-300 hover:bg-slate-600 transition">
            Cancelar
        </a>
    </div>
</div>
</form>
</div>

@push('scripts')
<script>
const planSelect = document.getElementById('plan-select');
const planInfo   = document.getElementById('plan-info');
const types = { monthly: 'Plan Mensual', custom: 'Plan Personalizado', visit: 'Por Visita' };

planSelect.addEventListener('change', function() {
    const opt = this.selectedOptions[0];
    if (!opt.value) { planInfo.classList.add('hidden'); return; }
    const price = parseFloat(opt.dataset.price);
    const type  = opt.dataset.type;
    planInfo.classList.remove('hidden');
    planInfo.innerHTML = `
        <span class="font-semibold text-slate-300">${types[type]}</span> —
        Precio: <span class="text-white font-semibold">$${price.toFixed(2)}</span>/mes
    `;
});
</script>
@endpush
@endsection