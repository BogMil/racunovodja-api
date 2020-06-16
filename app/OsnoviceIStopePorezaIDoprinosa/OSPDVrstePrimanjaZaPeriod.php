<?php

namespace App\OsnoviceIStopePorezaIDoprinosa;

use Illuminate\Database\Eloquent\Model;

class OSPDVrstePrimanjaZaPeriod extends Model
{
    protected $table = 'ospd_vrste_primanja_za_period';
    public $timestamps = false;

    protected $fillable = [];

    public function vrstaPrimanja()
    {
        return $this->belongsTo('App\OsnoviceIStopePorezaIDoprinosa\VrstaPrimanja');
    }
}
