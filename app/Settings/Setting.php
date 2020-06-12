<?php

namespace App\Settings;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $table = 'settings';
    public $timestamps = false;

    protected $fillable = [
        'value'
    ];

    public function user()
    {
        return $this->belongsTo('App\User\User');
    }
}
