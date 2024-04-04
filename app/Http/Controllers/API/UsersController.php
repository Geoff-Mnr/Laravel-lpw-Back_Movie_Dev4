<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use App\Models\Role;
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
            $users = User::with('role')->get();
            
            $filteredUsers = $users->map(function ($user) {
                return [
                    'id' => $user->id,
                    'username' => $user->username,
                    'email' => $user->email,
                    'role' => $user->role?->name,
                ];
            });
            return $this->handleResponseNoPagination('Users retrieved successfully', $filteredUsers, 200);
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
                'name' => 'required',
                'email' => ['required','email', 'unique:users'],
                'password' => 'required',
                'confirm_password' => ['required', 'same:password'],
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
        $user = User::where('id', $id)->with('role')->first();
        if ($user) {
            return $this->handleResponseNoPagination('User retrieved successfully', $user, 200);
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
