<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    // add -- edit wraper on Resources
    public static $wrap = 'user';
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
//        return parent::toArray($request);

        return [
          'id' => $this->id ,
          'name' => $this->name ,
          'email' => $this->email ,
            'posts' => PostResource::collection($this->whenLoaded('posts'))
        ];
    }
}
