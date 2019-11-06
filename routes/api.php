<?php

use Illuminate\Support\Facades\Route;
use Vyuldashev\LaravelOpenApi\Http\OpenApiController;

Route::group(['as' => 'open-api.'], function () {
    Route::get(config('openapi.route.uri'), [OpenApiController::class, 'show'])->name('specification');
});
