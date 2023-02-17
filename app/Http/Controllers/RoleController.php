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
            "message" => "permission list",
            "data"    => $role

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
    public function update(Request $request, $id)
    {
        //role validation
        $request->validate([
            'name'        => 'required',
            'description' => 'required',

        ]);
        $role = Role::findOrFail($id)->update($request->only('name', 'description', 'is_active'));
        return response()->json([
            "success" => true,
            "message" => "role updated",
            "data"    => $role
        ]);
    }
    //delete module

    public function destroy($id)
    {
        $role = Role::findOrFail($id)->delete();
        return response()->json([
            "success" => true,
            "message" => "role deleted",
            "data"    => $role
        ]);
    }
}
