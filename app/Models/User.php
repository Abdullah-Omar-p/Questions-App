<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    use HasRoles;

    protected $fillable = [
        'name',
        'phone',
        'role',
        'email',
        'password',
    ];

    public function notifications()
    {
        return $this->belongsToMany(Notification::class, 'notification_users');
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }
    public function attachQuestionToUser($questionId)
    {
        $this->questions()->attach($questionId, ['pivot_table_name' => 'question_reads']);
    }

    public function userInfo()
    {
        return $this->hasMany(UserInfo::class);
    }

    public function comments()
    {
        return $this->hasMany(QuestionComment::class);
    }
    public function favoriteQuestions()
    {
        return $this->belongsToMany(QuestionFavourite::class);
    }

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
}
