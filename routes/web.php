<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InitialController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SessaoController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [InitialController::class, 'index'])->name('filmes.index');

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::get('/sessoes', [SessaoController::class, 'index'])->name('sessoes.index');

Route::get('/sessoes/{id}', [SessaoController::class, 'sessoesFilme'])->name('sessoes.filme');
