<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User ;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;

class Mentee extends User implements JWTSubject
{
    use HasFactory;
    public function mentor(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Mentor::class);
    }
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims(): array
    {
        return [];
    }
}
