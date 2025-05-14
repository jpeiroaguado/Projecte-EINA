<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IniciController;
use App\Http\Controllers\ContextController;
use App\Http\Controllers\ConfiguracioIAController;
use App\Http\Controllers\ConversaController;
use App\Http\Controllers\MissatgeController;

Route::get('/', function () {
    return view('welcome');
});

// Ruta original de Breeze, cuan faja vistes ja vec si cambiarla
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Perfil d'usuari (ho ha creat tot Breeze... :D)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});



// Rutes comunes per a converses i missatges
Route::middleware('auth')->group(function () {
    Route::get('/panell-professor', [ConfiguracioIAController::class, 'index'])->name('configuracio.index');
    Route::get('/panell-professor/{id}/edit', [ConfiguracioIAController::class, 'edit'])->name('configuracio.edit');
    Route::put('/panell-professor/{id}', [ConfiguracioIAController::class, 'update'])->name('configuracio.update');
    Route::post('/panell-professor', [ConfiguracioIAController::class, 'store'])->name('configuracio.store');
    Route::post('/panell-professor/{id}/activar', [ConfiguracioIAController::class, 'activar'])->name('configuracio.activar');
});


require __DIR__.'/auth.php';
