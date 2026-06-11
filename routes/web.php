<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\ClienteController;
use App\Http\Controllers\Admin\ProductoController;
use App\Http\Controllers\Admin\PlanController;
use App\Http\Controllers\Admin\ClientPlanController;
use App\Http\Controllers\Cliente\DashboardController as ClienteDashboard;
use App\Http\Controllers\Cliente\PerfilSaludController;
use App\Http\Controllers\Cliente\MedicionController;
use App\Http\Controllers\Cliente\ProtocoloController;
use App\Http\Controllers\Cliente\CuestionarioController;
use App\Http\Controllers\Cliente\ArchivoController;
use App\Http\Controllers\SuperAdmin\DashboardController as SuperAdminDashboard;
 use App\Http\Controllers\SuperAdmin\CenterController;

 
// ── Raíz ──────────────────────────────────────────────────────────────────────
Route::get('/', function () {
    if (auth()->check()) {
        return auth()->user()->isAdmin()
            ? redirect()->route('admin.dashboard')
            : redirect()->route('cliente.dashboard');
    }
    return redirect()->route('login');
});

// ── Auth ──────────────────────────────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login',  [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});
Route::post('/logout', [AuthController::class, 'logout'])
     ->middleware('auth')->name('logout');

     // Grupo superadmin (agregar ANTES del grupo admin):

Route::middleware(['auth', 'role:superadmin'])->prefix('superadmin')->name('superadmin.')->group(function () {
    Route::get('/dashboard', [SuperAdminDashboard::class, 'index'])->name('dashboard');
    Route::resource('centers', CenterController::class);
    Route::post('centers/{center}/assign-admin', [CenterController::class, 'assignAdmin'])->name('centers.assign-admin');
});

// ── SUPERADMIN ────────────────────────────────────────────────────────────────
Route::middleware(['auth', 'role:superadmin'])->prefix('superadmin')->name('superadmin.')->group(function () {
    Route::get('/dashboard', [SuperAdminDashboard::class, 'index'])->name('dashboard');
    Route::resource('centers', CenterController::class);
    Route::post('centers/{center}/assign-admin', [CenterController::class, 'assignAdmin'])->name('centers.assign-admin');
});
// ── ADMIN ─────────────────────────────────────────────────────────────────────
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::post('productos/{producto}/importar', [ProductoController::class, 'importSugerido'])->name('productos.importar');

    Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');

    // Clientes
    Route::resource('clientes', ClienteController::class);
    Route::get('clientes/{cliente}/salud',         [ClienteController::class, 'salud'])->name('clientes.salud');
    Route::get('clientes/{cliente}/mediciones',    [ClienteController::class, 'mediciones'])->name('clientes.mediciones');
    Route::get('clientes/{cliente}/archivos',      [ClienteController::class, 'archivos'])->name('clientes.archivos');
    Route::get('clientes/{cliente}/protocolos',    [ClienteController::class, 'protocolos'])->name('clientes.protocolos');
    Route::post('clientes/{cliente}/protocolos',   [ClienteController::class, 'asignarProtocolo'])->name('clientes.protocolos.store');
    Route::get('clientes/{cliente}/salud/editar',  [ClienteController::class, 'editSalud'])->name('clientes.salud.edit');
    Route::put('clientes/{cliente}/salud/editar',  [ClienteController::class, 'updateSalud'])->name('clientes.salud.update');

    // Catálogo
    Route::resource('productos', ProductoController::class);

    // Planes y suscripciones
    Route::resource('planes', PlanController::class);
    Route::resource('client-planes', ClientPlanController::class)->except(['show']);
});

// ── CLIENTE ───────────────────────────────────────────────────────────────────
Route::middleware(['auth', 'role:client'])->prefix('cliente')->name('cliente.')->group(function () {

    Route::get('/dashboard', [ClienteDashboard::class, 'index'])->name('dashboard');

    Route::get('/perfil-salud', [PerfilSaludController::class, 'show'])->name('perfil');
    Route::put('/perfil-salud', [PerfilSaludController::class, 'update'])->name('perfil.update');

    Route::resource('mediciones', MedicionController::class)->only(['index', 'create', 'store', 'show']);

    Route::get('/protocolos',      [ProtocoloController::class, 'index'])->name('protocolos.index');
    Route::get('/protocolos/{id}', [ProtocoloController::class, 'show'])->name('protocolos.show');

    Route::get('/cuestionarios',                     [CuestionarioController::class, 'index'])->name('cuestionarios.index');
    Route::get('/cuestionarios/{survey}/responder',  [CuestionarioController::class, 'responder'])->name('cuestionarios.responder');
    Route::post('/cuestionarios/{survey}/responder', [CuestionarioController::class, 'guardar'])->name('cuestionarios.guardar');

    Route::get('/archivos',         [ArchivoController::class, 'index'])->name('archivos.index');
    Route::get('/archivos/subir',   [ArchivoController::class, 'create'])->name('archivos.create');
    Route::post('/archivos',        [ArchivoController::class, 'store'])->name('archivos.store');
    Route::delete('/archivos/{id}', [ArchivoController::class, 'destroy'])->name('archivos.destroy');
});