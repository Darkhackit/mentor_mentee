<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mentor extends Model
{
    use HasFactory;
    public function mentees(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Mentee::class);
    }
}
