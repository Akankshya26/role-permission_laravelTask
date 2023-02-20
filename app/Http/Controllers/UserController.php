<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    //list of user
    public function index()
    {
        $user = User::all();
        return response()->json([
            "success" => true,
            "message" => "user list",
            "data"    => $user->load('roleusers')
        ]);
    }
    //create user
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
    //user edit
    public function edit($id)
    {
        $user = User::with('roleusers')->findOrFail($id);

        return response()->json([
            "success" => true,
            "message" => "user founded ",
            "data"    => $user
        ]);
    }
    //update user
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
        $user = User::findOrFail($id);
        // if ($user->roleusers()->count() > 0) {
        //     ($user->roleusers()->detach());
        // }
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
    //force delete(data permanently delete)
    public function forceDelete($id)
    {
        $user = User::where('id', $id)->withTrashed()->forceDelete();
        if ($user->roleusers()->count() > 0) {
            ($user->roleusers()->detach());
        }
        $user->forceDelete();
        return response()->json([
            "success" => true,
            "message" => "user deleted permanently",
        ]);
    }
}
