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
            "data"    => $user
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
            'password'       => 'required |min:8|confirmed',
        ]);

        $user = User::create($request->only('first_name', 'last_name', 'email', 'password',));
        $user->roleusers()->attach($request->roles);
        return response()->json([
            "success" => true,
            "message" => "user created",
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
            'email'          => 'required|unique:users|email',
            'password'       => 'required |min:8|confirmed',
        ]);
        $user = User::findOrFail($id)->update($request->only('first_name', 'last_name', 'email', 'password'));
        return response()->json([
            "success" => true,
            "message" => "user updated",
            "data"    => $user
        ]);
    }
    //delete users
    public function destroy($id)
    {
        $user = User::findOrFail($id)->delete();
        return response()->json([
            "success" => true,
            "message" => "user deleted",
            "data"    => $user
        ]);
    }
}
