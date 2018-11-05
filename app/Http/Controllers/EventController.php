<?php

namespace App\Http\Controllers;

use App\Event;
use App\User;
use Illuminate\Support\Facades\Auth;
use Sabre\VObject\Component\VCalendar;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class EventController extends Controller
{
    public function index()
    {
        $events = $this->getEvents();

        return response()->make($events);
    }

    public function ical($hash)
    {
        $events = $this->getEvents($hash);

        $vcalendar = new VCalendar();
        foreach ($events as $event) {
            $e = [
                'SUMMARY' => $event->title,
                'DTSTART' => new \DateTime($event->start),
            ];

            if ($event->end) {
                $e['DTEND'] = new \DateTime($event->end);
            }

            $vcalendar->add('VEVENT', $e);
        }

        return response($vcalendar->serialize(), 200, [
            'Content-type'        => 'text/calendar; charset=utf-8',
            'Content-Disposition' => 'inline; filename=calendar.ics',
        ]);
    }

    private function getEvents($hash = null)
    {
        $user = null;
        if ($hash) {
            $user = User::whereEventUrl($hash)->first();
            if (!$user) {
                throw new NotFoundHttpException();
            }
        }

        return Event::orderBy('start')
            ->whereHas('topic.users', function ($query) use ($user) {
                if ($user) {
                    $query->where('event_url', $user->event_url);
                } else {
                    if (Auth::check() && !Auth::user()->isAdmin()) {
                        $query->where('id', Auth::user()->id);
                    }
                }
            })
            ->with('message', 'message.files', 'message.user', 'message.event')
            ->get();
    }
}