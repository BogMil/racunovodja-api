<?php

namespace App\Lokacija;

use Illuminate\Database\Eloquent\Model;

class Lokacija extends Model
{
    protected $table = 'lokacije';
    public $timestamps = false;

    protected $fillable = [
        'naziv'
    ];

    public function user()
    {
        return $this->belongsTo('App\User\User');
    }
}
