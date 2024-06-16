<?php

namespace App\Providers;

use Illuminate\Support\Facades\Response;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Response::macro('unprocessable', function ($error, $errors = []) {
            return response()->json([
                'message' => $error,
                'errors' => $errors,
            ], 422);
        });

        Response::macro('forbidden', function ($error) {
            return response()->json([
                'message' => $error,
            ], 403);
        });

        Response::macro('unauthorized', function () {
            return response()->noContent(401);
        });

        Response::macro('created', function ($data = null) {
            return response()->json([
                'data' => $data,
            ], 201);
        });

        Response::macro('success', function ($data = null) {
            return response()->json([
                'data' => $data,
            ]);
        });

        Response::macro('accepted', function ($message) {
            return response()->json([
                'message' => $message,
            ], 202);
        });

        Response::macro('deleted', function ($data = null) {
            return response()->noContent();
        });

        Response::macro('server_error', function ($data = null) {
            return response()->json(null, 500);
        });

    }

}
