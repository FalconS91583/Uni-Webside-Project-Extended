<?php|
// app/Models/Comment.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = ['message', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
?>