<?php

namespace App\Services;

class YoutubeService
{
    public function extractVideoId(string $url): ?string
    {
        $patterns = [
            '/(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/)([a-zA-Z0-9_-]{11})/',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $url, $matches)) {
                return $matches[1];
            }
        }

        return null;
    }

    public function embedUrl(string $videoId): string
    {
        // Use privacy-enhanced mode (no cookies until play)
        return "https://www.youtube-nocookie.com/embed/{$videoId}";
    }
}
