<?php 

namespace App\Helper;

use Firebase\JWT\JWT;



class JWTToken 
{
    public static function createToken($userEmail, $userID) : string
    {
        $key = env('JWT_SECRET_KEY');

        $payload = [
            'iss' => 'LaravelJWT',
            'iat' => time(),
            'exp' => time() + (60 * 60), 
            'userEmail' => $userEmail,
            'userID' => $userID
        ];

        return JWT::encode($payload, $key, 'HS256');
    }

}

