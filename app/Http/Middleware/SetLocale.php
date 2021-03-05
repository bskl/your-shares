<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $locale = $request->getPreferredLanguage(config('app.languages'));

        if (Auth::check() && in_array(Auth::user()->locale, config('app.languages'))) {
            $locale = Auth::user()->locale;
        }

        App::setLocale($locale);
        Carbon::setLocale(app()->getLocale());

        return $next($request);
    }
}
