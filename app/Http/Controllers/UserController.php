<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    //list of user function
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
        $user = User::query();
        //sorting function
        if ($request->sortField && $request->sortOrder) {
            $user = $user->orderBy($request->sortField, $request->sortOrder);
        } else {
            $user = $user->orderBy('id', 'DESC');
        }
        //searching function
        if (request()->has('search')) {
            $user->where('first_name', 'Like', '%' . request()->input('search') . '%');
        }
        //pagination function
        $perpage     = $request->perpage;
        $currentpage = $request->currentpage;
        $user        = $user->skip($perpage * ($currentpage - 1))->take($perpage);

        return response()->json([
            "success" => true,
            "message" => "user list",
            "data"    => $user->get()
        ]);
    }
    //create user function
    public function create(Request $request)
    {
        //user validation
        $request->validate([
            'first_name'     => 'required',
            'last_name'      => 'required',
            'email'          => 'required|unique:users|email',
            'password'       => 'required',
        ]);

        $user = User::create($request->only('first_name', 'last_name', 'email', 'password',));
        $user->roleusers()->attach($request->roles);
        return response()->json([
            "success" => true,
            "message" => "user created",
            "data"    => $user
        ]);
    }
    //user edit function
    public function edit($id)
    {
        $user = User::with('roleusers')->findOrFail($id);

        return response()->json([
            "success" => true,
            "message" => "user founded ",
            "data"    => $user
        ]);
    }
    //update user function
    public function update(Request $request, $id)
    {
        //user validation
        $request->validate([
            'first_name'     => 'required',
            'last_name'      => 'required',
            'email'          => 'required',
            'password'       => 'required',
        ]);
        $user = User::findOrFail($id);
        $user->update($request->only('first_name', 'last_name', 'email', 'password'));
        // $user->roleusers()->detach();
        $user->roleusers()->sync($request->roles);


        return response()->json([
            "success" => true,
            "message" => "user updated",
            "data"    => $user
        ]);
    }
    //delete users(soft delete)
    public function destroy($id, Request $request)
    {
        $request->validate([
            'softDelete' => 'required|boolean'
        ]);
        $user = User::findOrFail($id);
        if ($request->softDelete) {
            // dd('softDelete');
            if ($user->roleusers()->count() > 0) {
                ($user->roleusers()->delete());
            }
            $user->delete();
        } else {
            // dd('hardDelete');
            if ($user->roleusers()->count() > 0) {
                ($user->roleusers()->forceDelete());
            }
            $user->forceDelete();
        }
        $user->forceDelete();
        return response()->json([
            "success" => true,
            "message" => "user deleted",
        ]);
    }
    //restore data from trashed
    public function restore($id)
    {
        User::where('id', $id)->withTrashed()->restore();
        return response()->json([
            "success" => true,
            "message" => "user restored",
        ]);
    }
}
