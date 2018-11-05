<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $guarded = ['id'];

    public function message()
    {
        return $this->belongsTo(Message::class);
    }

    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }

}
