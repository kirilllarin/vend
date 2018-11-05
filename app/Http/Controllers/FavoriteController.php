<?php

namespace App\Http\Controllers;

use App\Favorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

class FavoriteController extends Controller
{
    public function index()
    {
        $favorites = Favorite::whereUserId(Auth::user()->id)
            ->with('favoritable')
            ->get();

        return response()->make($favorites);
    }
}
