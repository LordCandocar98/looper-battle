<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Support\Facades\Response;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;


class JwtMiddleware extends BaseMiddleware
{

    public function handle($request, Closure $next)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
        } catch (Exception $e) {
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException){
                return Response::json([
                    'code' => 401,
                    'message' => 'El token es inválido.',
                    'errors' => ['token' => 'El token es inválido.'],
                    'data' => NULL
                ], 401, [], JSON_PRETTY_PRINT);
            }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){
                return Response::json([
                    'code' => 401,
                    'message' => 'El token ha caducado.',
                    'errors' => ['token' => 'El token ha caducado.'],
                    'data' => NULL
                ], 401, [], JSON_PRETTY_PRINT);
                //return response()->json(['code' => 'Token is Expired']);
            }else{
                return Response::json([
                    'code' => 401,
                    'message' => 'Token de autorización no encontrado',
                    'errors' => ['token' => 'Token de autorización no encontrado'],
                    'data' => NULL
                ], 401, [], JSON_PRETTY_PRINT);
            }
        }
        return $next($request);
    }
}
