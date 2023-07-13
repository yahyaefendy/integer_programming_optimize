<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductData extends Model
{
    protected $fillable = [
        'id_product'
    ];

    public function data() {
        return $this->hasMany(Data::class, 'product_data_id', 'id_product');
    }
}
