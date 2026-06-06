@extends('layouts.app')
@section('title', 'Mis mediciones')
@section('page-title', 'Mis mediciones')
@section('page-subtitle', 'Historial de lecturas de dispositivos')
@section('content')
<div class="flex justify-end mb-4">
    <a href="{{ route('cliente.mediciones.create') }}" class="bg-green-600 hover:bg-green-500 text-white text-sm font-medium px-4 py-2 rounded-lg">+ Nueva medición</a>
</div>
<div class="bg-slate-900 border border-slate-800 rounded-xl">
    <div class="px-6 py-4 text-slate-500 text-sm text-center">Sin mediciones registradas.</div>
</div>
@endsection
