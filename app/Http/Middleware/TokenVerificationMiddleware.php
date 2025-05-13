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
       $token = $request->cookie('Token');
        $result = JWTToken::VerifyToken($token);
        if($result == 'unauthorized') {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], 401);
        }else{
            $request->headers->set('email', $result['email']);
            $request->headers->set('userID', $result['userID']);
            return $next($request);
        }
         
    }
}
