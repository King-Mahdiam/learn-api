<?php

namespace App\Http\Controllers\Api\user;

use App\Http\Controllers\ApiResponseController;
use App\Http\Resources\PostResource;
use App\Http\Resources\UserResource;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UsersController extends ApiResponseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $users = User::all();

        // return users resource for transform  ---- must collection
//        return UserResource::collection($users);

        // return users posts relation ---- must collection
        return UserResource::collection($users->load('posts'));

        // return user response json
//        return $this->SuccessResponse($users , 200 , 'true');

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
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
            'password' => encrypt($request->password)
        ]);

        return $this->SuccessResponse($user , 201 , 'user created');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {

        // return user resource for transform
//        return new UserResource($user);

        // return user posts relation
        return new UserResource($user->load('posts'));

        // return user response json
//        return $this->SuccessResponse($user , 200 , 'true');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $validator = Validator::make($request->all() , [
            'name' => ['required' , 'string' , 'min:2'] ,
            'email' => ['required' , 'email' , 'unique:users,email,' . $user->id] ,
            'password' => ['min:3']
        ]);

        if ($validator->fails()) {
            return $this->ErrorResponse(422 , $validator->messages());
        }

        $user->update([
            'name' => $request->name ,
            'email' => $request->email ,
            'password' => encrypt($request->password)
        ]);

        return $this->SuccessResponse($user , 201 , 'user updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $result = $user->delete();
        return $this->SuccessResponse($result , 200 , 'user deleted');
    }
}
