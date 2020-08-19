<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;

class Korisnik extends Authenticatable implements JWTSubject
{
    protected $table = 'korisnici';

    protected $guarded = [];

    public function detalji()
    {
        return $this->hasOne('App\DetaljiKorisnika', 'id_korisnika');
    }

    public function lokacijeSkole()
    {
        return $this->hasMany('App\LokacijaSkole', 'id_korisnika');
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function username()
    {
        return 'email';
    }

    public function getAuthPassword()
    {
        return $this->password;
    }
}
