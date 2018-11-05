<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $casts = [
        'is_completed' => 'boolean'
    ];

    /**
     * @var array
     */
    protected $fillable = [
        'title',
        'is_completed',
        'order'
    ];

    /**
     * Morph to relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function taskable()
    {
        return $this->morphTo();
    }
}
