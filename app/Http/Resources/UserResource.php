<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray($request)
    {
        // If the resource is a collection, map each item to the toArray method
        if ($this->resource instanceof \Illuminate\Support\Collection) {
            return $this->resource->map(function ($user) {
                return $this->transformUser($user);
            })->all();
        }
        return $this->transformUser($this->resource);
    }

    protected function transformUser($user)
    {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'social_type' => $user->social_type,
            'social_id' => $user->social_id,
            'phone' => $user->phone,
            'role_id' => $user->role_id,
        ];
    }
}
