<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class SwaggerUiServiceProvider extends ServiceProvider
{
/**
* Register the Swagger UI gate.
*
* This gate determines who can access Swagger UI in non-local environments.
*
* @return void
*/
    public function boot() : void
    {
        Gate::define('viewSwaggerUI', function ($user = null) {
            return in_array(optional($user)->email, [
                //
            ]);
        });
    }
}
