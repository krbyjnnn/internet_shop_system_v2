<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Station extends Model
{
    protected $fillable = [
        'name',
        'password',
        'is_occupied',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
