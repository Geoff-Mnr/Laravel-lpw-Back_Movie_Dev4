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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }
}
