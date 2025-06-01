<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ContextController;
use App\Http\Controllers\ConversaController;
use App\Http\Controllers\ConfiguracioIAController;
use App\Http\Controllers\GeminiController;
use Illuminate\Support\Facades\Broadcast;

/*Redirecció inicial segons rol*/
Route::get('/', function () {
    return redirect()->route('login'); // Mostra login si no autenticat
});

Route::get('/redirect-segons-rol', function () {
    $usuari = Auth::user();

    if ($usuari->rol === 'professor') {
        return redirect()->route('configuracio.index');
    }

    if ($usuari->rol === 'alumne') {
        return redirect()->route('alumne.chat');
    }

    abort(403, 'Rol desconegut');
})->middleware(['auth', 'verified'])->name('home'); // Laravel Breeze usa aquesta per defecte

/* Perfil d’usuari (Breeze) */
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*Panell del professor (configuració IA)*/
Route::middleware(['auth'])->group(function () {
    //Route::get('/panell-professor', [ConfiguracioIAController::class, 'index'])->name('configuracio.index');
    Route::get('/panell-professor', [ConversaController::class, 'panell'])->name('configuracio.index');
    Route::get('/panell-professor/conversa/{id}', [ConversaController::class, 'carregarPerProfessor'])
    ->name('professor.carregarConversa');
    Route::get('/panell-professor/{id}/edit', [ConfiguracioIAController::class, 'edit'])->name('configuracio.edit');
    Route::put('/panell-professor/{id}', [ConfiguracioIAController::class, 'update'])->name('configuracio.update');
    Route::post('/converses/{conversa}/actualitzar-context', [ConversaController::class, 'actualitzarContext'])
        ->name('converses.actualitzarContext');
    Route::get('/panell-professor/conversa-id/{id}', [ConversaController::class, 'mostrarConversaPerId']);
});

/*Gestió de contextos/*/
Route::middleware(['auth'])->group(function () {
    Route::resource('contextes', ContextController::class)->except(['index']);
    Route::post('/contextes/{id}/activar', [ContextController::class, 'activate'])->name('contextes.activate');
});

/*Vista per a l’alumne (xat amb la IA)*/
Route::middleware('auth')->group(function () {
    Route::get('/alumne/chat', [ConversaController::class, 'xatAmbIA'])->name('alumne.chat');
    Route::post('/conversa/{id}/missatge', [GeminiController::class, 'enviar'])->name('conversa.enviar');
});

Broadcast::routes(['middleware' => ['auth:sanctum']]);

require __DIR__.'/auth.php';
