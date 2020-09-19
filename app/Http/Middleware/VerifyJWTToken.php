<?php

namespace App\Http\Middleware;

use Closure;
//use http\Env\Request;
use JWTAuth;
use Illuminate\Http\Request;

class VerifyJWTToken
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            $user = JWTAuth::toUser($request->input('token'));
        }catch (JWTException $e) {
            if($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException) {
                return response()->json([
                    'status' => 498,
                    'message'=> 'token_expired'
                ]);
            }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException) {
                return response()->json([
                    'status' => 401,
                    'message' => 'token_invalid'
                ]);
            }else{
                return response()->json(['error'=>'Token is required']);
            }
        }
        return $next($request);
    }
}
