<?php

namespace App\Http\Controllers\API;

use App\Models\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;


class RolesController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $roles = Role::all();
            return $this->handleResponseNoPagination('Roles retrieved successfully', $roles, 200);
        } catch (\Exception $e) {
            return $this->handleError($e->getMessage(), 400);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',

            ]);
        
            $role = Role::create($request->all());

            return $this->handleResponseNoPagination('Role created successfully', $role, 200);
        } catch (\Exception $e) {
            return $this->handleError($e->getMessage(), 400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role)
    {
        try{
        $role = Role::where('id', $role->id)->with('users')->first();
        if ($role) {
            return $this->handleResponseNoPagination('Role retrieved successfully', $role, 200);
        } else {
            return $this->handleError('Role not found', 404);
        } 
        } catch (\Exception $e) {
            return $this->handleError($e->getMessage(), 400);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {
        try {
            $role= Role::find($role->id);
            if ($role) {
                $role->update($request->all());
                return $this->handleResponseNoPagination('Role updated successfully', $role, 200);
            } else {
                return $this->handleError('Role not found', 404);
            }
        } catch (\Exception $e) {
            return $this->handleError($e->getMessage(), 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        try {
            $role = Role::find($role->id);
            if ($role) {
                $role->delete();
                return $this->handleResponseNoPagination('Role deleted successfully', $role, 200);
            } else {
                return $this->handleError('Role not found', 404);
            }
        } catch (\Exception $e) {
            return $this->handleError($e->getMessage(), 400);
        }
    }
}
