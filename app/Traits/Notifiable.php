<?php

namespace App\Traits;

use App\Mail\Mailer;
use App\Notification;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

trait Notifiable
{
    /**
     * @return mixed
     */
    public function notifications()
    {
        return $this->morphMany(Notification::class, 'related');
    }

    /**
     * @param \App\User $actor
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param $type
     */
    public function notify(User $actor, Model $model, $type)
    {
        $notification = new Notification([
            'type' => $type,
            'actor_id' => $actor->id,
            'user_id' => $this->id,
        ]);

        $model = $model->notifications()->save($notification);
        $model->load('related');

//        Mail::to($this->email)->queue(new Mailer('emails.notification', [
//            'model' => $model,
//        ]));
    }

    /**
     *
     */
    public function notificationMarkAsRead()
    {
        $this->notifications()->where('user_id', Auth::user()->id)->update([
            'read_at' => Carbon::now(),
        ]);
    }
}