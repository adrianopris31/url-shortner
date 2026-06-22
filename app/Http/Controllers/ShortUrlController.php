<?php

namespace App\Http\Controllers;

use App\Actions\CreateShortLink;
use App\Actions\JumpToPage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ShortUrlController extends Controller
{
    public function index(string $code): RedirectResponse
    {
        return (new JumpToPage)->handle($code);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'url' => 'required|url',
        ]);

        $link = (new CreateShortLink)->handle($data['url']);

        return response()->json($link);
    }
}
