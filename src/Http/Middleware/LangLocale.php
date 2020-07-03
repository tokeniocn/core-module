<?php

namespace Modules\Core\Http\Middleware;

class LangLocale
{
    public function handle($request, $next)
    {
        $locale = $request->locale;

        if ( ! $locale && $request->hasSession()) {
            $locale = $request->session()->get('locale');
        }

        if ( ! $locale && $request->hasHeader('Content-Language')) {
            $locale = $request->header('Content-Language');
        }

        // take the default local language
        if ( ! $locale) {
            $locale = config('app.locale');
        }
        // check the languages defined is supported
        if ( ! in_array($locale, config('app.supported_locales', ['zh_CN', 'en']))) {
            // respond with error
            $locale = config('app.fallback_locale');
        }

        if ($request->hasSession()) {
            $request->session()->set('locale', $locale);
        }

        app()->setLocale($locale);

        // get the response after the request is done
        $response = $next($request);

        // set Content Languages header in the response
        $response->headers->set('Content-Language', $locale);

        // return the response
        return $response;
    }
}
