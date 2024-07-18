<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\EnderecoController;

Route::get('/search/local/{ceps}', [EnderecoController::class, 'search']);

Route::get('/', function () {
    return view('welcome');
});
