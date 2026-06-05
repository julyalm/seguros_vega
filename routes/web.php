<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Rutas protegidas
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/polizas', [\App\Http\Controllers\PolizaController::class, 'index'])->name('polizas.index');
    Route::get('/polizas/nueva', [\App\Http\Controllers\PolizaController::class, 'create'])->name('polizas.create');
    Route::get('/polizas/{poliza}', [\App\Http\Controllers\PolizaController::class, 'show'])->name('polizas.show');
    Route::put('/polizas/{poliza}', [\App\Http\Controllers\PolizaController::class, 'update'])->name('polizas.update');
    Route::post('/polizas', [\App\Http\Controllers\PolizaController::class, 'store'])->name('polizas.store');
    Route::post('/polizas/{poliza}/toggle-active', [\App\Http\Controllers\PolizaController::class, 'toggleActive'])->name('polizas.toggle-active');
    Route::get('/polizas/{poliza}/download', [\App\Http\Controllers\PolizaController::class, 'downloadFile'])->name('polizas.download');
    Route::get('/polizas/{poliza}/download-recibo', [\App\Http\Controllers\PolizaController::class, 'downloadRecibo'])->name('polizas.download.recibo');

    // Section-specific update routes (admin only)
    Route::middleware(['role:admin'])->group(function () {
        Route::put('/polizas/{poliza}/insured', [\App\Http\Controllers\PolizaController::class, 'updateInsured'])->name('polizas.update.insured');
        Route::put('/polizas/{poliza}/vehicles', [\App\Http\Controllers\PolizaController::class, 'updateVehicles'])->name('polizas.update.vehicles');
        Route::put('/polizas/{poliza}/payments', [\App\Http\Controllers\PolizaController::class, 'updatePayments'])->name('polizas.update.payments');
        Route::put('/polizas/{poliza}/validity', [\App\Http\Controllers\PolizaController::class, 'updateValidity'])->name('polizas.update.validity');
        Route::put('/polizas/{poliza}/reassign-agent', [\App\Http\Controllers\PolizaController::class, 'reassignAgent'])->name('polizas.reassign-agent');
    });

    Route::get('/recibos', [\App\Http\Controllers\ReciboController::class, 'index'])->name('recibos.index');
    Route::put('/recibos/{recibo}/status', [\App\Http\Controllers\ReciboController::class, 'updateStatus'])->name('recibos.status');
    Route::post('/recibos/{recibo}/toggle-active', [\App\Http\Controllers\ReciboController::class, 'toggleActive'])->name('recibos.toggle-active');

    Route::get('/comisiones-ventas', [\App\Http\Controllers\Admin\CommissionVentaController::class, 'index'])->name('comisiones.index');

    Route::put('api/asegurado/update/{rfc}', [\App\Http\Controllers\AseguradoController::class, 'update'])->name('asegurado.update');
    Route::put('api/vehiculo/update/{vin}', [\App\Http\Controllers\VehiculoController::class, 'update'])->name('vehiculo.update');

    // API Lookups
    Route::get('/api/asegurados/{rfc}', [\App\Http\Controllers\AseguradoController::class, 'showByRfc'])->name('api.asegurados.show');
    Route::get('/api/sepomex/{cp}', [\App\Http\Controllers\Api\SepomexController::class, 'lookup'])->name('api.sepomex.show');
    Route::get('/api/polizas/lookup-parent/{numero}', [\App\Http\Controllers\PolizaController::class, 'lookupParent'])->name('api.polizas.lookup-parent');
    Route::get('/api/polizas/check-availability/{numero}', [\App\Http\Controllers\PolizaController::class, 'checkAvailability'])->name('api.polizas.check-availability');
    Route::get('/api/vehiculos/check-vin/{vin}', [\App\Http\Controllers\PolizaController::class, 'checkVinAvailability'])->name('api.vehiculos.check-vin');

    // Admin Only
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/agentes', [\App\Http\Controllers\Admin\AgentController::class, 'index'])->name('agents.index');
        Route::post('/agentes', [\App\Http\Controllers\Admin\AgentController::class, 'store'])->name('agents.store');
        Route::delete('/agentes/{user}', [\App\Http\Controllers\Admin\AgentController::class, 'destroy'])->name('agents.destroy');

        Route::get('/reportes', [\App\Http\Controllers\Admin\ReportController::class, 'index'])->name('reports.index');
    });
});

// Logout (GET por conveniencia para el maquetado, pero Fortify prefiere POST)
Route::match(['get', 'post'], '/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect()->route('login');
})->name('logout');
