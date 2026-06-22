<?php

namespace App\Actions;

use App\Models\ShortUrl;
use Illuminate\Http\RedirectResponse;

class JumpToPage
{
    public function handle(string $code): RedirectResponse
    {
        $original_url = ShortUrl::where('code', $code)->firstOrFail()->original_url;
        return redirect()->away($original_url);
    }
}
