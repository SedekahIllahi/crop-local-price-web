<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    //
    protected $table = 'users_profile';
    protected $fillable = [
        'profile_photo',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
