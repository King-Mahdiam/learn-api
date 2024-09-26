<?php

namespace App\Http\Controllers\Api\auth;

use App\Http\Controllers\ApiResponseController;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends ApiResponseController
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all() , [
            'name' => ['required' , 'string' , 'min:2'] ,
            'email' => ['required' , 'email' , 'unique:users,email'] ,
            'password' => ['required' , 'min:3']
        ]);

        if ($validator->fails()) {
            return $this->ErrorResponse(422 , $validator->messages());
        }

        $user = User::create([
            'name' => $request->name ,
            'email' => $request->email ,
            'password' => Hash::make($request->password)
        ]);

        // create token OAtuh for user
        $token = $user->createToken('laravel')->accessToken;

        return $this->SuccessResponse([
            'user' => $user ,
            'token' => $token
        ] , 201);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email', 'exists:users,email'],
            'password' => ['required', 'min:3']
        ]);

        if ($validator->fails()) {
            return $this->ErrorResponse(422 , $validator->messages());
        }

        $user = User::where('email' , $request->email)->first();

        if (!$user or !Hash::check($request->password , $user->password)) {
            return $this->ErrorResponse(402 , 'user not found');
        }

        // create token OAtuh for user
        $token = $user->createToken('laravel')->accessToken;

        return $this->SuccessResponse([
            'user' => $user ,
            'token' => $token
        ] , 201);
    }
}
