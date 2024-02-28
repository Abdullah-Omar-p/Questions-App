<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserInfoResource extends JsonResource
{
    public function toArray($request)
    {
        if (is_null($this->resource)) {
            return [];
        }

        // Check if the resource is a collection
        if ($this->resource instanceof \Illuminate\Support\Collection) {
            return $this->resource->map(function ($userInfo) {
                return $this->transformUserInfo($userInfo);
            })->all();
        }

        // Check if the resource is a single model instance
        if ($this->resource instanceof \Illuminate\Database\Eloquent\Model) {
            return $this->transformUserInfo($this->resource);
        }

        // If the resource is neither a collection nor a model instance, return an empty array
        return [];
    }

    /**
     * Transform a single user info model into an array.
     *
     * @param  mixed  $userInfo
     * @return array
     */
    protected function transformUserInfo($userInfo)
    {
        return [
            'id' => $userInfo->id ?? null,
            'device' => $userInfo->device ?? null,
            'device_details' => $userInfo->device_details ?? null,
            'brand' => $userInfo->brand ?? null,
            'user_id' => $userInfo->user_id ?? null,
        ];
    }

}
