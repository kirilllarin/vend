<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class File extends Model
{
    protected $guarded = ['id'];
    protected $appends = ['thumbnail'];

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($model) {
            Storage::delete('uploads/thumbnails/'.$model->filename);
            Storage::delete('uploads/'.$model->filename);
        });
    }

    public function getPathAttribute()
    {
        return '/uploads/'.$this->attributes['filename'];
    }

    public function getThumbnailAttribute()
    {
        if($this->attributes['is_image']) {
            return asset('/uploads/thumbnails/'.$this->attributes['filename']);
        }

        return asset('/images/default.png');
    }

    public function message()
    {
        return $this->belongsTo(Message::class, 'parent')->where('type', 'message');
    }
}
