<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'message',
        'type', // name of model of action
        'related_id', // id of model of action
        'user_id', // who causes this notification
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'notification_users');
    }
}
