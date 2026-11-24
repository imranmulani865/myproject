<?php

namespace App\Services;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RecentShortsService
{
    private const SEARCH_URL = 'https://www.googleapis.com/youtube/v3/search';

    private const VIDEOS_URL = 'https://www.googleapis.com/youtube/v3/videos';

    /**
     * @return array{items: list<array{id: string, title: string, channel: string, views: int, published_at: string, thumbnail_url: string, duration_seconds: int}>, available: bool}
     */
    public function getPopularRecentShorts(): array
    {
        $apiKey = trim((string) config('services.youtube.api_key', ''));

        if ($apiKey === '') {
            return $this->unavailableFeed();
        }

        $cacheKey = 'youtube.recent-shorts.global.v1.' . substr(hash('sha256', $apiKey), 0, 12);
        $cachedFeed = Cache::get($cacheKey);

        if (is_array($cachedFeed) && isset($cachedFeed['items'], $cachedFeed['available'])) {
            return $cachedFeed;
        }

        $feed = $this->fetchPopularRecentShorts($apiKey);
        Cache::put($cacheKey, $feed, $feed['available'] ? 21600 : 900);

        return $feed;
    }

    /**
     * @return array{items: list<array{id: string, title: string, channel: string, views: int, published_at: string, thumbnail_url: string, duration_seconds: int}>, available: bool}
     */
    private function fetchPopularRecentShorts(string $apiKey): array
    {
        $publishedAfter = now()->subMonths(3)->utc();

        try {
            $searchResponse = Http::connectTimeout(4)->timeout(10)->get(self::SEARCH_URL, [
                'part' => 'snippet',
                'q' => '#shorts',
                'type' => 'video',
                'order' => 'viewCount',
                'publishedAfter' => $publishedAfter->format('Y-m-d\TH:i:s\Z'),
                'videoDuration' => 'short',
                'videoEmbeddable' => 'true',
                'maxResults' => 50,
                'key' => $apiKey,
            ]);

            if (! $searchResponse->successful()) {
                Log::warning('Recent Shorts search failed.', ['status' => $searchResponse->status()]);

                return $this->unavailableFeed();
            }

            $searchItems = $searchResponse->json('items');
            if (! is_array($searchItems)) {
                return $this->unavailableFeed();
            }

            $videoIds = [];
            foreach ($searchItems as $searchItem) {
                $videoId = $searchItem['id']['videoId'] ?? null;
                if (is_string($videoId) && preg_match('/^[A-Za-z0-9_-]{11}$/', $videoId) === 1) {
                    $videoIds[$videoId] = true;
                }
            }

            if ($videoIds === []) {
                return ['items' => [], 'available' => true];
            }

            $videosResponse = Http::connectTimeout(4)->timeout(10)->get(self::VIDEOS_URL, [
                'part' => 'snippet,contentDetails,statistics,status',
                'id' => implode(',', array_keys($videoIds)),
                'key' => $apiKey,
            ]);

            if (! $videosResponse->successful()) {
                Log::warning('Recent Shorts details lookup failed.', ['status' => $videosResponse->status()]);

                return $this->unavailableFeed();
            }

            $videos = $videosResponse->json('items');
            if (! is_array($videos)) {
                return $this->unavailableFeed();
            }

            $items = [];
            foreach ($videos as $video) {
                if (! is_array($video)) {
                    continue;
                }

                $videoId = $video['id'] ?? null;
                $durationSeconds = $this->durationInSeconds($video['contentDetails']['duration'] ?? null);
                $publishedTimestamp = strtotime((string) ($video['snippet']['publishedAt'] ?? ''));
                $views = $video['statistics']['viewCount'] ?? null;

                if (! is_string($videoId)
                    || ! isset($videoIds[$videoId])
                    || $durationSeconds === null
                    || $durationSeconds > 180
                    || $publishedTimestamp === false
                    || $publishedTimestamp < $publishedAfter->timestamp
                    || ($video['status']['embeddable'] ?? false) !== true
                    || ! is_numeric($views)
                ) {
                    continue;
                }

                $items[] = [
                    'id' => $videoId,
                    'title' => html_entity_decode((string) ($video['snippet']['title'] ?? ''), ENT_QUOTES | ENT_HTML5),
                    'channel' => html_entity_decode((string) ($video['snippet']['channelTitle'] ?? ''), ENT_QUOTES | ENT_HTML5),
                    'views' => (int) $views,
                    'published_at' => gmdate('Y-m-d\TH:i:s\Z', $publishedTimestamp),
                    'thumbnail_url' => "https://i.ytimg.com/vi/{$videoId}/hqdefault.jpg",
                    'duration_seconds' => $durationSeconds,
                ];
            }

            usort($items, fn (array $first, array $second): int => $second['views'] <=> $first['views']);

            return ['items' => array_slice($items, 0, 6), 'available' => true];
        } catch (ConnectionException $exception) {
            Log::warning('Recent Shorts lookup could not reach YouTube.', ['message' => $exception->getMessage()]);

            return $this->unavailableFeed();
        }
    }

    private function durationInSeconds(mixed $duration): ?int
    {
        if (! is_string($duration)
            || preg_match('/^PT(?:(\d+)H)?(?:(\d+)M)?(?:(\d+)S)?$/', $duration, $parts) !== 1
        ) {
            return null;
        }

        $seconds = ((int) ($parts[1] ?? 0) * 3600)
            + ((int) ($parts[2] ?? 0) * 60)
            + (int) ($parts[3] ?? 0);

        return $seconds > 0 ? $seconds : null;
    }

    /**
     * @return array{items: array{}, available: false}
     */
    private function unavailableFeed(): array
    {
        return ['items' => [], 'available' => false];
    }
}
