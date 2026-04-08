<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Resident extends Model
{
    protected $fillable = [
        // 'user_id',
        'fullname',
        'email',
        'contact',
        'age',
        'address',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}