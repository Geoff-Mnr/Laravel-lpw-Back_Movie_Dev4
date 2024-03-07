<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return User::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            request -> validate([
                'email' => ['required|email', 'unique:users'],
                'password' => 'required',
                'confirm_password' => ['required, same:password']
            ]);
            $input['password'] = bcrypt($input['password']);
            $user = User::create($request->all());
            return $this->handleResponseNoPagination('User created successfully', $user, 201);
        } catch (Exception $e) {
            return $this->handleResponseNoPagination($e->getMessage(), 400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        try {
        if ($user) {
            return $this->handleResponseNoPagination('User retrieved successfully', $user, 200);
        } else {
            return $this->handleResponseNoPagination('User not found', 404);
        }
        } catch (Exception $e) {
            return $this->handleResponseNoPagination($e->getMessage(), 400);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        try {
        $user = User::find($user->id);
        if ($user) {
            $user->update($request->all());
            return $this->handleResponseNoPagination('User updated successfully', $user, 200);
        } else {
            return $this->handleResponseNoPagination('User not found', 404);
        }
        } catch (Exception $e) {
            return $this->handleResponseNoPagination($e->getMessage(), 400);
        }
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
    }
}
