<?php

use App\Http\Controllers\NoteController;
use Illuminate\Support\Facades\Route;

Route::get('/health', fn () => response()->json(['status' => 'ok']));

Route::get('/notes', [NoteController::class, 'index']);
Route::get('/notes/{id}', [NoteController::class, 'show'])->whereNumber('id');
Route::post('/notes', [NoteController::class, 'store']);
Route::put('/notes/{id}', [NoteController::class, 'update'])->whereNumber('id');
Route::delete('/notes/{id}', [NoteController::class, 'destroy'])->whereNumber('id');
