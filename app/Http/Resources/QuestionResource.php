<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuestionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title'=> $this->title,
            'question'=>$this->question,
            'answer' => $this->answer,
            'category_id'=>$this->category_id,
            'user_id'=>$this->user_id,
            'answered_by' => $this->answered_by,
            'status' => $this->status,
            'count_reads' => $this->count_reads,
        ];
    }
}
