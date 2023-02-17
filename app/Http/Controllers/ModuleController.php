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
        ]);
        $module = Module::create($request->only('module_code', 'name'));
        $module->save();
        return response()->json([
            "success"  => true,
            "message"  => "module created",
            "data"     => $module
        ]);
    }
    //update module
    public function update(Request $request, $id)
    {
        //module validation
        $request->validate([
            'module_code' => 'required',
            'name'        => 'required',
        ]);
        $module = Module::findOrFail($id)->update($request->only('module_code', 'name'));
        return response()->json([
            "success" => true,
            "message" => "module updated",
            "data"    => $module
        ]);
    }
    //delete module

    public function destroy($id)
    {
        $module = Module::findOrFail($id)->delete();
        return response()->json([
            "success" => true,
            "message" => "module deleted",
            "data"    => $module
        ]);
    }
}
