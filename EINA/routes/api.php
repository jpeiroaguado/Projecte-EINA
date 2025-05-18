<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Exemple bàsic per protegir amb Sanctum
Route::middleware('auth:sanctum')->get('/usuari', function (Request $request) {
    return $request->user();
});
