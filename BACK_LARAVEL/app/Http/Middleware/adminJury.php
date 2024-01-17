<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class adminJury
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {        
        $this->validateJury();
        
        return $next($request);
    }

    private function validateJury()
    {
        if(!auth()->user()->parcours->first())
            abort(400,"votre parcours est introuvable veuillez contacter l'admin pour y assigner");

    }
}
