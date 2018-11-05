<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    protected $fillable = [
        'length',
        'card_id',
        'is_running',
        'user_id',
	    'created_at',
	    'updated_at'
    ];

    /**
     * @var array
     */
    protected $with = ['card'];

    /**
     * Relation: Belongs to a card
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function card()
    {
        return $this->belongsTo(Card::class);
    }

    /**
     * Relation: Belongs to a user
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Return the length attribute
     * If running, we returns with the elapsed time
     *
     * @return mixed
     */
    public function getLengthAttribute()
    {
        if(isset($this->attributes['is_running']) && $this->attributes['is_running'] == 1) {
            return $this->attributes['length'] + (time() - strtotime($this->attributes['updated_at']));
        }

        return $this->attributes['length'];
    }

    /**
     * Return current running log for a given user
     *
     * @param $user
     * @return mixed
     */
    public static function current($user)
    {
        return self::whereUserId($user->id)
            ->whereIsRunning(1)
            ->first();
    }
}
