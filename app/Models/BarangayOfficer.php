<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BarangayOfficer extends Model
{
    protected $fillable = [
        'fullname',
        'email',
        'username',
        'password',
        'contact',
        'address',
        'role',
    ];
    
    protected $hidden = [
        'password',
    ];
}
