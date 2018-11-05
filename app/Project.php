<?php

namespace App;

use App\Traits\Favoritable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Project extends Model
{
    use Favoritable;

    protected $fillable = [
            'title',
            'description',
            'is_archive',
            'due_date',
        ];

    public static function boot()
    {
        parent::boot();

        static::deleting(function ($model) {
            $model->cards()->delete();
            $model->columns()->delete();
            $model->users()->detach();
        });
    }

    public function columns()
    {
        return $this->hasMany(Column::class)
            ->orderBy('order', 'asc');
    }

    public function completedCards()
    {
        return $this->hasMany(Card::class)->where('is_completed', 1);
    }

    public function cards()
    {
        return $this->hasMany(Card::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
