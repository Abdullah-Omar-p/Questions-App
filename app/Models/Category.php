<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'title',
        'name',
        'image',
        'added_by',
        'updated_by',
        'deleted_at',
    ];

    public function questions()
    {
        return $this->hasMany(Question::class);
    }
}
