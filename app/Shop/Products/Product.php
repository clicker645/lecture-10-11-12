<?php

namespace App\Shop\Products;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{

    protected $guarded = ['id'];

    public function getFormattedPriceAttribute()
    {
        return '$ ' . $this->price;
    }

    public function setNameAttribute(string $name)
    {
        $this->attributes['name'] = strtolower($name);
    }
}
