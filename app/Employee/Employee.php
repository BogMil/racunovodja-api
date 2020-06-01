<?php

namespace App\Employee;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $table = 'employee';
    public $timestamps = false;

    protected $fillable = [
        'name', 'last_name', 'first_name', 'jmbg', 'number', 'banc_account', 'municipality_id', 'active'
    ];

    public function municipality()
    {
        return $this->belongsTo('App\Municipality\Municipality');
    }

    public function defaultRelations()
    {
        return $this->belongsToMany('App\Relation\Relation');
    }

    public function user()
    {
        return $this->belongsTo('App\User\User');
    }
}
