<?php

namespace App\Http\Controllers;


use App\Models\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    //list of permissions
    public function index()
    {
        $permission =  Permission::all();
        return response()->json([
            "success" => true,
            "message" => "permission list",
            "data"    => $permission

        ]);
    }
    //create module
    public function create(Request $request)
    {
        //Permission Validation
        $request->validate([
            'name'        => 'required',
            'description' => 'required',

        ]);
        $permission = Permission::create($request->only('name', 'description'));
        $permission->modulepermissions()->createMany($request->modules);
        $permission->save();
        return response()->json([
            "success" => true,
            "message" => "permission created",
            "data"    => $permission
        ]);
    }
    public function update(Request $request, $id)
    {
        //permission validation
        $request->validate([
            'name'        => 'required',
            'description' => 'required',

        ]);
        $permission = Permission::findOrFail($id)->update($request->only('name', 'description'));
        return response()->json([
            "success" => true,
            "message" => "permission updated",
            "data"    => $permission
        ]);
    }
    //delete module

    public function destroy($id)
    {
        $permission = Permission::findOrFail($id)->delete();
        return response()->json([
            "success" => true,
            "message" => "permission deleted",
            "data"    => $permission
        ]);
    }
}
