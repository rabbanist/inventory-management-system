<?php

namespace App\Http\Middleware;

use Closure;
use App\Helper\JWTToken;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TokenVerificationMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            $token = $request->header('token');
            if (!$token) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Token is required',
                ], 401);
            }
            $result = JWTToken::verifyToken($token);
            if ($result === 'unauthorized') {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthorized access',
                ], 401);
            } else {
                $request->headers->set('email', $result);
                return $next($request);
            }
        } catch (\Throwable $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Token verification failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}