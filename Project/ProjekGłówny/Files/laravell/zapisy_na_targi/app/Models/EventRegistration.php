<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventRegistration extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'event_name', 'details'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

class EventRegistration extends Model
{
    use HasFactory;
}
