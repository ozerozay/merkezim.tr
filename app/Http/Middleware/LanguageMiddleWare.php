<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LanguageMiddleWare
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        $language = 'tr';

        if ($request->has('lang')) {
            if ($request->has('lang')) {
                if (in_array($request->get('lang'), ['en', 'tr'])) {
                    $language = $request->get('lang');
                    session()->put('locale', $language);
                }
            }
        } else {
            if (session()->get('locale')) {
                $language = session()->get('locale');
            }
        }

        app()->setLocale($language);

        return $next($request);
    }
}
