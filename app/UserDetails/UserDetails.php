<?php

namespace App\UserDetails;

use Illuminate\Database\Eloquent\Model;

class UserDetails extends Model
{
    protected $table = 'user_details';
    public $timestamps = false;

    protected $fillable = [

    ];

    public function user()
    {
        return $this->belongsTo('App\User\User');
    }

    public function municipality()
    {
        return $this->belongsTo('App\Municipality\Municipality','opstina_id');
    }
}
