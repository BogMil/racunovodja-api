<?php

namespace App\TravelingExpense;

use Illuminate\Database\Eloquent\Model;

class TravelingExpenseEmployeeRelation extends Model
{
    protected $table = 'traveling_expense_employee_relation';
    public $timestamps = false;

    public function travelingExpenseEmployee()
    {
        return $this->belongsTo('App\TravelingExpense\TravelingExpenseEmployee');
    }

    public function relation()
    {
        return $this->belongsTo('App\Relation\Relation');
    }
}
