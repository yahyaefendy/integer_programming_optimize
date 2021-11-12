<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name'
    ];

    public function constraints()
    {
        return $this->hasMany(Constraint::class, 'id_product', 'id');
    }

    public function data() {
        return $this->hasMany(Data::class);
    }

    public function fields() {
        return $this->hasMany(Field::class, 'id_product', 'id');
    }

    public function productData() {
        return $this->hasMany(ProductData::class, 'id_product', 'id');
    }
}
