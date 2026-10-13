<?php

namespace App\Providers;

use Illuminate\Http\Request;
use Laravel\Fortify\Fortify;
use Illuminate\Support\ServiceProvider;
use App\Actions\Fortify\CreateNewUser;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;

class FortifyServiceProvider extends ServiceProvider
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
        // Registro ufficiale Aulab per la pagina di Registrazione
        Fortify::registerView(function () {
            return view('auth.register');
        });

        // Registro ufficiale Aulab per la pagina di Login
        Fortify::loginView(function () {
            return view('auth.login');
        });

        // Configurazione del limitatore richiesto da Laravel 11 per sbloccare il Login
        RateLimiter::for('login', function (Request $request) {
            $email = (string) $request->email;
            return Limit::perMinute(5)->by($email . $request->ip());
        });
    }
}
