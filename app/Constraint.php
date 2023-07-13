<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Constraint extends Model
{
    protected $fillable = [
        'id_field', 
        'operator_1', 
        'operator_2', 
        'value_1', 
        'value_2'
    ];
}
