<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'title',
        'question',
        'answer',
        'category_id',
        'user_id',
        'answered_by',
        'status',
    ];

    public function readers()
    {
        return $this->hasMany(QuestionReads::class);
    }

    public function favoritedBy()
    {
        return $this->belongsToMany(QuestionFavourite::class);
    }

    public function comments()
    {
        return $this->belongsToMany(QuestionComment::class);
    }

}
