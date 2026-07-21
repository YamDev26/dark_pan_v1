<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class hensHawUserHasEnseigment
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if($request->user()->role->libelle === 'enseignant') {
            return $next($request);
        }
        else{
            return back()->with(['str' => 'danger', 'msg' => 'Acces non autorisé !']);
        }
        return $next($request);
    }
}
