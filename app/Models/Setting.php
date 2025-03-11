<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $table = 'settings_events';

    protected $fillable = [
        'event_name',
        'user_name',
        'event_date',
        'invitation_count',
        'invitation_link',
        'image_url',
    ];
}
