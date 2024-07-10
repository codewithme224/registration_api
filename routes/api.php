<?php

use App\Http\Controllers\SampleModelController;
use App\Http\Controllers\SchoolController;
use Illuminate\Support\Facades\Route;

Route::apiResource('schools', SchoolController::class);
