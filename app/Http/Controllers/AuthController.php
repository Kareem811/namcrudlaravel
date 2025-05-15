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
        if (User::where('email', $request->email)->exists()) {
            return Response::json(['message' => "Email is already used"], 409);
        }
        User::create([
            "name" => $request->name,
            "username" => $request->username,
            "email" => $request->email,
            'password' => $request->password,
            'phone' => $request->phone,
            'role' => $request->email === "namariq@gmail.com" ? "admin" : "user"
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
        // $request->user()->tokens()->delete();
        // $cookie = cookie()->forget('jwt');
        $request->user()->tokens->each(
            function ($token) {
                $token->delete();
            }
        );
        return response()->json(['message' => 'Logged out successfully'], 200);
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
    public function forgetPassword(Request $request)
    {
        // $request->validate([
        //     'email' => 'required|email',
        //     'new_password' => 'required|min:3|confirmed',
        // ]);

        // $user = User::where('email', $request->email)->first();

        // if (!$user) {
        //     return response()->json(['message' => 'User not found'], 404);
        // }

        // // Update the password
        // $user->update([
        //     'password' => Hash::make($request->new_password),
        // ]);

        // return response()->json(['message' => 'Password reset successfully'], 200);
        $request->validate([
            'email' => 'required|email',
            'new_password' => 'required|min:3|confirmed',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        // Check if the new password is the same as the old password
        if (Hash::check($request->new_password, $user->password)) {
            return response()->json(['message' => 'New password must be different from the old password'], 422);
        }

        // Update the password
        $user->update([
            'password' => Hash::make($request->new_password),
        ]);

        return response()->json(['message' => 'Password reset successfully'], 200);
    }
    public function profile($id)
    {
        $user = User::with('messages' , 'bookings')->findOrFail($id);
        return Response::json(['user' => $user]);
    }
    public function updatedata(Request $request , User $id)
    {
        $request->validate([
            'name' => ['required', 'regex:/^[a-zA-Z]+$/u'], // Only letters
            'username' => ['required', 'string', 'unique:users'],
            'email' => [
                'required',
                'email',
                'unique:users',
                'regex:/^[a-zA-Z0-9._%+-]+@(gmail\.com|outlook\.com|yahoo\.com)$/'
            ],
            'phone' => ['nullable', 'regex:/^(9|7)[0-9]{7}$/'],
        ]);
        $id->update([
            "name" => $request->name,
            "username" => $request->username,
            "email" => $request->email,
            'password' => $id->password,
            'phone' => $request->phone,
            'role' =>"user"
        ]);
        return Response::json(['message' => "Updated"], 201);
    }
}
