<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Zaposleni extends Model
{
    protected $table = 'zaposleni';
    protected $guarded = [];

    public function opstina()
    {
        return $this->belongsTo('App\Opstina', 'id_korisnika');
    }
}
