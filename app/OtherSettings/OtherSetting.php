<?php

namespace App\OtherSettings;

use Illuminate\Database\Eloquent\Model;

class OtherSetting extends Model
{
    protected $table = 'other_settings';
    public $timestamps = false;

    protected $fillable = [
        'value'
    ];

    public function user()
    {
        return $this->belongsTo('App\User\User');
    }
}
