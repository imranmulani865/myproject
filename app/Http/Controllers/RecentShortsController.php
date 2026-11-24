<?php

namespace App\Http\Controllers;

use App\Services\RecentShortsService;
use Illuminate\Http\JsonResponse;

class RecentShortsController extends Controller
{
    public function __invoke(RecentShortsService $recentShortsService): JsonResponse
    {
        return response()->json($recentShortsService->getPopularRecentShorts());
    }
}
