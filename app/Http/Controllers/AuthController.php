<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function login(Request $request)
    {
        $credentials  = $request->validate([
            'email' =>  'required|email',
            'password'  =>  'required'
        ]);

        if(Auth::attempt($credentials))
        {
            $user   =   $request->user();
            $token  =   $user->createToken('auth-token');

            return  response()->json([
                'message' => 'Login Success',
                'token' =>  $token->plainTextToken
            ],200);
        }


        return response()->json([
            'message'   =>  'Unautherized'
        ],401);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated  =   $request->validate([
            'name'  =>  'required',
            'email' =>  'required|email',
            'password'  =>  'required',
        ]);

        $user = User::create($validated);
        if($user)
        {
            $token = $user->createToken('user-auth');

            $data = [
            'message'   =>  'Registered successfully',
            'user'      =>  [
                                'name'  =>  $user->name,
                                'email' =>  $user->email,
                            ],
            'token'     =>  $token->plainTextToken,
            ];


            return response()->json($data,200);
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        $user   =   $request->user();
        return response()->json([
            'message'   =>  'success',
            'data'  =>  $user
        ],200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $user   =   $request->user();
        $validated  =   $request->validate([
            'name'  =>  'string|max:255',
            'email' =>  'email|unique:users,email,'.$user->id,
            'password'  =>  'nullable|string|min:5'
        ]);
        // dd($user);
        if($user->update($validated))
        {
            return response()->json([
                'message'   =>  "User updated Success"
            ],200);
        }
        return response()->json([
            'message'   =>  "Updation failed!"
        ],401);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'message'   =>  'Successfully Logout'
        ],200);
    }
}
