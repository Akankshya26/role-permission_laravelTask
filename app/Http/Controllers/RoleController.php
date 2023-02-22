<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    //view list of roles function
    public function index(Request $request)
    {
        //validation for sorting ,seraching & pagination
        $request->validate([
            'perpage'     => 'required|numeric',
            'currentpage' => 'required|numeric',
            'sortField'   => 'nullable|string',
            'sortOrder'   => 'nullable|in:asc,desc',
            'name'        => 'nullable|string'

        ]);

        $role =  Role::query();
        //sorting function
        if ($request->sortField && $request->sortOrder) {
            $role = $role->orderBy($request->sortField, $request->sortOrder);
        } else {
            $role = $role->orderBy('id', 'DESC');
        }
        //searching function
        if (request()->has('search')) {
            $role->where('name', 'Like', '%' . request()->input('search') . '%');
        }
        //pagination function
        $perpage     = $request->perpage;
        $currentpage = $request->currentpage;
        $role        = $role->skip($perpage * ($currentpage - 1))->take($perpage);

        return response()->json([
            "success" => true,
            "message" => "role list",
            "data"    => $role->get()

        ]);
    }
    //create module function
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
    //Role edit function
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
    //delete module function

    public function destroy($id, Request $request)
    {
        $request->validate([
            'softDelete' => 'required|boolean'
        ]);
        $role = Role::findOrFail($id);
        // dd($request->all());

        if ($request->softDelete) {
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
