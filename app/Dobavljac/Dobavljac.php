<?php

namespace App\Dobavljac;

use Illuminate\Database\Eloquent\Model;

class Dobavljac extends Model
{
    protected $table = 'dobavljaci';
    public $timestamps = false;

    protected $fillable = [
        'naziv','pib','ziro_racun','adresa'
    ];

    public function user()
    {
        return $this->belongsTo('App\User\User');
    }
}
