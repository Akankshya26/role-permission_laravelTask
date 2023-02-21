<?php

namespace App\Http\Controllers;

use App\Models\Module;
use Illuminate\Http\Request;

class ModuleController extends Controller
{
    //list of modules
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
        //sorting
        $module = Module::query();
        if ($request->sortField && $request->sortOrder) {
            $module = $module->orderBy($request->sortField, $request->sortOrder);
        } else {
            $module = $module->orderBy('id', 'DESC');
        }
        //pagination
        $perpage = $request->perpage;
        $currentpage = $request->currentpage;
        $module = $module->skip($perpage * ($currentpage - 1))->take($perpage);
        //searching
        if (request()->has('search')) {
            $module->where('name', 'Like', '%' . request()->input('search') . '%');
        }
        return response()->json([
            "success" => true,
            "message" => "module list",
            "data"    => $module->get()

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
        $request->validate([
            'softDelete' => 'required|boolean'
        ]);
        if ($request->softDelete) {
            Module::findOrFail($id)->delete();
        } else {
            Module::findOrFail($id)->forceDelete();
        }
        return response()->json([
            "success" => true,
            "message" => "module deleted",

        ]);
    }
    public function restore($id)
    {
        Module::where('id', $id)->withTrashed()->restore();
        return response()->json([
            "success" => true,
            "message" => "user restored",
        ]);
    }
}
