<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Comment extends Model
{
    protected $fillable = ['content'];
    protected $with = ['user'];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            $model->user_id = Auth::user()->id;
        });
    }

    public function commentable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
