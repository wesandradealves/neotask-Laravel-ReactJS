<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory; 

class Song extends Model
{
    use HasFactory; 

    protected $fillable = [
        'title',
        'youtube_link',
        'is_active',
        'plays',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
