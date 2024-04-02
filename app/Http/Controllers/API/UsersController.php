<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;

class UsersController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $users = User::all()->map(function ($user) {
                return [
                    'email' => $user->email,
                    'created_at' => $user->created_at,
                    'updated_at' => $user->updated_at,
                ];
            });

            return $this->handleResponseNoPagination('Users retrieved successfully', $users, 200);
        } catch (Exception $e) {
            return $this->handleError($e->getMessage(), 400);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request -> validate([
                'email' => ['required','email', 'unique:users'],
                'password' => 'required',
                'confirm_password' => ['required', 'same:password']
            ]);

            $input['password'] = bcrypt($request['password']);

            $user = User::create($request->all());
            return $this->handleResponseNoPagination('User created successfully', $user);
        } catch (Exception $e) {
            return $this->handleError($e->getMessage(), 400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
        $user = User::find($id);
        if ($user) {
            return $this->handleResponseNoPagination('User retrieved successfully', [
                'email' => $user->email,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at
            ], 200);
        } else {
            return $this->handleError('User not found', 400);
        }
        } catch (Exception $e) {
            return $this->handleError($e->getMessage(), 400);
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
            return $this->handleError('User not found', 400);
        }
        } catch (Exception $e) {
            return $this->handleError($e->getMessage(), 400);
        }
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    { 
        try {
        $user = User::find($user->id);
        if ($user) {
            $user->delete();
            return $this->handleResponseNoPagination('User deleted successfully', $user, 200);
        } else {
            return $this->handleError('User not found', 400);
        }
        } catch (Exception $e) {
            return $this->handleError($e->getMessage(), 400);
        }
    }
}
