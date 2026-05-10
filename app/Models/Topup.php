<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Topup extends Model
{
    protected $fillable = [
        'user_id',
        'amount',
        'payment_method',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}