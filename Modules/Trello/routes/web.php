<?php

use Illuminate\Support\Facades\Route;
use Modules\Trello\Http\Controllers\TrelloController;

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

Route::prefix('trello')->group(function () {
    Route::get('/', [TrelloController::class, 'index'])->name('trello.index');

    Route::post('/card-import', [TrelloController::class, 'import'])->name('trello.import');
});
