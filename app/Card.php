<?php

namespace App;

use App\Traits\Notifiable;
use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    use Notifiable;

    protected $fillable = [
        'title',
        'description',
        'due_date',
        'assigned_to',
        'column_id',
        'project_id',
        'is_completed',
    ];

    protected $casts = [
        'logs_length' => 'integer',
    ];

    public static function boot()
    {
        parent::boot();

        static::deleting(function($model) {
            $model->files()->delete();
            $model->tasks()->delete();
            $model->comments()->delete();
            $model->logs()->delete();
            $model->notifications()->delete();
        });
    }

    public function getDescriptionAttribute()
    {
        return strip_tags($this->attributes['description']) === '' ? '' : $this->attributes['description'];
    }

    public function setDueDateAttribute($value)
    {
        if (! is_null($value)) {
            $this->attributes['due_date'] = str_replace('. ', '-', $value);
        } else {
            $this->attributes['due_date'] = null;
        }
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function column()
    {
        return $this->belongsTo(Column::class);
    }

    public function assigned()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function files()
    {
        return $this->hasMany(File::class, 'parent')->where('type', 'card');
    }

    public function tasks()
    {
        return $this->morphMany(Task::class, 'taskable');
    }

    public function tasks_completed()
    {
        return $this->morphMany(Task::class, 'taskable')->where('is_completed', 1);
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function logs()
    {
        return $this->hasMany(Log::class);
    }
}
