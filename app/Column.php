<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Column extends Model
{
    protected $casts = [
        'is_archive' => 'boolean'
    ];

    protected $fillable = [
        'order',
        'title',
	    'is_archive'
    ];

    public function cards()
    {
     return $this->hasMany(Card::class);
    }
}
