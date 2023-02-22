<?php

namespace App\Http\Controllers;

use App\Models\Module;
use App\Models\Permission;
use Illuminate\Http\Request;
use App\Models\ModulePermission;

class PermissionController extends Controller
{
    //list of permissions function
    public function index(Request $request)
    {
        //validation for sorting ,seraching &pagination
        $request->validate([
            'perpage'     => 'required|numeric',
            'currentpage' => 'required|numeric',
            'sortField'   => 'nullable|string',
            'sortOrder'   => 'nullable|in:asc,desc',
            'name'        => 'nullable|string'

        ]);
        //sorting function
        $permission     =  Permission::query();
        if ($request->sortField && $request->sortOrder) {
            $permission = $permission->orderBy($request->sortField, $request->sortOrder);
        } else {
            $permission = $permission->orderBy('id', 'DESC');
        }
        //searching function
        if (request()->has('search')) {
            $permission->where('name', 'Like', '%' . request()->input('search') . '%');
        }
        //pagination function
        $perpage     = $request->perpage;
        $currentpage = $request->currentpage;
        $permission  = $permission->skip($perpage * ($currentpage - 1))->take($perpage);

        return response()->json([
            "success" => true,
            "message" => "permission list",
            "data"    => $permission->get()

        ]);
    }
    //create permission function
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
    //permission edit function
    public function edit($id)
    {
        $permission = Permission::with('modulepermissions')->findOrFail($id);
        return response()->json([
            "success" => true,
            "message" => "permission founded ",
            "data"    => $permission
        ]);
    }
    //update permission function
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
        foreach ($request['modulepermissions']  as $modules) {
            ModulePermission::updateOrCreate(
                ['permission_id' => $permission->id, 'module_id' => $modules['module_id']],
                [
                    'add_access'    => $modules['add_access'],
                    'edit_access '  => $modules['edit_access'],
                    'delete_access' => $modules['delete_access'],
                    'view_access'   => $modules['view_access']
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
    //delete module function
    public function destroy($id, Request $request)
    {
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
        return response()->json([
            "success" => true,
            "message" => "permission deleted",
        ]);
    }
    //restore permission function
    public function restore($id)
    {
        Permission::where('id', $id)->withTrashed()->restore();
        return response()->json([
            "success" => true,
            "message" => "user restored",
        ]);
    }
}
