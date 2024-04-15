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
        $search = $request->q;
        $perPage = $request->input('per_page', 10);

        try {
            $query = User::when($search, function ($query) use ($search) {
                return $query->where('username', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%");
            });

            $users = $query->paginate($perPage)->withQueryString();
            $users->getCollection()->transform(function ($user) {
                return [
                    'id' => $user->id,
                    'username' => $user->username,
                    'email' => $user->email,
                    'role_name' => $user->role->name ?? 'User',
                    'created_at' => $user->created_at->format('Y-m-d H:i:s'),
                    'updated_at' => $user->updated_at->format('Y-m-d H:i:s'),
                    'is_active' => $user->is_active ? 'Actif' : 'Banni',
                ];
            });
           

            return $this->handleResponse('Users retrieved successfully', $users, 200);
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
            $userData = [
                'id' => $user->id,
                'username' => $user->username,
                'email' => $user->email,
                'role_name' => $user->role->name ?? 'User'
            ];
            return $this->handleResponseNoPagination('User retrieved successfully', $userData, 200);
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

   public function getprofile (Request $request)
   {
       try {
           $user = $request->user();
           $userData = [
               'id' => $user->id,
               'username' => $user->username,
               'email' => $user->email,
               'role_name' => $user->role->name ?? 'User',
           ];
           return $this->handleResponseNoPagination('User profile retrieved successfully', $userData, 200);
       } catch (Exception $e) {
           return $this->handleError($e->getMessage(), 400);
       }
   }
}
