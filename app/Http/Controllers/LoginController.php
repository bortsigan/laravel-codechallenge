<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\LoginRequest;
use Auth;

class LoginController extends Controller
{
    
    /**
     * Authenticate User
     * 
     * @param LoginRequest $request
     * 
     **/
    public function login(LoginRequest $request): JsonResponse
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $user = Auth::user();
        $token = $user->createToken('authToken')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'token' => $token
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully']);
    }
}
