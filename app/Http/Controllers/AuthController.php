<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Mail\OtpMail;
use App\Helper\JWTToken;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
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

     public function sendOtp(Request $request) :JsonResponse
     {

       try{
        $request->validate([
            'email' => 'required|email',
        ]);
        
        $email = $request->input('email');
        $otp = rand(100000, 999999);
        $count = User::where('email', '=', $email)->count();

        if($count > 0) {
            Mail::to($email)->queue(new OtpMail($otp));

            User::where('email', $email)->update(['otp' => $otp]);

            return response()->json([
                'status' => 'success',
                'message' => 'OTP sent successfully',
            ], 200);
        }else {
            return response()->json([
                'status' => 'error',
                'message' => 'Email not registered',
            ], 404);
        }
       }catch(Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'Failed to send OTP',
            'error' => $e->getMessage(),
        ], 500);
       }
     }

    /*
     * Verify OTP
     * @param Request $request
     * @return JsonResponse
     */
    public function verifyOtp(Request $request) :JsonResponse
    {
        try{
            $request->validate([
                'email' => 'required|email',
                'otp' => 'required|integer',
            ]);

            $email = $request->input('email');
            $otp = $request->input('otp');

            $count = User::where('email', $email)
                    ->where('otp', $otp)->count();

            if ($count > 0) {
                //Update the user record to set the otp to null
                User::where('email', $email)
                    ->update(['otp' => '0']);

                //Pass JWT token to initiate the session
                $token = JWTToken::CreateTokenForSetPassword($email);
                return response()->json([
                    'status' => 'success',
                    'message' => 'OTP verified successfully',
                    'token' => $token,
                ], 200)->cookie('Token', $token . time()+60*24*30);
            }else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid OTP',
                ], 401);
            }  
        }catch(Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to verify OTP',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /*
     * Set Password
     * @param Request $request
     * @return JsonResponse
     */
    public function setPassword(Request $request) :JsonResponse 
    {
        try{

            $request->validate([
                'password' => 'required|string|min:8',
            ]);

            $email = $request->header('email');
            $password = $request->input('password');
            User::where('email','=',$email)
                ->update(['password' => Hash::make($password)]);

            return response()->json([
                'status' => 'success',
                'message' => 'Password set successfully',
            ], 200);
        }catch(Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to set password',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
     
}
