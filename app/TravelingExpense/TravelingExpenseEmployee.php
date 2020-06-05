<?php

namespace App\TravelingExpense;

use Illuminate\Database\Eloquent\Model;

class TravelingExpenseEmployee extends Model
{
    protected $table = 'traveling_expense_employee';
    public $timestamps = false;

    protected $fillable = [
        // 'month', 'year'
    ];

    public function travelingExpense()
    {
        return $this->belongsTo('App\TravelingExpense\TravelingExpense');
    }

    public function employee()
    {
        return $this->belongsTo('App\Employee\Employee');
    }

    public function relationsWithDays()
    {
        return $this->hasMany('App\TravelingExpense\TravelingExpenseEmployeeRelation');
    }
}
