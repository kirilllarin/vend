<?php

namespace App;

use App\Traits\Favoritable;
use App\Traits\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Topic extends Model
{
    use Favoritable, Notifiable;

    /**
     * @var array
     */
    protected $fillable = ['title', 'description', 'is_public', 'is_archive'];

    /**
     *
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            $model->user_id = Auth::user()->id;
        });

        static::deleting(function ($model) {
            $model->messages()->delete();
            $model->favorites()->delete();
            $model->users()->detach();
        });
    }

    /**
     * @return mixed
     */
    public function latestMessage()
    {
        return $this->hasOne(Message::class)->latest();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function events()
    {
        return $this->hasMany(Event::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function messages()
    {
        return $this->hasMany(Message::class);
    }
}
