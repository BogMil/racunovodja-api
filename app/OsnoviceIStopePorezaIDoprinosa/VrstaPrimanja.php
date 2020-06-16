<?php

namespace App\OsnoviceIStopePorezaIDoprinosa;

use Illuminate\Database\Eloquent\Model;

class VrstaPrimanja extends Model
{
    protected $table = 'vrste_primanja';
    public $timestamps = false;

    protected $fillable = [];

    public function vrednosti()
    {
        return $this->hasMany('App\OsnoviceIStopePorezaIDoprinosa\OSPDVrstePrimanjaZaPeriod');
    }
}
