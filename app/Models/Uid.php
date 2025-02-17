<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Uid extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'uid', 'lokasi'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
