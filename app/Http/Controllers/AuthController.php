<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {

        $user = User::where('username', $request->username)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'username atau password yang anda masukkan tidak tepat',
            ], 401);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'login berhasil',
            'data' => [
                'token' => $user->createToken("test")->plainTextToken,
                'admin' => new UserResource($user),
            ],

        ], 200);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(["status" => "success", 'message' => 'logout berhasil'], 200);
    }
}
