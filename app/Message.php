<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Message extends Model
{
    protected $guarded = ['id'];
    protected $appends = ['excerpt'];

    public static function boot()
    {
        parent::boot();

        if(Auth::check()) {
            static::saving(function($model) {
               $model->user_id = Auth::user()->id;
            });
        }

        static::deleting(function($model) {
            $model->files()->delete();
            $model->event()->delete();
        });
    }

    public function getExcerptAttribute()
    {
        return strip_tags($this->attributes['message']);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }

    public function event()
    {
        return $this->hasOne(Event::class);
    }

    public function files()
    {
        return $this->hasMany(File::class, 'parent')->where('type', 'message');
    }
}
