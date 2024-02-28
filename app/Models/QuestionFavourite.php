<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionFavourite extends Model
{
    protected $table= 'question_favourite';

    protected $fillable = [
        'question_id',
        'user_id',
    ];
    use HasFactory;
}
