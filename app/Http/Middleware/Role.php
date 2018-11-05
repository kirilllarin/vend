<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\UnauthorizedException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class Role
{
    public function handle(Request $request, Closure $next, $role)
    {
        $accepted = [
            'admin'  => ['admin', 'editor', 'client'],
            'editor' => ['editor', 'client'],
            'client' => ['client'],
        ];

        $userRole = Auth::user()->role;

        if (!in_array($role, $accepted[$userRole])) {

            $message = 'You don\'t have permission to make this operation';
            if ($request->expectsJson()) {
                return response()->make(['error' => [$message]], 403);
            }

            throw new UnauthorizedException();
        }

        return $next($request);
    }
}