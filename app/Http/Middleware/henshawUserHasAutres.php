<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class henshawUserHasAutres
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(in_array($request->user()->role->libelle, ['admin', 'fondateur', 'directeur'])) {
            return $next($request);
        }
        else{
            return back()->with(['str' => 'danger', 'msg' => 'Acces non autorisé !']);
        }
    }
}