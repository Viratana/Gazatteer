<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
class UserController extends Controller
{
    // 

    public function login(Request $request)
    {
        $user= User::where('email', $request->email)->first();
        // print_r($data);
            if (!$user || !Hash::check($request->password, $user->password)) {
                return response([
                    'message' => ['These credentials do not match our records.']
                ], 404);
            }
            $token = $user->createToken('my-app-token')->plainTextToken;
            $response = [
                'user' => $user,
                'token' => $token
            ];
            return response($response, 201);
    }

    public function register(Request $request){
        $data = [
            'name'    => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ];
        $user = User::create($data);
        if($user){
            return response([
                'message' => ['User has been registered successfully!']
            ], 200);
        } else {
            return response([
                'message' => ['Something Went Wrong!']
            ], 404);
        }
    }
}

