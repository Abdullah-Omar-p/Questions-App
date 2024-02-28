<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserNotification extends Model
{
    protected $table= 'notification_users';

    protected $fillable = [
        'notification_id',
        'status',
        'user_id',
    ];
    use HasFactory;
}
