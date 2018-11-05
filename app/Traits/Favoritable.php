<?php

namespace App\Traits;

use App\Favorite;
use Illuminate\Support\Facades\Auth;

trait Favoritable
{
    public function favorites()
    {
        return $this->morphMany(Favorite::class, 'favoritable');
    }
}