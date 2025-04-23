<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'content',
        'idea_id',
    ];

    public function idea()
    {
        return $this->belongsTo(Idea::class);
    }
}


