<?php

namespace App\NonTaxableItem;

use Illuminate\Database\Eloquent\Model;

class NonTaxableItemValue extends Model
{
    protected $table = 'non_taxable_item_value';
    public $timestamps = false;

    protected $fillable = [];

    public function nonTaxableItem()
    {
        return $this->belongsTo('App\NonTaxableItem\NonTaxableItem');
    }
}
