@extends('layouts.app')
@section('title', 'Mis mediciones')
@section('page-title', 'Mis mediciones')
@section('page-subtitle', 'Historial de lecturas de dispositivos')
@section('content')
<div class="w-full max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div></div>
        <a href="{{ route('cliente.mediciones.create') }}" 
           class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-green-600 hover:bg-green-500 text-white font-semibold px-4 py-2 sm:px-5 sm:py-3 rounded-lg text-sm sm:text-base transition-colors">
            + Nueva medición
        </a>
    </div>
    
    <div class="bg-slate-900 border border-slate-800 rounded-xl overflow-hidden">
        <div class="px-4 sm:px-6 py-4 text-slate-500 text-sm sm:text-base text-center">
            Sin mediciones registradas.
        </div>
    </div>
</div>
@endsection
