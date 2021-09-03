<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    // Retrieve current user based on token
    public function userProfile()
    {
        $currentUser = auth()->user();
        return response()->json([
            'sucess' => true,
            'message' => 'User retrived sucessfully',
            'user' => $currentUser
        ]);
    }
    // Register User
    public function register(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users',
            'phone_number' => 'required|min:10',
            'password' => 'required|confirmed|min:8'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()
            ]);
        }
        $user = User::create(array_merge($validator->validated(), ['password' => bcrypt($input['password'])]));
        return response()->json([
            'success' => true,
            'message' => 'User logged in successfully',
            'data' => $user
        ], 201);
    }
    // Login User
    public function login(Request $request)
    {
        $input = $request->only('email', 'password');
        $validator = Validator::make($input, [
            'email' => 'required',
            'password' => 'required|min:3'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()
            ]);
        }
        if (!$token = auth()->attempt($input)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials'
            ]);
        }
        return response()->json([
            'success' => true,
            'message' => 'User logged in succesfully',
            'user' => $token
        ]);
    }

    public function logout()
    {
        auth()->logout();

        return response()->json([
            'sucess' => true,
            'message' => 'User logged out successfully'
        ]);
    }
}
