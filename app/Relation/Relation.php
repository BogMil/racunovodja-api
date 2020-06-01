<?php

namespace App\Relation;

use Illuminate\Database\Eloquent\Model;

class Relation extends Model
{
    protected $table = 'relation';
    public $timestamps = false;

    protected $fillable = [
        'name', 'price'
    ];

    public function defaultRelations()
    {
        return $this->belongsToMany('App\Relation\Relation', 'employee_relation')
            ->as('defaultRelations');
    }

    public function user()
    {
        return $this->belongsTo('App\User\User');
    }
}
