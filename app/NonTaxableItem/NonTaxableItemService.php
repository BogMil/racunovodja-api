<?php

namespace App\NonTaxableItem;

use App\Constants\NonTaxableItems;
use DateTime;
use Illuminate\Database\Eloquent\Model;

class NonTaxableItemService
{

    public function getTravelingExpenseValue($month, $year)
    {
        $date = new DateTime();
        $date->setDate($year, $month, 1);

        $value = NonTaxableItem::where('name', NonTaxableItems::TRAVELING_EXPENSE)
            ->first()
            ->values
            ->where('valid_from', '<=', $date->format('Y-m-d'))
            ->where('valid_to', '>=', $date->format('Y-m-d'))
            ->first()
            ->value;
        return floatval($value);
    }
}
