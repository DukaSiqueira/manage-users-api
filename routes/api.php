<?php

use App\Http\Controllers\UserController;
use App\Http\Middleware\HandleApiExceptions;
use Illuminate\Support\Facades\Route;

Route::apiResource("users", UserController::class);
