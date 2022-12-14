<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    //
    public function login(Request $request)
    {
       $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
            'device_name' => ['required'],
       ]);

       $user = User::where('email', $request->email)->first();

       if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
       }

       return $user->createToken($request->device_name)->plainTextToken;
    }

    public function register(Request $request)
    {
       $request->validate([
            'name'                  => ['required'],
            'email'                 => ['required', 'email', 'unique:users,email'],
            'password'              => ['required', 'min:6', 'confirmed'],
       ]);

       User::create([
            'name'        => $request->name,
            'email'       => $request->email,
            'password'    => Hash::make($request->password),
       ]);

       return response()->json(['msg' => 'Registere Successfully']);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['msg' => 'Logout Successfull']);
    }

}
