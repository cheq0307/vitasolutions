@extends('layouts.app')
@section('title', 'Mis archivos')
@section('page-title', 'Mis archivos')
@section('page-subtitle', 'Análisis, estudios y recetas')
@section('content')
<div class="flex justify-end mb-4">
    <a href="{{ route('cliente.archivos.create') }}" class="bg-green-600 hover:bg-green-500 text-white text-sm font-medium px-4 py-2 rounded-lg">+ Subir archivo</a>
</div>
<div class="bg-slate-900 border border-slate-800 rounded-xl">
    <div class="px-6 py-4 text-slate-500 text-sm text-center">Sin archivos subidos.</div>
</div>
@endsection
