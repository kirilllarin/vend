<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $fillable = [
        'name',
        'country',
        'address',
        'city',
        'zip',
        'vat'
    ];
}
