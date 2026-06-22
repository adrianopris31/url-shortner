<?php

namespace App\Actions;

use App\Models\ShortUrl;

class CreateShortLink
{
    public function handle(string $url): ShortUrl
    {
        do {
            $code = str()->random(8);
        } while ($this->exists($code));

        $shortUrl = ShortUrl::create([
            'code' => $code,
            'original_url' => $url,
        ]);

        return $shortUrl;
    }

    private function exists(string $code): bool
    {
        return ShortUrl::where('code', $code)->exists();
    }
}
