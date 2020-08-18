<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Korisnik extends Model
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
}
