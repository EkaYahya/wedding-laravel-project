<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WATemplate extends Model
{
    use HasFactory;

    protected $table = 'settings_wa_templates';

    protected $fillable = [
        'template_text',
    ];
}

