<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Navigation extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'url',
        'order',
        'active',
        'display',
        'parent_id',
    ];

    public function child()
    {
        return $this->hasMany(Navigation::class, 'parent_id', 'id');
    }

    public function subChild()
    {
        return $this->hasMany(Navigation::class, 'parent_id', 'id');
    }

    public function parent()
    {
        return $this->belongsTo(Navigation::class, 'parent_id', 'id');
    }
}
