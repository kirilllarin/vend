<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $guarded = ['id'];
    protected $hidden = [
        'related_id',
        'related_type'
    ];

    public function related()
    {
        return $this->morphTo();
    }

    public function actor()
    {
        return $this->belongsTo(User::class, 'actor_id');
    }

    public function getTypeAttribute()
    {
        return str_replace(':', '_', $this->attributes['type']);
    }
}
