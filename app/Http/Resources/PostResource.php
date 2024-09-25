<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{

    // add -- edit wraper on Resources
    public static $wrap = 'post';
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
          'title' => $this->title ,
          'body' => $this->body ,
            'user_id' => $this->user_id , // for example 'user_name' => $this->>user->name
            'created_at' => $this->created_at->format('Y-m-d H:i:s')
        ];
    }
}
