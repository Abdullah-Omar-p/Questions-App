<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionComment extends Model
{
    protected $table= 'question_comment';

    protected $fillable = [
        'question_id',
        'user_id',
        'comment',
        'status',
    ];
    use HasFactory;
}
