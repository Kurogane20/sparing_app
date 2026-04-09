<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogEntry extends Model
{
    use HasFactory;

    protected $fillable = ['uid', 'level', 'message', 'logged_at'];

    protected $casts = [
        'logged_at' => 'datetime',
    ];
}
