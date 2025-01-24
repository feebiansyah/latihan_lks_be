<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validateData = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:5'
        ]);

        if ($validateData->fails()) {
            return response()->json(
                [
                    'message' => 'Invalid Field',
                    'errors' => $validateData->errors()
                ],
                422
            );
        }

        if (!Auth::attempt($request->all())) {
            return response()->json(
                [
                    'message' => 'Email or Password is incorrect'
                ],
                401
            );
        }

        $user = User::where('email', $request->email)->first();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json(
            [
                'message' => 'Login Success',
                'user' => [
                    'name' => $user->name,
                    'email' => $user->email,
                    'accessToken' => $token
                ],
            ],
            200
        );
    }


    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(
            [
                'message' => 'Logout Success'
            ],
            200
        );
    }
}   
