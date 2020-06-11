<?php

namespace App\NonTaxableItem;

use Illuminate\Database\Eloquent\Model;

class NonTaxableItem extends Model
{
    protected $table = 'non_taxable_item';
    public $timestamps = false;

    protected $fillable = [];

    public function values()
    {
        return $this->hasMany('App\NonTaxableItem\NonTaxableItemValue');
    }
}
