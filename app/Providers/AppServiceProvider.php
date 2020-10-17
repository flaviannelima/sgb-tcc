<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        
        Blade::if('coordenador', function () {
            return auth()->user()->coordenador()->first() && auth()->user()->coordenador()->first()->ativo;
        });

        Blade::if('coordenadoratendente', function () {
            return (auth()->user()->coordenador()->first() && auth()->user()->coordenador()->first()->ativo) || 
            (auth()->user()->atendente()->first() && auth()->user()->atendente()->first()->ativo);
        });
    }
}
