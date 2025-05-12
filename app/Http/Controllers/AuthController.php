<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Helper\JWTToken;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\RegistrationReqeust;

class AuthController extends Controller
{
    public function userRegistration(RegistrationReqeust $request) :JsonResponse 
    {
        try {
            User::create($request->validated());
            return response()->json([
                'status' => 'success',
                'message' => 'User registered successfully',
            ], 201);
        }catch(Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'User registration failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    
    /* User Login
     * @param LoginReqeust $request
     * @return JsonResponse
     */

     public function userLogin(LoginRequest $request) :JsonResponse
     {
        try {
            $validated = $request->validated();
            // 1) Fetch the user by email
             $user = User::where('email', $validated['email'])->first();

            // 2) If user exists and password matches...
            if ($user && Hash::check($validated['password'], $user->password)) {

                $token = JWTToken::createToken($validated['email'], $user->id);
                return response()->json([
                    'status' => 'success', 
                    'message' => 'User logged in successfully',
                ], 200)->cookie('Token',  $token . time()+60*24*30);
            }else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid email or password',
                ], 401);
            }
        }catch(Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'User login failed',
                'error' => $e->getMessage(),
            ], 500);
        }
     }

     
}
