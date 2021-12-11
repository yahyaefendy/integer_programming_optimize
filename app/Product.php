<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'rasio',
        'slack'
    ];

    public function constraint()
    {
        return $this->hasOne(Constraint::class, 'id_field', 'id');
    }

    public function data() {
        return $this->hasMany(Data::class, 'product_id', 'id');
    }

    public function fields() {
        return $this->hasMany(Field::class, 'id_product', 'id');
    }

    public function productData() {
        return $this->hasMany(ProductData::class, 'id_product', 'id');
    }
}
