<?php

namespace App\Http\Controllers;

use App\Card;
use App\Http\Requests\LogRequest;
use App\Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class LogController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:editor');
    }

    public function index()
    {
        // Default time frame: 1 week
        $start = request('start') ?: Carbon::now()->subDays(7)->format('Y-m-d');
        $end = request('end') ?: Carbon::now()->format('Y-m-d');

        // Filtering logs by user or/and project
        $logs = Log::with('user')
            ->whereHas('user', function ($query) {
                if (request('user_id') && Auth::user()->isAdmin()) {
                    $query->where('id', request('user_id'));
                }
                if (!Auth::user()->isAdmin()) {
                    $query->where('id', Auth::user()->id);
                }
            })
            ->whereHas('card', function ($query) {
                if (request('project_id')) {
                    $query->where('project_id', request('project_id'));
                }
            })
            ->where('created_at', '>=', $start)
            ->where('created_at', '<=', $end)
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->make($logs);
    }

    public function store(LogRequest $request)
    {
        $log = Log::create([
            'length' => $request->length,
            'user_id' => Auth::user()->id,
            'card_id' => $request->card_id,
            'created_at' => $request->date,
            'updated_at' => $request->date,
        ]);

        $log->load('card', 'user');

        return response()->make($log);
    }

    public function current()
    {
        $current = Log::current(Auth::user());
        if (!$current) {
            return $this->latest();
        }

        return response()->make($current);
    }

    public function toggle(Card $card = null)
    {
        // Get currently running log
        $running = Log::current(Auth::user());

        // Check if we today we already have a log for this card
        $today = Log::whereUserId(Auth::user()->id)
            ->whereCardId($card->id)
            ->whereRaw('DATE(created_at) = CURDATE()')->first();

        // If we have a running log, stop it and updated it
        if ($running) {
            $running->update([
                'is_running' => 0,
                'length' => $running->length,
            ]);
            $running->load('card');

            return response()->make($running);
        }

        // Have a card, but not running, start it
        if ($today) {
            $today->update([
                'is_running' => 1,
            ]);
        } else {
            // Create a card's log for today
            Log::create([
                'card_id' => $card->id,
                'is_running' => 1,
                'user_id' => Auth::user()->id,
                'length' => 0,
            ]);
        }

        return response()->make(Log::current(Auth::user()));
    }

    public function destroy(Log $log)
    {
        $this->authorize('destroy', $log);
        $log->delete();
    }
}