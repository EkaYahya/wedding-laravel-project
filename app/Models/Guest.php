<?php

/// app/Models/Guest.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Guest extends Model
{
    use HasFactory;

    protected $fillable = ['name',  'phone_number', 'guest_type','slug', 'attended', 'greeting_message', 'will_attend', 'number_of_guests','photo'];

    // Generate slug otomatis saat membuat atau mengupdate nama
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($guest) {
            if (empty($guest->slug)) {
                $guest->slug = Str::slug($guest->name . '-' . uniqid());
            }
        });

        static::updating(function ($guest) {
            if (empty($guest->slug)) {
                $guest->slug = Str::slug($guest->name . '-' . uniqid());
            }
        });
    }
    public function getRouteKeyName()
    {
        return 'slug';
    }

}
