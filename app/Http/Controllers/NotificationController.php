<?php

namespace App\Http\Controllers;

use App\Notification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function get()
    {
        $notifications =  Notification::where('user_id', Auth::user()->id)
            ->with('actor', 'related')
            ->latest()
            ->get();

        return response()->make($notifications);
    }

    public function read()
    {
        Notification::where('user_id', Auth::user()->id)
            ->update([
                'read_at' => Carbon::now()
            ]);
    }

    public function clear()
    {
        Auth::user()->notifications()->delete();
    }
}
