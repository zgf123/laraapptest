<?php

namespace App\Http\Middleware;

use Closure;

class ChangeLocal
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $language = $request->header('accept-language');
        if($language){
            \App::setLocale($language);
        }

        return $next($request);
    }
}
