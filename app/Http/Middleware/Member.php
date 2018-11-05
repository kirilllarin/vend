<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Member
{
    public function handle(Request $request, Closure $next, $type)
    {
//        $model = $request->route($type);
//
//        if (!$model || !$model->users->contains(Auth::user()->id)) {
//            throw new AuthorizationException('You don\'t have access to view this page');
//        }

        return $next($request);
    }
}