<?php

namespace App\Http\Middleware;

use Closure;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class dVerifyJWTToken
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        try {
            $user = JWTAuth::toUser($request->input('token'));
        } catch (JWTException $e) {
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException) {
                return response()->json([
                    'status' => 401,
                    'message' => 'Token Expired'
                ], 401);
            } else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException) {
                return response()->json(['token_invalid'], 401);
            } else {
                return response()->json(['error' => 'Token is required']);
            }
            return $next($request);
        }
    }
}
