<?php

namespace App\OsnoviceIStopePorezaIDoprinosa;

use Illuminate\Database\Eloquent\Model;

class OSPDVrstePrimanjaZaPeriod extends Model
{
    protected $table = 'ospd_vrste_primanja_za_period';
    public $timestamps = false;

    protected $fillable = [];

    protected $casts = [
        'preracun_na_bruto' => 'float',
        'neoporezivo' => 'float',
        'stopa' => 'float',
    ];

    public function vrstaPrimanja()
    {
        return $this->belongsTo('App\OsnoviceIStopePorezaIDoprinosa\VrstaPrimanja');
    }
}
