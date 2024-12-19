<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;

class AuthController extends Controller
{
    public function register(AuthRequest $request)
    {
        User::create([
            "name" => $request->name,
            "username" => $request->username,
            "email" => $request->email,
            'password' => $request->password,
            'phone' => $request->phone,
            'role' => 'user', // Default role
        ]);
        return Response::json(['message' => "Registered Successfully"], 201);
    }
    public function login(Request $request)
    {
        $user = $request->validate([
            "email" => "required",
            "password" => "required"
        ]);
        $user = User::where("email", $request->email)->first();
        if (!$user) {
            return response()->json(["message" => "Invalid Credential"], 401);
        }
        if (!Hash::check($request->password, $user->password)) {
            return response()->json(["message" => "Invalid Credential"], 401);
        }
        $token = $user->createToken('auth_token')->plainTextToken;

        $cookie = cookie('jwt', $token, 60 * 24);

        return response()->json(["message" => "Logged in", "user" => $user, "token" => $token], 200)->withCookie($cookie);
    }
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        $cookie = cookie()->forget('jwt');
        return response()->json(['message' => 'Logged out successfully'], 200)->withCookie($cookie);
    }
    public function user(Request $request)
    {
        $user = $request->user();
        if ($user) {
            return response()->json(["user" => $user], 200);
        }
        return response()->json(['message' => 'Unauthenticated'], 401);
    }
    public function users()
    {
        return Response::json(User::all(), 200);
    }
    public function updaterole(Request $request, User $id)
    {
        $validatedData = $request->validate([
            'role' => 'required|in:admin,assistant,user', // Ensure the role is one of the expected values
        ]);

        // Normalize the role to lowercase and update
        $id->update(['role' => strtolower($validatedData['role'])]);

        return Response::json($id);
    }
}
