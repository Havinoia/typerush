<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
    protected $fillable = [
        'user_id',
        'wpm',
        'accuracy',
        'total_chars',
        'duration',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
