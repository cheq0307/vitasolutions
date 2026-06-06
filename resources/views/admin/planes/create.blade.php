@extends('layouts.app')

@section('title', 'Nuevo plan')
@section('subtitle', 'Crea un plan mensual, personalizado o por visita')

@section('content')
<div class="max-w-4xl">
<form method="POST" action="{{ route('admin.planes.store') }}" id="plan-form">
@csrf

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Columna principal --}}
    <div class="lg:col-span-2 space-y-5">

        {{-- Info básica --}}
        <div class="bg-slate-800 border border-slate-700 rounded-xl p-5 space-y-4">
            <h2 class="text-white font-semibold text-sm uppercase tracking-wider">Información del plan</h2>

            <div>
                <label class="block text-slate-300 text-sm font-medium mb-1">Nombre del plan *</label>
                <input type="text" name="name" value="{{ old('name') }}" required
                    class="w-full bg-slate-700 border border-slate-600 text-white placeholder-slate-500 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-teal-500"
                    placeholder="Ej. Plan Bienestar Integral">
            </div>

            <div>
                <label class="block text-slate-300 text-sm font-medium mb-1">Descripción</label>
                <textarea name="description" rows="2"
                    class="w-full bg-slate-700 border border-slate-600 text-white placeholder-slate-500 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-teal-500"
                    placeholder="Descripción breve del plan...">{{ old('description') }}</textarea>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-slate-300 text-sm font-medium mb-1">Tipo *</label>
                    <select name="type" id="plan-type" required
                        class="w-full bg-slate-700 border border-slate-600 text-white rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-teal-500">
                        @foreach(\App\Models\Plan::$types as $val => $label)
                        <option value="{{ $val }}" {{ old('type') === $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div id="price-wrap">
                    <label class="block text-slate-300 text-sm font-medium mb-1">Precio mensual (MXN)</label>
                    <input type="number" name="price" value="{{ old('price', 0) }}" min="0" step="0.01"
                        id="plan-price"
                        class="w-full bg-slate-700 border border-slate-600 text-white rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-teal-500">
                    <p id="price-auto-note" class="text-xs text-purple-400 mt-1 hidden">Se calculará automáticamente al guardar.</p>
                </div>
            </div>

            {{-- Toggle activo --}}
            <div class="flex items-center justify-between pt-1">
                <span class="text-slate-300 text-sm font-medium">Plan activo</span>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="hidden" name="active" value="0">
                    <input type="checkbox" name="active" value="1" class="sr-only peer" checked>
                    <div class="w-10 h-5 bg-slate-600 rounded-full peer peer-checked:bg-teal-600 transition after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:rounded-full after:h-4 after:w-4 after:transition peer-checked:after:translate-x-5"></div>
                </label>
            </div>
        </div>

        {{-- Productos del plan --}}
        <div class="bg-slate-800 border border-slate-700 rounded-xl p-5">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-white font-semibold text-sm uppercase tracking-wider">Productos incluidos</h2>
                <button type="button" id="add-product"
                    class="inline-flex items-center gap-1 text-xs font-medium px-3 py-1.5 rounded-lg text-teal-300 bg-teal-700/20 hover:bg-teal-700/40 transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Agregar producto
                </button>
            </div>

            <div id="products-container" class="space-y-3">
                <p id="empty-msg" class="text-slate-600 text-sm text-center py-6">No hay productos. Agrega el primero.</p>
            </div>

            {{-- Subtotal para plan custom --}}
            <div id="price-summary" class="hidden mt-4 pt-4 border-t border-slate-700 flex items-center justify-between">
                <span class="text-slate-400 text-sm">Total estimado mensual:</span>
                <span id="price-total" class="text-white font-bold text-lg">$0.00</span>
            </div>
        </div>
    </div>

    {{-- Sidebar acciones --}}
    <div class="space-y-4">
        <div class="bg-slate-800 border border-slate-700 rounded-xl p-5 space-y-3">
            <button type="submit"
                class="w-full py-2.5 rounded-lg text-sm font-semibold text-white transition"
                style="background:#0F6E56">
                Guardar plan
            </button>
            <a href="{{ route('admin.planes.index') }}"
               class="block w-full text-center py-2.5 rounded-lg text-sm font-medium bg-slate-700 text-slate-300 hover:bg-slate-600 transition">
                Cancelar
            </a>
        </div>

        <div class="bg-slate-800 border border-slate-700 rounded-xl p-4 text-xs text-slate-500 space-y-2">
            <p class="font-semibold text-slate-400">Tipos de plan:</p>
            <p><span class="text-teal-400">Mensual</span> — precio fijo que tú defines.</p>
            <p><span class="text-purple-400">Personalizado</span> — precio calculado: costo producto × cantidad × 30 días.</p>
            <p><span class="text-slate-400">Por visita</span> — sin costo fijo, paga cada visita.</p>
        </div>
    </div>
</div>
</form>
</div>

{{-- Template para fila de producto --}}
<template id="product-row-tpl">
<div class="product-row bg-slate-700/50 border border-slate-600 rounded-lg p-4 space-y-3">
    <div class="flex items-center justify-between">
        <select name="products[__IDX__][id]" class="product-select flex-1 bg-slate-700 border border-slate-600 text-white rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-teal-500 mr-3" required>
            <option value="">— Selecciona producto —</option>
            @foreach($products as $p)
            <option value="{{ $p->id }}" data-price="{{ $p->price }}">{{ $p->name }} — ${{ number_format($p->price,2) }}</option>
            @endforeach
        </select>
        <button type="button" class="remove-row text-red-400 hover:text-red-300 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>
    <div class="grid grid-cols-2 gap-3">
        <div>
            <label class="text-slate-400 text-xs mb-1 block">Dosis</label>
            <input type="text" name="products[__IDX__][dose]" placeholder="Ej. 1 cápsula"
                class="w-full bg-slate-700 border border-slate-600 text-white placeholder-slate-500 rounded-lg px-3 py-1.5 text-sm focus:outline-none focus:border-teal-500">
        </div>
        <div>
            <label class="text-slate-400 text-xs mb-1 block">Cantidad/día</label>
            <input type="number" name="products[__IDX__][quantity]" value="1" min="1"
                class="product-qty w-full bg-slate-700 border border-slate-600 text-white rounded-lg px-3 py-1.5 text-sm focus:outline-none focus:border-teal-500">
        </div>
    </div>
    <div class="grid grid-cols-2 gap-3">
        <div>
            <label class="text-slate-400 text-xs mb-1 block">Horario</label>
            <select name="products[__IDX__][schedule]"
                class="w-full bg-slate-700 border border-slate-600 text-white rounded-lg px-3 py-1.5 text-sm focus:outline-none focus:border-teal-500">
                @foreach(\App\Models\Plan::$schedules as $val => $label)
                <option value="{{ $val }}">{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="text-slate-400 text-xs mb-1 block">Hora (opcional)</label>
            <input type="time" name="products[__IDX__][time]"
                class="w-full bg-slate-700 border border-slate-600 text-white rounded-lg px-3 py-1.5 text-sm focus:outline-none focus:border-teal-500">
        </div>
    </div>
    <div>
        <label class="text-slate-400 text-xs mb-1 block">Lugar de consumo</label>
        <select name="products[__IDX__][place]"
            class="w-full bg-slate-700 border border-slate-600 text-white rounded-lg px-3 py-1.5 text-sm focus:outline-none focus:border-teal-500">
            @foreach(\App\Models\Plan::$places as $val => $label)
            <option value="{{ $val }}">{{ $label }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="text-slate-400 text-xs mb-1 block">Notas (opcional)</label>
        <input type="text" name="products[__IDX__][notes]" placeholder="Ej. Tomar con agua tibia"
            class="w-full bg-slate-700 border border-slate-600 text-white placeholder-slate-500 rounded-lg px-3 py-1.5 text-sm focus:outline-none focus:border-teal-500">
    </div>
</div>
</template>

@push('scripts')
<script>
let idx = 0;
const container  = document.getElementById('products-container');
const emptyMsg   = document.getElementById('empty-msg');
const tpl        = document.getElementById('product-row-tpl');
const planType   = document.getElementById('plan-type');
const priceInput = document.getElementById('plan-price');
const priceWrap  = document.getElementById('price-wrap');
const priceNote  = document.getElementById('price-auto-note');
const priceSumm  = document.getElementById('price-summary');
const priceTotal = document.getElementById('price-total');

function isCustom() { return planType.value === 'custom'; }

function updatePriceUI() {
    const custom = isCustom();
    priceInput.disabled = custom;
    priceInput.classList.toggle('opacity-40', custom);
    priceNote.classList.toggle('hidden', !custom);
    priceSumm.classList.toggle('hidden', !custom);
    if (custom) recalcTotal();
}

function recalcTotal() {
    let total = 0;
    document.querySelectorAll('.product-row').forEach(row => {
        const sel = row.querySelector('.product-select');
        const qty = parseFloat(row.querySelector('.product-qty').value) || 1;
        const opt = sel.selectedOptions[0];
        const price = opt ? parseFloat(opt.dataset.price || 0) : 0;
        total += price * qty * 30;
    });
    priceTotal.textContent = '$' + total.toFixed(2);
}

function addRow() {
    emptyMsg.classList.add('hidden');
    const html = tpl.innerHTML.replaceAll('__IDX__', idx++);
    const div  = document.createElement('div');
    div.innerHTML = html;
    const row  = div.firstElementChild;

    row.querySelector('.remove-row').addEventListener('click', () => {
        row.remove();
        if (!container.querySelector('.product-row')) emptyMsg.classList.remove('hidden');
        if (isCustom()) recalcTotal();
    });
    row.querySelector('.product-select').addEventListener('change', () => { if (isCustom()) recalcTotal(); });
    row.querySelector('.product-qty').addEventListener('input', () => { if (isCustom()) recalcTotal(); });

    container.appendChild(row);
    if (isCustom()) recalcTotal();
}

document.getElementById('add-product').addEventListener('click', addRow);
planType.addEventListener('change', updatePriceUI);
updatePriceUI();
</script>
@endpush
@endsection