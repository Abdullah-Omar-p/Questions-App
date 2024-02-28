<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionReads extends Model
{
    protected $table= 'question_reads';

    protected $fillable = [
        'question_id',
        'user_id',
    ];
    use HasFactory;
}
