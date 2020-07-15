<?php

namespace App\SlanjeMailovaLog;

use Illuminate\Database\Eloquent\Model;

class SlanjeMailovaLog extends Model
{
    protected $table = 'slanje_mailova_log';
    public $timestamps = false;

    protected $fillable = [
        'subject','type','success','error_message','naziv_skole_iz_fajla'
    ];
}
