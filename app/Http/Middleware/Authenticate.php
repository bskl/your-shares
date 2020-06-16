<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return string|null
     */
    protected function redirectTo(Request $request)
    {
        return $request->expectsJson()
            ? response()->json(['message' => 'Unauthenticated.'], Response::HTTP_UNAUTHORIZED)
            : route('login');
    }
}
