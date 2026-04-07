<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
<<<<<<< HEAD
    return response()->json(['message' => 'JobNow API']);
=======
    return view('welcome');
>>>>>>> origin/feature/dev
});
