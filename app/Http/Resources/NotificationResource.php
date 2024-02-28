<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $selectedColumns = [
            'id',
            'title',
            'message',
            'user_id',
        ];

        return array_intersect_key($this->resource->toArray(), array_flip($selectedColumns));
    }
}
