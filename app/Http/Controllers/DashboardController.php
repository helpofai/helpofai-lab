<?php

namespace App\Http\Controllers;

use App\Models\Collection;
use App\Models\Pen;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function __invoke(Request $request): Response
    {
        $user = $request->user();

        $recentPens = $user->pens()
            ->with(['collections:id,name,slug'])
            ->latest()
            ->take(5)
            ->get()
            ->map(fn (Pen $pen) => [
                'id' => $pen->id,
                'title' => $pen->title,
                'slug' => $pen->slug,
                'status' => $pen->status,
                'visibility' => $pen->visibility,
                'updated_at' => $pen->updated_at?->toDateTimeString(),
                'collections' => $pen->collections->map(fn (Collection $collection) => [
                    'id' => $collection->id,
                    'name' => $collection->name,
                    'slug' => $collection->slug,
                ]),
            ]);

        $collections = $user->collections()
            ->withCount('pens')
            ->latest()
            ->take(3)
            ->get(['id', 'name', 'slug', 'is_private', 'created_at'])
            ->map(fn (Collection $collection) => [
                'id' => $collection->id,
                'name' => $collection->name,
                'slug' => $collection->slug,
                'is_private' => $collection->is_private,
                'pens_count' => $collection->pens_count,
                'created_at' => $collection->created_at?->toDateTimeString(),
            ]);

        $stats = [
            'pens_total' => $user->pens()->count(),
            'pens_published' => $user->pens()->where('status', 'published')->count(),
            'collections_total' => $user->collections()->count(),
        ];

        return Inertia::render('Dashboard', [
            'recentPens' => $recentPens,
            'collections' => $collections,
            'stats' => $stats,
        ]);
    }
}
