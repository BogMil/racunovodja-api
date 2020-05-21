<?php

namespace App\Municipality;

use Illuminate\Database\Eloquent\Model;

class Municipality extends Model
{
    protected $table = 'municipality';
    public $timestamps = false;
    public function employees()
    {
        return $this->hasMany('App\Comment');
    }
}
