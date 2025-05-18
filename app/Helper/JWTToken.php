<?php

namespace App\Helper;

use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;



class JWTToken
{
    public static function createToken($userEmail, $userID): string|int
    {
        $key = env('JWT_SECRET_KEY');

        $payload = [
            'iss' => 'Laravel Token',
            'iat' => time(),
            'exp' => time() + (60 * 60),
            'userEmail' => $userEmail,
            'userID' => $userID
        ];

        return JWT::encode($payload, $key, 'HS256');
    }


    public static function CreateTokenForSetPassword($userEmail): string
    {
        $key = env('JWT_SECRET_KEY');
        $payload = [
            'iss' => 'Laravel Token',
            'iat' => time(),
            'exp' => time() + (60 * 60),
            'userEmail' => $userEmail,
        ];
        return JWT::encode($payload, $key, 'HS256');
    }


    /* * Verify the token
     * @param string $token
     * @return array|bool
     */
    public static function verifyToken($token): string
    {
        try {
            $key = env('JWT_SECRET_KEY');
            $decoded = JWT::decode($token, new Key($key, 'HS256'));
            return $decoded->userEmail;
        } catch (Exception $e) {
            return 'unauthorized';
        }
    }
}

