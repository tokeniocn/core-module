<?php

namespace Modules\Core\Http\Middleware;


class ForceResponseJson
{
    /**
     * Change the Request headers to accept "application/json" first
     * in order to make the wantsJson() function return true
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     *
     * @return mixed
     */
    public function handle($request, $next)
    {
        $request->headers->set('Accept', 'application/json');

        return $next($request);
    }
}
