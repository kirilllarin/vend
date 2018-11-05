<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Favorite extends Model
{
    protected $guarded = ['id'];

    protected $hidden = ['favoritable_type'];

    protected $appends = ['type'];

    const types = [
        'topics' => 'App\Topic',
        'projects' => 'App\Project',
    ];

    /**
     * Alter the favoriteable_type to type name
     *
     * @return mixed
     */
    public function getTypeAttribute()
    {
        return array_search($this->attributes['favoritable_type'], self::types);
    }

    /**
     * Toggle favorites on different models,
     * which can be favoritable
     *
     * @param Model $model
     * @return bool
     */
    public static function toggleFavorite(Model $model)
    {
        $favorite = $model->favorites()->firstOrNew([
                'user_id' => Auth::user()->id,
            ]);

        if ($favorite->exists) {
            $favorite->delete();

            return false;
        } else {
            $favorite = $model->favorites()->save($favorite);
            $favorite->load('favoritable');

            return $favorite;
        }
    }

    public function favoritable()
    {
        return $this->morphTo();
    }
}
