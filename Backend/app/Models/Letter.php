<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Letter extends Model
{
    protected $fillable = ['letter'];

    public function preferredLetters()
    {
        return $this->hasMany(PreferredLetter::class, 'letter_id');
    }
}

