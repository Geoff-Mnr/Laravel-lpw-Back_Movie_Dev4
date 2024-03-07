<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use illuminate\Support\Facades\Storage;

class AuthController extends BaseController
{
    public function login(Request $request)
    {
        try {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
            $token = $user->createToken('LaravelSanctumAuth')->plainTextToken;
            return $this->handleResponseNoPagination('Login successful', ['user' => $user, 'access_token' => $token], 200);
        } else {
            return $this->handleError('Invalid email or password', 401);
        }
        } catch (Exception $e) {
            return $this->handleError($e->getMessage(), 400);
        }
    }

    public function register(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email|unique:users',
                'password' => 'required',
                'confirm_password' => 'required|same:password'
            ]);

            if ($validator->fails()) {
                return $this->handleError($validator->errors(), 400);
            }

            $input = $request->all();
            $input['password'] = bcrypt($input['password']);
            $user = User::create($input);
            $success['token'] = $user->createToken('LaravelSanctumAuth')->plainTextToken;
            return $this->handleResponseNoPagination('User created successfully', ['user' => $user, 'access_token' => $token]);
        } catch (Exception $e) {
            return $this->handleError($e->getMessage(), 500);
        }
    }

    public function logout(Request $request)
    {
        auth()->user()->tokens()->delete();
        return $this->handleResponseNoPagination('Logged out', null, 200);
    }
}