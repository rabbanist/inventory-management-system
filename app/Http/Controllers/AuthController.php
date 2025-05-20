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
    public function userRegistration(RegistrationReqeust $request): JsonResponse
    {
        try {
            User::create($request->validated());
            return response()->json([
                'status' => 'success',
                'message' => 'User registered successfully',
            ], 201);
        } catch (Exception $e) {
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

    public function userLogin(Request $request)
    {
        $user = User::where('email', $request->email)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            // Password matched - create token
            $token = JWTToken::createToken($user->email, $user->id);
            return response()->json([
                'status' => 'success',
                'message' => 'User Login successful'
            ], 200)->cookie('token', $token, 60 * 24 * 30); // expires in 30 day (in minutes)
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'User Login failed',
                'error' => 'Invalid email or password.'
            ], 401);
        }
    }

    public function sendOtp(Request $request): JsonResponse
    {

        try {
            $request->validate([
                'email' => 'required|email',
            ]);

            $email = $request->input('email');
            $otp = rand(100000, 999999);
            $count = User::where('email', '=', $email)->count();

            if ($count > 0) {
                Mail::to($email)->queue(new OtpMail($otp));

                User::where('email', $email)->update(['otp' => $otp]);

                return response()->json([
                    'status' => 'success',
                    'message' => 'OTP sent successfully',
                ], 200);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Email not registered',
                ], 404);
            }
        } catch (Exception $e) {
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
    public function verifyOtp(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|integer',
        ]);
        try {
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
                ], 200)->cookie('Token', $token . time() + 60 * 24 * 30);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid OTP',
                ], 401);
            }
        } catch (Exception $e) {
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
    public function resetPassword(Request $request): JsonResponse
    {

        $request->validate([
            'password' => 'required|min:8|confirmed',
        ]);

        try {
            $email = $request->header('email');
            $password = bcrypt($request->password);
            User::where('email', $email)->update(['password' => $password]);

            return response()->json([
                'status' => 'success',
                'message' => 'Password Reset successfully',
            ], 200);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Unable to reset password'
            ]);
        }
    }


    public function logout()
    {
        return response()->json([
            'status' => 'success',
            'message' => 'User Logout successful'
        ])->cookie('token', null, -1);
    }

}
