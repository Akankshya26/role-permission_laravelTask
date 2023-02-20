<?php

namespace App\Http\Controllers;

use App\Models\Module;
use App\Models\Permission;
use Illuminate\Http\Request;
use App\Models\ModulePermission;

class PermissionController extends Controller
{
    //list of permissions
    public function index()
    {
        $permission =  Permission::all();
        return response()->json([
            "success" => true,
            "message" => "permission list",
            "data"    => $permission->load('modulepermissions')

        ]);
    }
    //create module
    public function create(Request $request)
    {
        //Permission Validation
        $request->validate([
            'name'                    => 'required',
            'description'             => 'required',
            'is_active'               => 'nullable|boolean',
            'modules.*'               => 'required|array',
            'modules.*.module_id'     => 'required|string',
            'modules.*.add_access'    => 'required|boolean',
            'modules.*.edit_access'   => 'required|boolean',
            'modules.*.delete_access' => 'required|boolean',
            'modules.*.view_access'   => 'required|boolean',

        ]);
        $permission = Permission::create($request->only('name', 'description'));
        $permission->modulepermissions()->createMany($request->modules);
        $permission->save();
        return response()->json([
            "success" => true,
            "message" => "permission created",
            "data"    => $permission->load('modulepermissions')
        ]);
    }
    //permission edit
    public function edit($id)
    {
        $permission = Permission::with('modulepermissions')->findOrFail($id);
        return response()->json([
            "success" => true,
            "message" => "permission founded ",
            "data"    => $permission
        ]);
    }
    //update permission
    public function update(Request $request, $id)
    {
        // permission validation
        $request->validate([
            'name'                    => 'required',
            'description'             => 'required',
            'is_active'               => 'nullable|boolean',
            'modules.*'               => 'required|array',
            'modules.*.module_id'     => 'required|string',
            'modules.*.add_access'    => 'required|boolean',
            'modules.*.edit_access'   => 'required|boolean',
            'modules.*.delete_access' => 'required|boolean',
            'modules.*.view_access'   => 'required|boolean',

        ]);
        $permission = Permission::findOrFail($id);
        $permission->update($request->only('name', 'description'));
        // $permission->modulepermissions()->delete();
        // $permission->modulepermissions()->createMany($request->modules);
        // if (is_array($modulepermissions) || is_object($permission)) {
        // dd($permission);
        foreach ($request['modulepermissions']  as $modules) {
            ModulePermission::updateOrCreate(

                [
                    'permission_id' => $modules->id, 'module_id' => $modules['module_id'],
                    [
                        'add_access'    => $modules['add_access'],
                        'edit_access '  => $modules['edit_access'],
                        'delete_access' => $modules['delete_access'],
                        'view_access'   => $modules['view_access'],
                    ]
                ]
            );
        }

        return response()->json([
            "success" => true,
            "message" => "permission updated",
            "data"    => $permission
        ]);
    }
    // }
    //delete module
    public function destroy($id, Request $request)
    {
        // $permission = Permission::findOrFail($id)->delete();
        // $permission = Permission::find($id);
        // if ($permission->has('roles')) {
        //     $permission->delete();
        // }

        $request->validate([
            'softDelete' => 'required|boolean'
        ]);
        $permission = Permission::findOrFail($id);
        if ($request->softDelete) {
            // dd('softDelete');
            if ($permission->modulepermissions()->count() > 0) {
                ($permission->modulepermissions()->delete());
            }
            $permission->delete();
        } else {
            // dd('hardDelete');
            if ($permission->modulepermissions()->count() > 0) {
                ($permission->modulepermissions()->forceDelete());
            }
            $permission->forceDelete();
        }
        // if ($permission->modulepermissions()->count() > 0) {
        //     ($permission->modulepermissions()->delete());
        // }

        return response()->json([
            "success" => true,
            "message" => "permission deleted",
        ]);
    }
}
