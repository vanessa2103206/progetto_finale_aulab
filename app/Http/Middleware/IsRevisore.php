<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class IsRevisore
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Se l'utente è loggato E ha il flag is_revisore impostato su vero, allora può passare!
        if (Auth::check() && Auth::user()->is_revisore) {
            return $next($request);
        }

        // Altrimenti lo rimbalziamo alla home con un avviso di divieto
        return redirect()->route('homepage')->with('errorMessage', 'Accesso negato: Solo i revisori possono accedere a questa area.');
    }
}
