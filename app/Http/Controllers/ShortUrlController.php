<?php

namespace App\Http\Controllers;

use App\Actions\CreateShortLink;
use App\Actions\JumpToPage;
use Illuminate\Http\Request;

class ShortUrlController extends Controller
{
    public function index($code)
    {
        return (new JumpToPage)->handle($code);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'url' => 'required|url',
        ]);

        $link = (new CreateShortLink)->handle($data['url']);

        return response()->json($link);
    }
}
