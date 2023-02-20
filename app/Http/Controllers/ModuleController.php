<?php

namespace App\Http\Controllers;

use App\Models\Module;
use Illuminate\Http\Request;

class ModuleController extends Controller
{
    //list of modules
    public function index()
    {
        $module = Module::all();
        return response()->json([
            "success" => true,
            "message" => "module list",
            "data"    => $module

        ]);
    }
    //create module
    public function create(Request $request)
    {
        //module validation
        $request->validate([
            'module_code' => 'required',
            'name'        => 'required',
            'is_active'   => 'nullable|boolean',
            'is_in_menu'  => 'nullable|boolean'
        ]);
        $module = Module::create($request->only('module_code', 'name'));
        $module->save();
        return response()->json([
            "success"  => true,
            "message"  => "module created",
            "data"     => $module
        ]);
    }
    //module edit
    public function edit($id)
    {
        $module = Module::find($id);
        if (is_null($module)) {
            return $this->endError('user not found');
        }
        return response()->json([
            "success" => true,
            "message" => "permission founded ",
            "data"    => $module
        ]);
    }
    //update module
    public function update(Request $request, $id)
    {
        //module validation
        $request->validate([
            'module_code' => 'required',
            'name'        => 'required',
            'is_active'   => 'required|boolean',
            'is_in_menu'  => 'required|boolean'
        ]);
        $module = Module::findOrFail($id)->update($request->only('module_code', 'name'));
        return response()->json([
            "success" => true,
            "message" => "module updated",
            "data"    => $module
        ]);
    }
    //delete module

    public function destroy($id, Request $request)
    {
        // $module = Module::findOrFail($id);
        // if ($module->permissions()->count() > 0) {
        //     ($module->modulepermissions()->delete());
        // }
        $request->validate([
            'softDelete' => 'required|boolean'
        ]);
        $module = Module::findOrFail($id);
        if ($request->softDelete) {
            // dd('softDelete');
            if ($module->permissions()->count() > 0) {
                ($module->permissions()->delete());
            }
            $module->delete();
        } else {
            // dd('hardDelete');
            if ($module->permissions()->count() > 0) {
                ($module->permissions()->forceDelete());
            }
            $module->forceDelete();
        }

        return response()->json([
            "success" => true,
            "message" => "module deleted",
            "data"    => $module
        ]);
    }
}
