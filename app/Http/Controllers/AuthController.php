<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    //user register function
    public function register(Request $request)
    {
        //validation for user registration
        $validateData = $request->validate([
            'first_name'  => 'required',
            'last_name'   => 'required',
            'email'       => 'required',
            'password'    => 'required',
        ]);
        // $request['password'] = Hash::make($request->password);
        $user = User::create([
            'first_name'    => $request->first_name,
            'last_name'     => $request->last_name,
            'email'         => $request->email,
            'password'      => $request->password,
        ]);
        // $user = User::create($validateData);
        //create token when register
        $token = $user->createToken($request->email)->plainTextToken;
        return response()->json(
            [
                'token'   => $token,
                'message' => 'user Created successfully',
                'status'  => 1
            ]
        );
    }
    //user login function
    public function login(Request $request)
    {
        //user login validation
        $validateData = $request->validate([
            'email'    => 'required',
            'password' => 'required',
        ]);
        $user = User::where('email', $request->email)->first();
        if ($user && Hash::check($request->password, $user->password)) {
            // $user = User::where(['email' => $validateData['email'], 'password' => $validateData['password']])->first();
            $token = $user->createToken($request->email)->plainTextToken;
            return response()->json(
                [
                    'token'   => $token,
                    'message' => 'Logged in Successfully',
                    'status'  => 1
                ]
            );
        }
    }
    //logout user function
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'message' => 'logged out',
            'status'  => 'success'
        ]);
    }
}
