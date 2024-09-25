<?php

namespace App\Http\Controllers\Api\post;

use App\Http\Controllers\ApiResponseController;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;


class PostsController extends ApiResponseController
{
    public function index()
    {
        $posts = Post::all();

        // return posts resource for transform  ---- must collection
        return PostResource::collection($posts);


        // return post response json
        return $this->SuccessResponse($posts , 200 , 'true');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all() , [
            'title' => ['required' , 'string'] ,
            'body' => ['required' , 'string'] ,
            'image' => ['required' , 'image'] ,
            'user_id' => ['required' , 'string']
        ]);

        if ($validator->fails()) {
            return $this->ErrorResponse(422 , $validator->messages());
        }

        $image = $request->image;
        $alphabet = '1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $pass = array(); //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < 8; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        $code = implode($pass);

        $format = pathinfo($image->getClientOriginalName(), PATHINFO_EXTENSION);

        $path = "/images/posts_image/";
        $filename = time().'-'.$code.'.'.$format;
        $image->move(public_path().$path, $filename);
        $file_url = $path.$filename;

        $post = Post::create([
           'title' => $request->title ,
           'body' => $request->body ,
           'image' => $file_url ,
           'user_id' => $request->user_id
        ]);

        return $this->SuccessResponse($post , 200 , 'true');

    }

    public function show(Post $post)
    {
        // return post resource for transform
//        return new PostResource($post);

        // return post resource add with
        return (new PostResource($post))->additional([
            'user' => [
                'id' => $post->user->id
            ]
        ]);


        // return post response json
//        return $this->SuccessResponse($post , 200 , 'true');
    }

    public function update(Request $request , Post $post)
    {
        $validator = Validator::make($request->all() , [
            'title' => ['required' , 'string'] ,
            'body' => ['required' , 'string'] ,
            'image' => ['image'] ,
            'user_id' => ['required' , 'string']
        ]);

        if ($validator->fails()) {
            return $this->ErrorResponse(422 , $validator->messages());
        }

        $query = [
            'title' => $request->title ,
            'body' => $request->body ,
            'user_id' => $request->user_id
        ];

        if ($request->has('image')) {
            File::delete(public_path($post->image));
            $image = $request->image;
            $alphabet = '1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $pass = array(); //remember to declare $pass as an array
            $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
            for ($i = 0; $i < 8; $i++) {
                $n = rand(0, $alphaLength);
                $pass[] = $alphabet[$n];
            }
            $code = implode($pass);

            $format = pathinfo($image->getClientOriginalName(), PATHINFO_EXTENSION);

            $path = "/images/posts_image/";
            $filename = time().'-'.$code.'.'.$format;
            $image->move(public_path().$path, $filename);
            $file_url = $path.$filename;
            $query['image'] = $file_url;
        }

        $post->update($query);

        return $this->SuccessResponse($post , 200 , 'post updated');

    }

    public function destroy(Post $post)
    {
        $post = $post->delete();
        return $this->SuccessResponse($post , 200 , 'post deleted');
    }
}
