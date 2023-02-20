<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    //view list of roles
    public function index()
    {
        $role =  Role::all();
        return response()->json([
            "success" => true,
            "message" => "role list",
            "data"    => $role->load('permissions')

        ]);
    }
    //create module
    public function create(Request $request)
    {
        //role validation
        $request->validate([
            'name'        => 'required',
            'description' => 'required',

        ]);
        $role = Role::create($request->only('name', 'description'));
        $role->permissions()->attach($request->roles);
        $role->save();
        return response()->json([
            "success" => true,
            "message" => "role created",
            "data"    => $role
        ]);
    }
    //Role edit
    public function edit($id)
    {
        $role = Role::with('permissions')->findOrFail($id);
        return response()->json([
            "success" => true,
            "message" => "Role founded ",
            "data"    => $role
        ]);
    }
    public function update(Request $request, $id)
    {
        //role validation
        $request->validate([
            'name'        => 'required',
            'description' => 'required',

        ]);
        $role = Role::findOrFail($id);
        $role->update($request->only('name', 'description', 'is_active'));
        // $role->permissions()->detach();
        $role->permissions()->sync($request->roles);
        return response()->json([
            "success" => true,
            "message" => "role updated",
            "data"    => $role
        ]);
    }
    //delete module

    public function destroy($id, Request $request)
    {
        $request->validate([
            'softDelete' => 'required|boolean'
        ]);
        $role = Role::findOrFail($id);
        if ($request->softDelete) {
            // dd('softDelete');
            if ($role->permissions()->count() > 0) {
                ($role->permissions()->delete());
            }
            $role->delete();
        } else {
            // dd('hardDelete');
            if ($role->permissions()->count() > 0) {
                ($role->permissions()->forceDelete());
            }
            $role->forceDelete();
        }
        return response()->json([
            "success" => true,
            "message" => "role deleted",
        ]);
    }
    //restore deleted data from trashed
    public function restore($id)
    {
        Role::where('id', $id)->withTrashed()->restore();
        return response()->json([
            "success" => true,
            "message" => "user restored",
        ]);
    }
}
