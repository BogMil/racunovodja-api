<?php

namespace App\TravelingExpense;

use Illuminate\Database\Eloquent\Model;

class TravelingExpense extends Model
{
    protected $table = 'traveling_expense';
    public $timestamps = false;

    protected $fillable = [
        'month', 'year'
    ];

    public function user()
    {
        return $this->belongsTo('App\User\User');
    }

    public function travelingExpenseEmployees()
    {
        return $this->hasMany('App\TravelingExpense\TravelingExpenseEmployee');
    }

    public function employeesWithRelation()
    {
        return $this->travelingExpenseEmployees()->with(['employee', 'relationsWithDays.relation']);
    }
}
