<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use App\Http\Resources\UserResource;
use App\Notifications\NewUserNotification;
use App\Models\User;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'password' => ['required', 'string', 'min:8', 'confirmed'],
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validator->errors()->first(),
                    'errors' => $validator->errors()
                ], 400);
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            $token = $user->createToken('api-token')->plainTextToken;

            Log::info('User registered successfully via API', ['user_id' => $user->id, 'email' => $user->email]);

            // Notify all admins about the new user
            $admins = User::where('is_admin', true)->get();
            foreach ($admins as $admin) {
                $admin->notify(new NewUserNotification($user));
            }

            return response()->json([
                'status' => true,
                'message' => 'Registration successful',
                'data' => [
                    'user' => new UserResource($user),
                    'token' => $token,
                ]
            ], 201);

        } catch (\Throwable $e) {
            Log::error('API Error in AuthController@register: ' . $e->getMessage(), [
                'exception' => $e,
                'request' => $request->except(['password', 'password_confirmation'])
            ]);

            return response()->json([
                'status' => false,
                'message' => 'An error occurred while registering.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => ['required', 'email'],
                'password' => ['required'],
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validator->errors()->first(),
                    'errors' => $validator->errors()
                ], 400);
            }

            if (Auth::attempt($request->only('email', 'password'))) {
                $user = Auth::user();
                $token = $user->createToken('api-token')->plainTextToken;

                Log::info('User logged in successfully via API', ['user_id' => $user->id, 'email' => $user->email]);

                return response()->json([
                    'status' => true,
                    'message' => 'Login successful',
                    'data' => [
                        'user' => new UserResource($user),
                        'token' => $token,
                    ]
                ], 200);
            }

            Log::warning('Failed API login attempt', ['email' => $request->email]);

            return response()->json([
                'status' => false,
                'message' => 'The provided credentials do not match our records.'
            ], 401);

        } catch (\Throwable $e) {
            Log::error('API Error in AuthController@login: ' . $e->getMessage(), [
                'exception' => $e,
                'email' => $request->email
            ]);

            return response()->json([
                'status' => false,
                'message' => 'An error occurred while logging in.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function user(Request $request)
    {
        try {
            return response()->json([
                'status' => true,
                'message' => 'User retrieved successfully',
                'data' => new UserResource($request->user())
            ], 200);
        } catch (\Throwable $e) {
            Log::error('API Error in AuthController@user: ' . $e->getMessage(), [
                'exception' => $e,
                'user_id' => $request->user()?->id
            ]);

            return response()->json([
                'status' => false,
                'message' => 'An error occurred while retrieving user data.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function logout(Request $request)
    {
        try {
            $userId = $request->user()->id;
            $request->user()->currentAccessToken()->delete();

            Log::info('User logged out successfully via API', ['user_id' => $userId]);

            return response()->json([
                'status' => true,
                'message' => 'Successfully logged out',
                'data' => null
            ], 200);
        } catch (\Throwable $e) {
            Log::error('API Error in AuthController@logout: ' . $e->getMessage(), [
                'exception' => $e,
                'user_id' => $request->user()?->id
            ]);

            return response()->json([
                'status' => false,
                'message' => 'An error occurred while logging out.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
