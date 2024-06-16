<?php

use App\Http\Controllers\SampleModelController;
use Illuminate\Support\Facades\Route;

Route::apiResource('sample_model', SampleModelController::class);
