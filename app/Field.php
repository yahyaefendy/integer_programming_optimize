<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Field extends Model
{
    protected $fillable = [
        'name'
    ];

    public function constraint()
    {
        return $this->hasOne(Constraint::class, 'id_field', 'id');
    }

    public function data()
    {
        return $this->belongsTo(Data::class, 'id', 'field_id');
    }
}
