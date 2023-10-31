<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\{LoginRequest, RegisterRequest};
use App\Http\Resources\Auth\AuthResource;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Create a new user in storage.
     * 
     * @param RegisterRequest $request
     * @return AuthResource
     */
    public function register(RegisterRequest $request)
    {
        return new AuthResource(User::create($request->validated()));
    }

    /**
     * Login the user.
     * 
     * @param LoginRequest $request
     * @return AuthResource
     */
    public function login(LoginRequest $request)
    {
        $user = User::whereEmail($request->validated()['email'])->first();

        if (!$user || !password_verify($request->validated()['password'], $user->password)) {
            return response()->json(['data' => ['message' => 'Invalid credentials']], 401);
        }

        $user->token = $user->createToken('auth_token')->plainTextToken;

        return new AuthResource($user);
    }
}
