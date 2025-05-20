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
        $token = $request->cookie('token');
        $payload = JWTToken::verifyToken($token);
        if ($payload === 'unauthorized') {
            return response()->json([
                'status' => 'failed',
                'message' => 'You are not authorized to access this resource.',
            ], 200);
        } else {
            $request->headers->set('email', $payload->userEmail);

            if (isset($payload->userID)) {
                $request->headers->set('user_id', $payload->userID);
            }
            return $next($request);
        }
    }
}
