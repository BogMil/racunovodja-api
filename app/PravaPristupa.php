<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PravaPristupa extends Model
{
    protected $table = 'prava_pristupa';

    protected $guarded = [];

    protected $casts = [
        'dpl' => 'boolean',
        'opiro' => 'boolean',
    ];
}
