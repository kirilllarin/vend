<?php

namespace App\Http\Controllers;

use App\Events\UserCreated;
use App\Http\Requests\UserRequest;
use App\Mail\Mailer;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class UserController extends Controller
{
    /**
     * UserController constructor.
     */
    public function __construct()
    {
        $this->middleware('role:editor', ['except' => ['currentUser']]);
        $this->middleware('role:admin', ['except' => ['index', 'upload', 'currentUser']]);
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::get();

        return response()->make($users);
    }

    /**
     * @param UserRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
        $existing = User::whereEmail($request->input('email'))->first();

        if (!$existing) {
            $password = null;
            if (!$request->input('password')
                || $request->input('password') === ''
            ) {
                $password = Str::random(6);
                $request->merge(['password' => $password]);
            }
            $request->merge(['event_url' => Str::random(24), 'image' => null]);
            $user = User::create($request->all());

            if ($password) {
                Mail::to($user->email)->queue(new Mailer('emails.user-created',
                    [
                        'password' => $password,
                    ]));
            }
        } else {
            $user = $existing;
        }

        return response()->make($user);
    }

    /**
     * @param UserRequest $request
     * @param User        $user
     *
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $request, User $user)
    {
        $user->update($request->all());

        return response()->make($user);
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function currentUser()
    {
        $user = Auth::user();
        $user->load('logs', 'logs.card');

        return response()->make($user);
    }

    /**
     * @param User $user
     */
    public function destroy(User $user)
    {
        $user->delete();
    }
}