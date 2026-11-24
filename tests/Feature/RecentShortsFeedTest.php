<?php

namespace Tests\Feature;

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class RecentShortsFeedTest extends TestCase
{
    public function test_feed_is_unavailable_without_an_api_key(): void
    {
        config()->set('services.youtube.api_key', null);
        Http::fake();

        $this->getJson(route('api.recent-shorts'))
            ->assertOk()
            ->assertExactJson(['items' => [], 'available' => false]);

        Http::assertNothingSent();
    }

    public function test_feed_verifies_recent_embeddable_short_videos_and_caches_the_result(): void
    {
        config()->set('services.youtube.api_key', 'test-api-key');

        $recentDate = now()->subDays(8)->utc()->format('Y-m-d\TH:i:s\Z');
        $oldDate = now()->subMonths(4)->utc()->format('Y-m-d\TH:i:s\Z');
        $videoIds = ['AAAaaa00011', 'BBBbbb00022', 'CCCccc00033', 'DDDddd00044', 'EEEeee00055'];

        Http::fake([
            'https://www.googleapis.com/youtube/v3/search*' => Http::response([
                'items' => array_map(fn (string $videoId): array => ['id' => ['videoId' => $videoId]], $videoIds),
            ]),
            'https://www.googleapis.com/youtube/v3/videos*' => Http::response([
                'items' => [
                    $this->video('AAAaaa00011', $recentDate, 'PT42S', 120, true),
                    $this->video('BBBbbb00022', $recentDate, 'PT1M10S', 300, true),
                    $this->video('CCCccc00033', $oldDate, 'PT30S', 9999, true),
                    $this->video('DDDddd00044', $recentDate, 'PT3M1S', 9998, true),
                    $this->video('EEEeee00055', $recentDate, 'PT45S', 9997, false),
                ],
            ]),
        ]);

        $expectedItems = [
            [
                'id' => 'BBBbbb00022',
                'title' => 'Sample & short',
                'channel' => 'Creator',
                'views' => 300,
                'published_at' => $recentDate,
                'thumbnail_url' => 'https://i.ytimg.com/vi/BBBbbb00022/hqdefault.jpg',
                'duration_seconds' => 70,
            ],
            [
                'id' => 'AAAaaa00011',
                'title' => 'Sample & short',
                'channel' => 'Creator',
                'views' => 120,
                'published_at' => $recentDate,
                'thumbnail_url' => 'https://i.ytimg.com/vi/AAAaaa00011/hqdefault.jpg',
                'duration_seconds' => 42,
            ],
        ];

        $this->getJson(route('api.recent-shorts'))
            ->assertOk()
            ->assertExactJson(['items' => $expectedItems, 'available' => true]);

        $this->getJson(route('api.recent-shorts'))
            ->assertOk()
            ->assertExactJson(['items' => $expectedItems, 'available' => true]);

        Http::assertSentCount(2);
        Http::assertSent(function (Request $request): bool {
            if (! str_contains($request->url(), '/youtube/v3/search?')) {
                return false;
            }

            parse_str((string) parse_url($request->url(), PHP_URL_QUERY), $query);

            return ($query['q'] ?? null) === '#shorts'
                && ($query['order'] ?? null) === 'viewCount'
                && ($query['videoDuration'] ?? null) === 'short'
                && ($query['videoEmbeddable'] ?? null) === 'true'
                && isset($query['publishedAfter']);
        });
    }

    public function test_feed_is_unavailable_when_youtube_rejects_the_request(): void
    {
        config()->set('services.youtube.api_key', 'test-api-key');
        Http::fake(['https://www.googleapis.com/youtube/v3/search*' => Http::response([], 403)]);

        $this->getJson(route('api.recent-shorts'))
            ->assertOk()
            ->assertExactJson(['items' => [], 'available' => false]);

        Http::assertSentCount(1);
    }

    /**
     * @return array{id: string, snippet: array{title: string, channelTitle: string, publishedAt: string}, contentDetails: array{duration: string}, statistics: array{viewCount: string}, status: array{embeddable: bool}}
     */
    private function video(string $videoId, string $publishedAt, string $duration, int $views, bool $embeddable): array
    {
        return [
            'id' => $videoId,
            'snippet' => ['title' => 'Sample &amp; short', 'channelTitle' => 'Creator', 'publishedAt' => $publishedAt],
            'contentDetails' => ['duration' => $duration],
            'statistics' => ['viewCount' => (string) $views],
            'status' => ['embeddable' => $embeddable],
        ];
    }
}
