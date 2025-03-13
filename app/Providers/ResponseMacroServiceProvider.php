<?php

namespace App\Providers;

use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\ServiceProvider;

class ResponseMacroServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
            Response::macro('success', function ($message, $value = null) {
                  return Response::make([
                        'code' => HttpResponse::HTTP_OK,
                        'message' => $message,
                        'data' => $value
                  ], HttpResponse::HTTP_OK);
            });

            Response::macro('validationError', function ($value) {
                  return Response::make([
                        'code' => HttpResponse::HTTP_UNPROCESSABLE_ENTITY,
                        'message' => 'Validation error found!',
                        'data' => $value
                  ], HttpResponse::HTTP_UNPROCESSABLE_ENTITY);
            });
    }
}
