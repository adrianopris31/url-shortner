<?php

namespace App\Actions;

use App\Models\ShortUrl;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class JumpToPage
{
    public function handle(string $code)
    {
        $original_url = ShortUrl::where('code', $code)->firstOrFail()->original_url;
        return redirect()->away($original_url);
    }
}
