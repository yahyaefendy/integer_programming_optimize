<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Data extends Model
{
    protected $fillable = [
        'product_id',
        'product_data_id',
        'field_id',
        'value'
    ];

    public function field()
    {
        return $this->hasOne(Field::class, 'id', 'field_id');
    }

    public function constraint()
    {
        return $this->hasOne(Constraint::class, 'id_field', 'field_id');
    }
}
