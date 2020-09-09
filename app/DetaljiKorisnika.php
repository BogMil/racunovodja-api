<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetaljiKorisnika extends Model
{
    protected $table = 'detalji_korisnika';

    protected $guarded = [];

    public function opstina()
    {
        return $this->belongsTo('App\Opstina', 'id_opstine');
    }
}
