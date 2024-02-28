<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use mysql_xdevapi\Table;

class UserInfo extends Model
{
    use HasFactory;

    protected $fillable = [
        'device',
        'device_details',
        'user_id',
        'brand',
    ];

    protected $table= 'user_info';
    public function user()
    {
        return $this->belongsToOne(User::class);
    }
}
