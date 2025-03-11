<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpotGuest extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone_number',
        'guest_type',
        'slug',
        'photo'
    ];
}