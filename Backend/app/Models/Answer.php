<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    protected $fillable = ['question', 'letters', 'correct_answer', 'user_answer','image','user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}



