<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json([
        'success' => true,
        'message' => 'G. Collection API is running!'
    ]);
});
Route::get('/sajid', function () {
    return view('sajid');
});